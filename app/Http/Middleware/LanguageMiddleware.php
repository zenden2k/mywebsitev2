<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lang =
            $request->route('lang');
           // $request->get('lang');
        if ($lang !== 'ru') {
            $lang = 'en';
        }

        $supportedLanguages = [
            'en',
            'ru'
        ];

        $detectedLang = $request->getPreferredLanguage($supportedLanguages);
        $cookieLang = $request->cookie('lang') ?: null;

        $hostName = $request->getHost();

        $referer = $request->server('HTTP_REFERER');

        if ( $detectedLang != $lang && $detectedLang === 'ru'
            && strpos($referer, $hostName . '/') === false &&
            (!$cookieLang || $cookieLang != $lang)
        ) {
            //return redirect($this->generateCurrentPageUrlForLang($request, $detectedLang));
        }

        \App::setLocale($detectedLang);
        $data = [
            '__lang' => $detectedLang,
            '__canonical_url' =>  'https://' . $hostName .'/'.$request->path(),
            '__domain_name' => $hostName,
            '__ru_link' => $this->generateCurrentPageUrlForLang($request, 'ru'),
            '__en_link' => $this->generateCurrentPageUrlForLang($request, 'en')
        ];
        \View::share($data);

        return $next($request);
    }


    private function generateCurrentPageUrlForLang(Request $request, string $lang): string {
        /*$route = app()->router->getCurrentRoute();
        if (!$route) {
            return '/ru/'.$request->path();
        }*/
        $route = app()->router->getCurrentRoute();
        if($route) {
            return app('url')->toRoute(
                $route,
                array_merge($route->parameters(), ['prefix' => $lang]),
                true
            );
        }

        // TODO:
        return '/'.$lang.'/'.$request->url();
    }
}
