@extends('layouts.blog')

@section('content')

    <div style="/*width: 800px*/">
    <div class="b-list">
        <div class="b-item">
            <article>
                <h1>{{$post->title}}</h1>
                {!! $post->foreword !!}
                {!! $post->content !!}
            </article>
            @if($post->alias == 'about')
            <div class="fb-like" data-href="{%$post->get_url()%}" data-send="false" data-width="450" data-show-faces="false" style="margin-top: 8px"></div>
            @else
            <div class="b-item-footer">
                <div class="fb-like" data-href="{%$post->get_url()%}" data-send="false" data-width="450" data-show-faces="false" style="margin-top: 8px"></div>
                <div class="clearfix"></div>
                <div class="b-item-footer-border"></div>
                <p class="b-item-details">{{__('Published')}} {{$post->created_at}}</p>
                <script src="https://yastatic.net/share2/share.js"></script>
                <div class="ya-share2" data-curtain data-shape="round" data-services="vkontakte,facebook,telegram,twitter,viber"></div>
                <div class="clearfix"></div>
            </div>
            @endif
        </div>
        @if($post->enable_comments)
        <div class="clearfix"></div>

        <div class="b-comments-list">
            <div class="fb-comments" data-href="{{$post->getUrl()}}" data-width="575" data-num-posts="10"></div>
        </div>
        @endif
    </div>
    <div class="clearfix"></div>
</div>

<br>
@if($post->enable_comments)
    @include('subviews.comments')
@endif

@endsection
