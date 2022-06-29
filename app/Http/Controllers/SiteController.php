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
        View::share ([
            '__lang' => 'ru',
            'meta_description' => '',
            'meta_keywords' => '',
            'open_graph_image' => '',
            'title' => '',
            '__canonical_url' => '',
            'url_prefix' => '',
            '__en_link' => '',
            '__ru_link' => '',
            '__domain_name' => 'svistunov.dev',
            '__tabs' => $tabs
        ]);

    }
}
