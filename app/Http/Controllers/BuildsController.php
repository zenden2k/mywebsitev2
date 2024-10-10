<?php

namespace App\Http\Controllers;

use App\Models\PackageDownload;
use App\Models\Page;
use Illuminate\Http\Request;

class BuildsController extends StaticPageController
{
    public function index(Request $request)
    {
        $builds = PackageDownload::getBuilds(PackageDownload::NIGHTLY);

        $page = Page::where('alias', '=', 'imageuploader_nightly')->first();

        $data = $this->getCommonData($request, $page);
        $data += [
            'builds' => $builds,
            'showVersion' => false
        ];

        return view('builds', $data);
    }

    public function downloads(Request $request)
    {
        $builds = array_slice(PackageDownload::getBuilds(PackageDownload::RELEASE),0, 1);

        $page = Page::where('alias', '=', 'imageuploader_downloads')->first();

        $data = $this->getCommonData($request, $page);
        $data += [
            'builds' => $builds,
        ];

        return view('downloads', $data);
    }

    public function oldVersions(Request $request)
    {
        $arr = PackageDownload::getBuilds(PackageDownload::RELEASE);
        $builds = array_slice($arr,1);

        $page = Page::where('alias', '=', 'imageuploader_old_versions')->first();

        $data = $this->getCommonData($request, $page);
        $data += [
            'builds' => $builds,
            'showVersion' => true,
            'oldVersions' => true
        ];

        return view('builds', $data);
    }
}
