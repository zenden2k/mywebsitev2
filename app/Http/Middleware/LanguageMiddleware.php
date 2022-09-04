<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $path = $request->path();

        // Redirect to an URI without last slash
        if (preg_match('/.+\/$/', $request->getRequestUri())) {
            return \Redirect::to(rtrim($request->getRequestUri(), '/'), 301);
        }

        $route = $request->route();
        if (!$route) {
            return $next($request);
        }
        $lang = $request->route('lang');

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

        \App::setLocale($lang);

        if ($lang !== 'en') {
            $prefix = '/'.$lang;
        } else {
            $prefix = '';
        }
        $data = [
            '__prefix' => $prefix,
            '__lang' => $lang,
            '__canonical_url' =>  'https://' . $hostName .'/'.$request->path(),
            '__domain_name' => $hostName,
            '__ru_link' => $this->generateCurrentPageUrlForLang($request, 'ru'),
            '__en_link' => $this->generateCurrentPageUrlForLang($request, 'en')
        ];
        \View::share($data);

        /** @var Response $res */
        $res = $next($request);

        if ( $res->getStatusCode() != 404 && $detectedLang != $lang && $detectedLang === 'ru'
            && strpos($referer, $hostName . '/') === false &&
            (!$cookieLang || $cookieLang != $lang)
        ) {
            return redirect($this->generateCurrentPageUrlForLang($request, $detectedLang));
        }
        return $res;
    }


    private function generateCurrentPageUrlForLang(Request $request, string $lang): string {
        $path = $request->getRequestUri();
        if ($path[0] !== '/') {
            $path = '/'.$path;
        }
        $languages = ['ru', 'en'];
        foreach ($languages as $item) {
            $path = preg_replace('|^/'.$item.'/|', '/', $path);
            $path = preg_replace('|^/'.$item.'$|', '/', $path);
        }

        if ($lang !== 'en') {
            if ($path === '/'){
                $path = '/'.$lang;
            } else {
                $path = '/'.$lang.$path;
            }
        }
        return $path;
    }
}
