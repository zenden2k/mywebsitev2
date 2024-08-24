<?php


namespace App\Models;


use App\Helpers\FileHelper;
use App\Helpers\LocaleHelper;
use DeviceDetector\DeviceDetector;
use DeviceDetector\ClientHints;

class PackageDownload
{
    public const int NIGHTLY = 1;
    public const int RELEASE = 2;

    protected const REL_PATH = [
        self::NIGHTLY => '/files/ImageUploader/Packages/',
        self::RELEASE => '/files/ImageUploader/Releases/'
    ];

    protected const string COMMIT_URL = 'https://github.com/zenden2k/image-uploader/commit/';

    public static function getBuilds(int $type = self::NIGHTLY, string $os = '', string $arch = ''): array
    {
        $relPath = self::REL_PATH[$type] ?? '';
        $allBuildsDir = public_path() . $relPath;

        $builds = [
        ];

        foreach (new \DirectoryIterator($allBuildsDir) as $fileInfo) {
            if ($fileInfo->isDot()) continue;

            if ($type === self::NIGHTLY && !str_contains($fileInfo->getFilename(), 'nightly')) {
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
                $commit['commit_url'] = self::COMMIT_URL . $commit['commit_hash'];
                $commit['commit_hash_short'] = substr($commit['commit_hash'], 0, 8);
                $d = new \DateTime($commit['date']);
                $commit['datetime'] = $d->format('Y-m-d H:i:s');
                $commit['date'] = $d->format('Y-m-d');
            }
            unset($commit);
            $build['commit_url'] = self::COMMIT_URL . $build["commit_hash"];
            $buildDate = new \DateTime($build['date']);
            $build['date'] = $buildDate->format('Y-m-d');
            $build['datetime'] = $buildDate->format('Y-m-d H:i:s');
            $build['time_ago'] = LocaleHelper::timeAgo($buildDate);
            $build['commit_hash_short'] = substr($build['commit_hash'], 0, 8);

            foreach ($jsonData['files'] as $buildFile) {
                $buildFile['download_url'] = $relPath . $fileInfo->getFilename() . '/' . $buildFile['path'];
                $buildFile['hash_file_url'] = $buildFile['download_url'] . ".sha256";
                $buildFile['size_readable'] = !empty($buildFile['size']) ? FileHelper::humanFilesize($buildFile['size']) : '';
                if ($os && strcasecmp($buildFile['os'], $os)) {
                    continue;
                }

                if ($arch && strcasecmp($buildFile['arch'], $arch)) {
                    continue;
                }

                if ($type !== self::RELEASE) {
                    $build['subproducts'][$buildFile['os']][$buildFile['subproduct']][$buildFile['arch']][] = $buildFile;
                } else {
                    $build['subproducts'][$buildFile['subproduct']][$buildFile['os']][$buildFile['arch']][] = $buildFile;
                }
            }

            $builds[] = $build;
        }

        usort($builds, static function ($a, $b) {
            return -($a['build_number'] <=> $b['build_number']);
        });

        return $builds;
    }

    public static function getBuildForCurrentPlatform(): array
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT']; // change this to the useragent you want to parse
        $clientHints = ClientHints::factory($_SERVER);
        $dd = new DeviceDetector($userAgent, $clientHints);
        $dd->parse();
        return self::getBuildForPlatform($dd);
    }

    public static function getBuildForPlatform(\DeviceDetector\DeviceDetector $dd): array
    {
        $os = $dd->getOs();
        if (!is_array($os)) {
            return [];
        }
        $platform = $os['platform'];
        if(!strcasecmp($platform, 'arm')) {
            $platform = 'armv8';
        }
        $builds = self::getBuilds(self::RELEASE, $os['name'], $platform);
        return $builds? current($builds): [];
    }


    public static function getCompatibleBuild(string $subproductName, string $packageType = 'installer'): ?array
    {
        $build = self::getBuildForCurrentPlatform();
        if (empty($build['subproducts'])) {
            return null;
        }
        //dd($build);
        foreach ($build['subproducts'] as $name => $subproduct) {
            if (str_contains($name, $subproductName)) {
                $packages = current(current($subproduct));
                foreach ($packages as $package) {
                    if (str_contains(strtolower($package['name']), $packageType)) {
                        $package['build_number'] = $build['build_number'];
                        $package['version_clean'] = $build['version_clean'];
                        return $package;
                    }
                }
            }
        }
        return null;
    }

    /*public static function getReleases(): array
    {
        $builds = self::getBuilds('release');
        $build = current($builds);

        foreach ( )
    }*/
}
