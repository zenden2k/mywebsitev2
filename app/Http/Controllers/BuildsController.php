<?php

namespace App\Http\Controllers;

use App\Models\PackageDownload;
use App\Models\Page;
use Illuminate\Http\Request;

class BuildsController extends StaticPageController
{
    public function index(Request $request)
    {
        $builds = PackageDownload::getBuilds('nightly');

        $page = Page::where('alias', '=', 'imageuploader_nightly')->first();

        $data = $this->getCommonData($request, $page);
        $data += [
            'builds' => $builds,
        ];

        return view('builds', $data);
    }

    public function downloads(Request $request)
    {
        $builds = PackageDownload::getBuilds('release');

        $page = Page::where('alias', '=', 'imageuploader_downloads')->first();

        $data = $this->getCommonData($request, $page);
        $data += [
            'builds' => $builds,
        ];

        return view('downloads', $data);
    }
}
