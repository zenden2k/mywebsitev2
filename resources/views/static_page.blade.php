@extends('layouts.main')

@section('content')
    @if($showPage)
        <article>
            {!! $staticPage->text !!}
        </article>

        @foreach($bottomPageBlocks as $staticPageBlock)
        <div class="page_block_header">
            <span>{{ $staticPageBlock->title }}</span>
            <div class="page_block_header_shadow"></div>
        </div>
        @if(!empty($staticPageBlock->alias))
        <a name="{{ $staticPageBlock->alias }}"></a>
        @endif
        {!! $staticPageBlock->content !!}
        @endforeach
    @else
        <a href="{{$__prefix}}/{{$staticPage->alias}}">{{__('<<< Back to the page')}}</a>
    @endif
        @if($staticPage->showComments)
            @include('subviews.comments')
        @endif

@endsection
