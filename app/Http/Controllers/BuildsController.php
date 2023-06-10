<?php

namespace App\Http\Controllers;

use App\Helpers\LocaleHelper;
use App\Models\Page;
use Illuminate\Http\Request;

class BuildsController extends StaticPageController
{
    const PACKAGES_REL_PATH = '/files/ImageUploader/Packages/';

    public function index(Request $request)
    {

        $allBuildsDir = public_path() . self::PACKAGES_REL_PATH;
        $builds = [
        ];
        foreach (new \DirectoryIterator($allBuildsDir) as $fileInfo) {
            if($fileInfo->isDot()) continue;

            if (!str_contains($fileInfo->getFilename(), 'nightly')) {
                continue;
            }
            $buildDir = $allBuildsDir . $fileInfo->getFilename().'/';
            $buildInfoPath = $buildDir . "build_info.json";
            $jsonContents = file_get_contents($buildInfoPath);

            if ($jsonContents === FALSE) {
                continue;
            }

            $jsonData = json_decode($jsonContents, true);
            if (!$jsonData) {
                continue;
            }
            $build = [
            ];
            foreach ($jsonData as $key => &$val) {
                if (!is_object($val) && !is_array($val)) {
                    $build[$key] = $val;
                }
            }
            unset($val);
            $build['commits'] = $jsonData['commits'] ?? [];
            foreach ($build['commits'] as &$commit) {
                $commit['commit_url'] = "https://github.com/zenden2k/image-uploader/commit/" . $commit['commit_hash'];
                $commit['commit_hash_short'] = substr($commit['commit_hash'], 0, 8);
                $d = new \DateTime($commit['date']);
                $commit['datetime'] = $d->format('Y-m-d H:i:s');
                $commit['date'] = $d->format('Y-m-d');
            }
            unset($commit);
            $build['commit_url'] = "https://github.com/zenden2k/image-uploader/commit/" . $build["commit_hash"];
            $buildDate = new \DateTime($build['date']);
            $build['date'] = $buildDate->format('Y-m-d');
            $build['datetime'] = $buildDate->format('Y-m-d H:i:s');
            $build['time_ago'] = LocaleHelper::timeAgo($buildDate);
            $build['commit_hash_short'] = substr($build['commit_hash'], 0, 8);
            unset($val);
            foreach ($jsonData['files'] as $buildFile) {
                $buildFile['download_url'] = self::PACKAGES_REL_PATH . $fileInfo->getFilename() .'/'.$buildFile['path'];
                $buildFile['hash_file_url'] = $buildFile['download_url'] . ".sha256";
                $build['subproducts'][$buildFile['os']][$buildFile['subproduct']][$buildFile['arch']][] = $buildFile;
            }
            $builds[] = $build;
        }
        usort($builds,function($a, $b) {
            return -($a['build_number'] <=> $b['build_number']);
        });

        $page = Page::where('alias', '=', 'imageuploader_nightly')->first();

       // [$leftPageBlocks, $bottomPageBlocks] = $this->getBlocks($page);
        $data =   $this->getCommonData($request, $page);
        $data += [
            'builds' => $builds,
            //'currentTab' => 'imageuploader',
            //'title' => __('Zenden2k Image Uploader Nightly Builds'),
        ];

        return view('builds', $data);

    }

}
