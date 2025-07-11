<?php

namespace App\Http\Controllers;

use App\Helpers\LocaleHelper;
use App\Models\Comment;
use App\Models\MenuItem;
use App\Models\PackageDownload;
use App\Models\Page;
use App\Models\SidebarBlock;
use Illuminate\Http\Request;

class StaticPageController extends SiteController
{
    public function index(Request $request)
    {
        $uri =  $request->route()?->parameter('any') ?? '';

        $page = Page::where('alias', '=', $uri)->first();

        if (!$page) {
            abort(404);
        }

        if ($page->alias === 'imageuploader_downloads' || $page->alias === 'imageuploader') {
            $userAgent = $request->userAgent() ?? '';
            $iuLatestRelease = PackageDownload::getCompatibleBuild('Image Uploader (GUI)', $userAgent);
            $iuLatestLink = $iuLatestRelease['download_url']??'';
            $build = $iuLatestRelease['build_number']??'';
            $version = $iuLatestRelease['version_clean']??'';
            $supportedOs = $iuLatestRelease['supported_os']??'';
            $target_os = $iuLatestRelease['os']??'';

            $downloadButtonText = $iuLatestRelease? __("messages.download_button_for", ['os' => $target_os]): __("messages.download_button");
            $versionString = $iuLatestRelease ? __('messages.app_version',
                [
                    'version' => $version,
                    'build' => $build,
                    'supported_os' => $supportedOs
                ]): '';

            $downloadButtonTitle = $iuLatestRelease ? __('messages.download_button_title',
                [
                    'product_name' => 'Image Uploader',
                    'version' => $version,
                    'build' => $build,
                    'supported_os' => $supportedOs,
                    'package_type' => 'Installer'
                ]): '';

            $replace = array('{version}',
                '{build}',
                '/downloads/image-uploader-latest',
                '{supported_os}',
                '{target_os}',
                '{download_button_text}',
                '{download_button_title}',
                '{version_string}'
            );
            if (!$iuLatestRelease) {
                $iuLatestLink = LocaleHelper::getUrlPrefix() . '/imageuploader_downloads';
            }

            $page->text_ru = str_replace(
                    $replace,
                    array(
                        $version,
                        $build,
                        $iuLatestLink,
                        $supportedOs,
                        $target_os,
                        $downloadButtonText,
                        $downloadButtonTitle,
                        $versionString
                    ), $page->text_ru
                );
                $page->text_en = str_replace($replace,
                    array($version,
                        $build,
                        $iuLatestLink,
                        $supportedOs,
                        $target_os,
                        $downloadButtonText,
                        $downloadButtonTitle,
                        $versionString), $page->text_en);
           // }

            /*$iu_latest_link = file_get_contents(public_path() . '/downloads/image-uploader-latest-beta.txt');
            $matches = null;
            if (preg_match('/image-uploader-(.+)-build-(\d+)/i', $iu_latest_link, $matches)) {
                // var_dump($matches);
                $version = $matches[1];
                $build = $matches[2];
                $page->text_ru = str_replace(
                    array('{beta_version}','{beta_build}'),
                    array($version, $build),
                    $page->text_ru
                );
                $page->text_en = str_replace(array('{version}','{build}'), array($version, $build), $page->text_en);
            }*/
        }

        if ($request->isMethod('POST') && $page->showComments) {
            if ($request->post('name') != '' || $request->post('checkB') !== 'checkA') {
                return abort(400);
            }
            $validated = $request->validate([
                'eman' => 'required|max:255',
//                'email' => 'email',
                'text' => 'required|max:2000'
            ]);

            $validated['nickname'] = $validated['eman'];

            $comment = new Comment($validated);
            $comment->pageId = $page->id;
            $comment->save();
        }
        return view('static_page', $this->getCommonData($request, $page));
    }

    protected function getCommonData(Request $request, Page $page): array {
        $menuItems = $this->getMenuItems($page);

        $comments = $page->showComments ? $page->comments()->orderBy('createdAt', 'desc')->paginate(10) : [];
        [$leftPageBlocks, $bottomPageBlocks] = $this->getBlocks($page);
        $pageNumber = (int)$request->get('page');
        return [
            'staticPage' => $page,
            'showPage' => $pageNumber == 0,
            'leftPageBlocks' => $leftPageBlocks,
            'bottomPageBlocks' => $bottomPageBlocks,
            'menuItems' => $menuItems,
            'currentPageId' => $page->id,
            'menuTitle' => $page->tab ? $page->tab->title : '',
            'currentTab' => '',
            'comments' => $comments,
            'title' => $pageNumber ? __(
                "messages.comments_title",
                [
                    'pagename' => $page->title, 'page' => $pageNumber
                ]
            ) : $page->title,
            'metaKeywords' =>  $pageNumber == 0 ? $page->meta_keywords: '',
            'metaDescription' => $pageNumber == 0 ? $page->meta_description: '',
            'openGraphImage' => $page->open_graph_image
        ];
    }

    protected function getBlocks(Page $page): array {
        $pageBlocks = $page->blocks()->orderBy('orderNumber')->get();
        $sidebarBlocks = $page->sidebarBlocks->all();
        $defaultSidebarBlocks = SidebarBlock::whereIn('alias', ['myprograms','links'])->get()->all();

        $bottomPageBlocks = [];
        $leftPageBlocks = [];

        foreach ($pageBlocks as $block) {
            if ($block->showInSidebar) {
                $leftPageBlocks[] = $block;
            } else {
                if ($block->alias === 'imageuploader-downloads') {
                    $iu_latest_link = file_get_contents(public_path() . '/downloads/image-uploader-latest.txt');
                    $matches = null;
                    if (preg_match('/image-uploader-(.+)-build-(\d+)/i', $iu_latest_link, $matches)) {
                        // var_dump($matches);
                        $version = $matches[1];
                        $build = $matches[2];
                        $block->content = str_replace(
                            array('{version}','{build}'),
                            array($version, $build),
                            $block->content
                        );
                    }
                }
                $bottomPageBlocks[] = $block;
            }
        }

        $leftPageBlocks = array_merge($sidebarBlocks, $leftPageBlocks, $defaultSidebarBlocks);
        return [$leftPageBlocks,$bottomPageBlocks];
    }

    protected function getMenuItems(Page $page): array {
        if ($page->tabId) {
            $lang = LocaleHelper::getCurrentLanguage();
            $menuItems = MenuItem::with('targetPage')
                ->where('tab_id', '=', $page->tabId)
                ->where('status', '=', 1)
                ->where('title_' . $lang, '!=', '')
                ->orderBy('order_number')
                ->get()->all();
        } else {
            $menuItems = [];
        }
        return $menuItems;
    }
}
