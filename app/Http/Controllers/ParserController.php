<?php

namespace App\Http\Controllers;


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
}
