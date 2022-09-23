<?php

namespace App\Http\Controllers;


use App\Models\Page;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function index()
    {
        $month = date('n') - 1;
        $day = date('j');

        $cacheKey = 'parser_day_info_v3_'.$month.'_'.$day;
        $res = \Cache::get($cacheKey);
        if ($res === null) {
            $months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа',
                'сентября', 'октября', 'ноября', 'декабря');

            $month_name = $months[$month];

            $res = $this->parseDay('Категория:Умершие_' . $day . '_' . $month_name);
            \Cache::put($cacheKey, $res, now()->addDay());
        }
        return view('parser.test', ['people' => $res]);
    }

    private function parseDay($day)
    {
        $res = [];
        $response1 = \Http::get('https://ru.wikipedia.org/w/api.php?action=query&generator=categorymembers&gcmtitle=' . urlencode($day) . '&prop=categories&cllimit=max&gcmlimit=max&format=json');
        $data = $response1->json();
        $i = 0;
        //$page_id_titles = [];
        foreach (array_chunk($data['query']['pages'], 50) as $chunk) {

            $page_titles = [];
            foreach ($chunk as $page) {
                $page_titles[] = $page['title'];
               //$page_id_titles[$page['pageid']] = $page['title'];
            }

            $page_title_str = implode('|', $page_titles);
            $i++;
            /*if ($i > 5) {
                break;
            }*/

            $url = 'https://www.wikidata.org/w/api.php?action=wbgetentities&sites=ruwiki&props=info|sitelinks|claims&titles=' . urlencode($page_title_str) . '&format=json';
            $response = \Http::get($url);
            if (!$response->ok()) {
                echo 'Error while loading info!';
                continue;
            }
            $str2 = $response->body();
            $data2 = $response->json();
            foreach ($data2['entities'] as $entity) {
                if ($entity) {
                    $title = $entity['sitelinks']['ruwiki']['title'];

                    $property = isset($entity['claims']['P569'][0]) ? $entity['claims']['P569'][0] : null;

                    $datavalue = isset($property['mainsnak']['datavalue']) ? $property['mainsnak']['datavalue'] : null;

                    if (!$datavalue) {
                        continue;
                    }

                    $property_death = isset($entity['claims']['P570'][0]) ? $entity['claims']['P570'][0] : null;

                    $datavalue_death = isset($property_death['mainsnak']['datavalue']) ? $property_death['mainsnak']['datavalue'] : null;

                    if (!$datavalue_death) {
                        continue;
                    }
                    $date = $datavalue['value']['time'];
                    $born = new \DateTime($date);

                    $date = $datavalue_death['value']['time'];
                    $dead = new \DateTime($date);

                    $res[] = [
                        'title' =>   $title,
                        'born' => $born->format('d.m.Y'),
                        'dead' => $dead->format('d.m.Y')
                    ];


                }
            }

        }
        return $res;
    }

    public function updateServerList(Request $request) {
        $hash = \Hash::make($request->post('password'));

        if (\Hash::check($hash, '$2y$10$.juBxRgp1YEp2OXxQsAIeuqw5DZTH5hUrlQhXkms83gioPrgGMgGm')) {
               return [
                   'message' => "Invalid password",
                   'success' => false
               ];
        }

        if (!$request->hasFile('file')) {
            return [
                'message' => "No file",
                'success' => false
            ];
        }

        if ($request->hasFile('icons')) {
            $zip = new \ZipArchive();
            $status = $zip->open($request->file("icons")->getRealPath());
            if ($status !== true) {
                return [
                    'success' => false,
                    'message' => 'Unable to unzip archive (1)'
                ];
            }
            if ($zip->extractTo(public_path().'/images/servericons/') !== true){
                return [
                    'success' => false,
                    'message' => 'Unable to unzip archive (2)'
                ];
            }
            $zip->close();
        }

        $file = $request->file('file');
        $src = $file->path();

        $xml = new \SimpleXMLElement(file_get_contents($src));

        $servers = array();

        foreach ( $xml->Server as $server) {
            $server_name = (string)$server['Name'];
            $server_type = 'image';
            if ( !empty($server['FileHost'])) {
                $server_type = 'file';
            }
            if (!empty($server['Type'])) {
                $server_type = $server['Type'];
            }
            $servers[$server_name] = array(
                'Name'         => (string)$server['Name'],
                'Authorize' => !empty($server['Authorize'])?$server['Authorize'] : 0,
                'Type' => (string)$server_type
            );

        }
        foreach ( $xml->Server2 as $server) {
            $server_name = (string)$server['Name'];
            $servers[$server_name] = array(
                'Name'         => (string)$server['Name'],
                'Authorize' => !empty($server['Authorize'])?$server['Authorize'] : 0,
                'Type' => (string)$server['Type']
            );

        }
        foreach ( $xml->Server3 as $server) {
            $server_name = (string)$server['Name'];
            $server_type = 'image';
            if ( !empty($server['FileHost'])) {
                $server_type = 'file';
            }
            if (!empty($server['Type'])) {
                $server_type = $server['Type'];
            }
            $servers[$server_name] = array(
                'Name'         => (string)$server['Name'],
                'Authorize' => !empty($server['Authorize'])?$server['Authorize'] : 0,
                'Type' => $server_type
            );

        }
        usort($servers, function($a, $b) {
            return strcasecmp ($a['Name'], $b['Name']);
        });
        $result_text  = '<table style="width:80%"><tr><td style="vertical-align: top;"><h3 style="text-align: center">Image hostings</h3>';
        $result_text .= $this->generateTable($servers,'image');
        $result_text .= '</td><td style="vertical-align: top;"><h3 style="text-align: center">File hostings</h3>'.$this->generateTable($servers,'file');
        $result_text .= '</td><td style="vertical-align: top;"><h3 style="text-align: center">URL shorteners</h3>'.$this->generateTable($servers,'urlshortening');
        $result_text .= '</td></tr></table>';

        $page = Page::where('alias','=','imageuploader_servers')->first();
        $success = false;
        if ($page) {
            $page->text_ru =  preg_replace('/\<!--table--\>(.+?)\<!--\/table--\>/ius', '<!--table-->'.$result_text.'<!--/table-->', $page->text_ru);
            $page->text_ru =  preg_replace('/список обновлен [0-9\.]+/ius', "список обновлен ".date("d.m.Y"), $page->text_ru);
            $page->text_en =  preg_replace('/\<!--table--\>(.+?)\<!--\/table--\>/ius', '<!--table-->'.$result_text.'<!--/table-->', $page->text_en);
            $page->text_en =  preg_replace('/list updated [0-9\.]+/ius', "list updated ".date("d.m.Y"), $page->text_en);
            // echo $page->text;
            $page->save();
            $success = true;
        }

        return [
            'success' => $success
        ];
    }

    private function generateTable($servers, string $type): string {
        $result_text = '<table class="server-list"><thead><tr><th></th><th>Name</th><th>Account</th></tr></thead>';
        $i=0;
        $icons_dir = public_path() . "/images/servericons/";
        foreach ( $servers as $server ) {
            if ($server['Type']!= $type){
                continue;
            }

            $server_name = $server['Name'];
            $result_text .= "<tr  class='".($i % 2 ? 'odd':'even')."'><td class='nonCopyable'>";

            if ( file_exists($icons_dir . strtolower($server_name) . '.ico' ) ) {
                $result_text .= "<img src='/images/servericons/". strtolower($server_name) . ".ico' align='middle' width='16' height='16'>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            $result_text .= "</td><td>" . $server_name ."</td>";
            $autorization = ($server['Authorize']?'Yes' : '&nbsp;-');
            if ( $server['Authorize'] == 2 ) {
                $autorization .= ' (required)';
            }
            $result_text .= "<td  class='nonCopyable ".($server['Authorize'] ? "" : "gray")."'>".$autorization."</td></tr>\r\n";
            $i++;
        }
        $result_text .= '</table>';
        return $result_text;
    }
}
