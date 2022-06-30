<?php

namespace App\Http\Controllers;

use App\Models\Tab;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

class SiteController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $tabs = Tab::where('active', '=', 1)->orderBy('orderNumber')->get();

        $revision = @file_get_contents( base_path().'/revision' );
        if ( $revision === false ) {
            $revision = 1;
        } else {
            $revision = trim( $revision );
        }

        View::share ([
            '__lang' => 'ru',
            'metaDescription' => '',
            'metaKeywords' => '',
            'openGraphImage' => '',
            'title' => '',
            '__canonical_url' => '',
            'url_prefix' => '',
            '__en_link' => '',
            '__ru_link' => '',
            '__domain_name' => request()->getHost(),
            '__tabs' => $tabs,
            '__revision' => $revision
        ]);

    }
}
