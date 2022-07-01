<?php


namespace App\Services;


use App\Models\BlogPost;
use App\Models\Page;

class SitemapService
{
    public static function generate()
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xhtml','http://www.w3.org/1999/xhtml');

        $pages = Page::get();

        foreach ($pages as $page) {
            $date = max(array(strtotime($page->createdAt), strtotime($page->modifiedAt)));

            $url = $dom->createElement('url');

            $ruUrl = url('ru/' .  $page->alias);
            $enUrl = url ( $page->alias);
            // Элемент <loc> - URL статьи.
            $loc = $dom->createElement('loc');
            $text = $dom->createTextNode(
                htmlentities($ruUrl, ENT_QUOTES)
            );
            $loc->appendChild($text);
            $url->appendChild($loc);
            $link = $dom->createElement('xhtml:link');
            $link->setAttribute('rel', 'alternate');
            $link->setAttribute('hreflang', 'ru');
            $link->setAttribute('href', $ruUrl);
            $url->appendChild($link);

            $linkEn = $dom->createElement('xhtml:link');
            $linkEn->setAttribute('rel', 'alternate');
            $linkEn->setAttribute('hreflang', 'en');
            $linkEn->setAttribute('href', $enUrl);
            $url->appendChild($linkEn);



            // Элемент <lastmod> - дата последнего изменения статьи.
            $lastmod = $dom->createElement('lastmod');
            $text = $dom->createTextNode(date('Y-m-d', $date));
            $lastmod->appendChild($text);
            $url->appendChild($lastmod);

            // Элемент <priority> - приоритетность (от 0 до 1.0, по умолчанию 0.5).
            // Если дата публикации/изменения статьи была меньше недели назад ставим приоритет 1.
            $priority = $dom->createElement('priority');
            $text = $dom->createTextNode((($date + 604800) > time()) ? '1' : '0.5');
            $priority->appendChild($text);
            $url->appendChild($priority);

            $urlset->appendChild($url);
        }

        $posts_orm = BlogPost::with('category');

        $posts = $posts_orm->where('status','=','1')->orderBy('created_at','DESC')->get();

        foreach ($posts as $post) {
            $date = strtotime($post->created_at);

            $url = $dom->createElement('url');

            $ruUrl = $post->content_ru ? $post->getUrl(true, 'ru'): null;

            $enUrl = $post->content_en ? $post->getUrl(true, 'en'): null;


            if (!$ruUrl && !$enUrl) {
                continue;
            }

            // Элемент <loc> - URL статьи.
            $loc = $dom->createElement('loc');
            $text = $dom->createTextNode(
                htmlentities($ruUrl ? $ruUrl: $enUrl, ENT_QUOTES)
            );
            $loc->appendChild($text);
            $url->appendChild($loc);
            if ($ruUrl) {
                $link = $dom->createElement('xhtml:link');
                $link->setAttribute('rel', 'alternate');
                $link->setAttribute('hreflang', 'ru');
                $link->setAttribute('href', $ruUrl);
                $url->appendChild($link);
            }


            if ($enUrl) {
                $linkEn = $dom->createElement('xhtml:link');
                $linkEn->setAttribute('rel', 'alternate');
                $linkEn->setAttribute('hreflang', 'en');
                $linkEn->setAttribute('href', $enUrl);
                $url->appendChild($linkEn);
            }



            // Элемент <lastmod> - дата последнего изменения статьи.
            $lastmod = $dom->createElement('lastmod');
            $text = $dom->createTextNode(date('Y-m-d', $date));
            $lastmod->appendChild($text);
            $url->appendChild($lastmod);

            // Элемент <priority> - приоритетность (от 0 до 1.0, по умолчанию 0.5).
            // Если дата публикации/изменения статьи была меньше недели назад ставим приоритет 1.
            $priority = $dom->createElement('priority');
            $text = $dom->createTextNode((($date + 604800) > time()) ? '1' : '0.5');
            $priority->appendChild($text);
            $url->appendChild($priority);

            $urlset->appendChild($url);
        }

        $dom->appendChild($urlset);
        return $dom->save(public_path() . '/sitemap.xml');
    }
}
