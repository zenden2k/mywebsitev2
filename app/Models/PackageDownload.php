<?php


namespace App\Models;


use App\Helpers\FileHelper;
use App\Helpers\LocaleHelper;

class PackageDownload
{
    const REL_PATH = [
        'nightly' => '/files/ImageUploader/Packages/',
        'release' => '/files/ImageUploader/Releases/'
    ];

    public static function getBuilds(string $type = 'nightly'): array
    {
        $relPath = self::REL_PATH[$type] ?? '';
        $allBuildsDir = public_path() . $relPath;

        $builds = [
        ];

        foreach (new \DirectoryIterator($allBuildsDir) as $fileInfo) {
            if ($fileInfo->isDot()) continue;

            if ($type === 'nightly' && !str_contains($fileInfo->getFilename(), 'nightly')) {
                continue;
            }
            $buildDir = $allBuildsDir . $fileInfo->getFilename() . '/';
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
                $buildFile['download_url'] = $relPath . $fileInfo->getFilename() . '/' . $buildFile['path'];
                $buildFile['hash_file_url'] = $buildFile['download_url'] . ".sha256";
                $buildFile['size_readable'] = !empty($buildFile['size']) ? FileHelper::humanFilesize($buildFile['size']) : '';
                if ($type !== 'release') {
                    $build['subproducts'][$buildFile['os']][$buildFile['subproduct']][$buildFile['arch']][] = $buildFile;
                } else {
                    $build['subproducts'][$buildFile['subproduct']][$buildFile['os']][$buildFile['arch']][] = $buildFile;
                }
            }
            $builds[] = $build;
        }

        usort($builds, function ($a, $b) {
            return -($a['build_number'] <=> $b['build_number']);
        });

        return $builds;
    }

    /*public static function getReleases(): array
    {
        $builds = self::getBuilds('release');
        $build = current($builds);

        foreach ( )
    }*/
}
