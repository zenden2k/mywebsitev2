<!doctype html>
<html prefix="og: https://ogp.me/ns#" lang="{{ $__lang }}">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if($metaDescription)
        <meta name="Description" content="{{ $metaDescription }}" />
    @endif
    @if($metaKeywords)
        <meta name="Keywords" content="{{ $metaKeywords }}" />
    @endif
    <meta property="og:title" content="{{$title?$title.' - ':''}}{{__('messages.site_title')}}" />
    @if($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}" />
    @endif
    <meta property="og:url" content="{{ $__canonical_url }}" />
    @if($openGraphImage)
    <meta property="og:image" content="{{ $openGraphImage }}" />
    @endif
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}?v={{$__revision}}" />
    <link id="favicon" rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <link href="{{asset('js/prettify/prettify.css')}}" rel="stylesheet" type="text/css" />
    <link rel="alternate" href="https://{{ $__domain_name }}{{$__en_link}}" hreflang="en" />
    <link rel="alternate" href="https://{{ $__domain_name }}{{$__ru_link}}" hreflang="ru" />
    <title>@if($title){{ $title }} - {{__('messages.site_title')}} @else {{__('messages.site_title')}} @endif</title>
    @yield('head')
</head>
<body onload="prettyPrint()">

<header id="header">
    <div class="wrapper2">
        <a href="{{ $__prefix }}/" id="logo" class="{{$__lang}}"></a>
        <div class="languages"><a href="{{ $__en_link }}" id="lang_en" rel="nofollow">English</a> <a href="{{ $__ru_link }}" id="lang_ru" rel="nofollow">Русский</a></div>
        <div class="clearfix"></div>
    </div>
</header>
<div class="page-wrapper" id="page_wrapper">
    <button class="open-panel" title="{{__('messages.show_menu')}}"></button>
        <nav id="navbar">
            @foreach($__tabs as $tab)
            <div class="tab @if ((!empty($staticPage) && ( $staticPage->tabId == $tab->id )) || ($currentTab && $currentTab==$tab->alias)) active @endif"  >
                <a href="{{ $__prefix }}{{ $tab->url }}">{{ $tab->title }}</a>
            </div>
            @endforeach
        </nav>
        <div class="navbar_bottom">
            <div></div>
            <p></p>
        </div>
        <div class="navbar_bottom_shadow"></div>
        <div class="row-wrapper">
            <aside id="sidebar">
                @yield('sidebar')
                @if(!empty($menuItems))
                <div class="sidebar_block">
                    <div class="sidebar_block_header"><span></span><div>{{ $menuTitle }}</div></div>
                    <div class="sidebar_block_content">
                        <p>
                            @foreach($menuItems as $menuItem)
                            <a href="@if(!$menuItem->isExternalUrl()){{$__prefix}}/@endif{{$menuItem->url()}}" @if($menuItem->isExternalUrl())target="_blank" @endif class="@if($currentPageId == $menuItem->target_page_id)active @endif" @if($currentPageId == $menuItem->target_page_id)aria-current="page" @endif>{{ $menuItem->title }}</a> <br/>
                            @endforeach
                        </p>
                    </div>
                </div>
                @endif
                @foreach($leftPageBlocks as $pageBlock)
                <div class="sidebar_block">
                    <div class="sidebar_block_header"><span></span><div>{{ $pageBlock->title }}</div></div>
                    <div class="sidebar_block_content">
                        {!! $pageBlock->content !!}
                    </div>
                </div>
                @endforeach
            </aside>
            <main id="content" >
                <div class="abner-under2" ></div>
                @yield('content')

            </main>
        </div>
</div>
    <footer id="footer">{{ __("messages.footer", [ "year" => date('Y') ]) }}</footer>

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/prettify/prettify.js')}}"></script>
    <script src="{{asset('js/galleria/galleria.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}?v={{$__revision}}"></script>


    @yield("js")

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-32288104-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-32288104-1');
    </script>


</body>
</html>
