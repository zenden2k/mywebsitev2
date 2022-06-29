<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\SidebarBlock;
use Illuminate\Http\Request;

class StaticPageController extends SiteController
{
    public function index(Request $request)
    {
        $uri =  $request->route()->parameter('any') ?? '';

        $page = Page::where('alias', '=', $uri)->first();

        if (!$page) {
            die();
        }
        $pageBlocks = $page->blocks()->orderBy('orderNumber')->get();
        $sidebarBlocks = $page->sidebarBlocks->all();
        $defaultSidebarBlocks = SidebarBlock::whereIn('alias', ['myprograms','links'] )->get()->all();

        $bottomPageBlocks = [];
        $leftPageBlocks = [];

        foreach ( $pageBlocks as $block ) {
            if ( $block->showInSidebar ) {
                $leftPageBlocks[] = $block;
            } else {
                if ( $block->alias === 'imageuploader-downloads' ) {
                    $iu_latest_link = file_get_contents(public_path().'/downloads/image-uploader-latest.txt');
                    $matches = null;
                    if ( preg_match('/image-uploader-(.+)-build-(\d+)/i',$iu_latest_link, $matches) ) {
                        // var_dump($matches);
                        $version = $matches[1];
                        $build = $matches[2];
                        $block->content = str_replace(array('{version}','{build}'),array($version, $build),$block->content);
                    }
                }
                $bottomPageBlocks[] = $block;
            }
        }

        $leftPageBlocks = array_merge( $sidebarBlocks, $leftPageBlocks, $defaultSidebarBlocks );

        $lang = substr(\App::getLocale(),0,2);
        if (!$lang) {
            $lang = 'en';
        }

        if ($page->tabId) {
            $menuItems = MenuItem::where('tab_id', '=', $page->tabId)
                ->where('status', '=', 1)
                ->where('title_'.$lang,'!=','')
                ->orderBy('order_number')
                ->get()->all();
        } else {
            $menuItems = [];
        }

        $comments = $page->comments()->orderBy('createdAt', 'desc')->paginate(10);

        return view('static_page', [
            'staticPage' => $page,
            'leftPageBlocks' => $leftPageBlocks,
            'bottomPageBlocks' => $bottomPageBlocks,
            'menuItems' => $menuItems,
            'currentPageId' => $page->id,
            'menuTitle' => $page->tab ? $page->tab->title: '',
            'currentTab' => '',
            'comments' => $comments
        ]);
    }
}
