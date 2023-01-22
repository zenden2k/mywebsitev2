<?php

namespace App\Http\Controllers;

use App\Helpers\LocaleHelper;
use App\Models\Tab;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

class SiteController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected string $urlPrefix;

    public function callAction($method, $parameters)
    {
        if ($method !== 'rss') {
            $tabs = Tab::where('active', '=', 1)->orderBy('orderNumber')->get();

            $revision = @file_get_contents(base_path() . '/revision');
            if ($revision === false) {
                $revision = 1;
            } else {
                $revision = trim($revision);
            }

            $lang = LocaleHelper::getCurrentLanguage();

            $this->urlPrefix = $lang === 'en' ? '' : '/ru';

            View::share([
                '__lang' => $lang,
                'metaDescription' => '',
                'metaKeywords' => '',
                'openGraphImage' => '',
                'title' => '',
                'url_prefix' => $this->urlPrefix,
                '__domain_name' => request()?->getHost(),
                '__tabs' => $tabs,
                '__revision' => $revision,
               /* '__canonical_url' => '',
                '__en_link' => '',
                '__ru_link' => '',
                '__prefix' => $this->urlPrefix*/
            ]);
        }
        return parent::callAction($method, $parameters);
    }
}
