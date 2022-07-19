<?=
'<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL
?>
<rss version="2.0">
    <channel>
        <title>{{$info['title']}}</title>
        <link>{{$info['link']}}</link>
        <description>{{$info['description']}}</description>
        <language>{{$info['language']}}</language>
        <pubDate>{{ now() }}</pubDate>

        @foreach($posts as $post)
            <item>
                <title><![CDATA[{{ $post['title'] }}]]></title>
                <link>{{ $post['link'] }}</link>
                <description><![CDATA[{!! $post['description'] !!}]]></description>
                <category>{{ $post['category'] }}</category>
                <author>Sergey Svistunov</author>
                <guid>{{ $post['guid'] }}</guid>
                <pubDate>{{ $post['pubdate'] }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>
