@extends('layouts.main')

@section('sidebar')
    <div class="sidebar_block">
        <div class="sidebar_block_header">
            <span></span><div>{{__('Blog Categories')}}</div>
        </div>
        <div class="sidebar_block_content">
            <p>
                @foreach($__blog_categories as $category)
                <a class="b-menu-link @if(isset($current_category_alias) && $current_category_alias == $category->alias) active @endif" href="{{$url_prefix}}/blog/category/{{$category->alias}}/">{{$category->title}} @if(isset($__categories_post_count[$category->id])) ({{$__categories_post_count[$category->id]}}) @endif </a><br/>
                @endforeach
            </p>
        </div>
    </div>

    <div class="sidebar_block">
        <div class="sidebar_block_header">
            <span></span><div>{{__('Archive')}}</div>
        </div>
        <div class="sidebar_block_content">
            <p>
                @foreach($archives as $archive)
                <a class="b-menu-link @if(isset($selected_month) && $selected_month == $archive['month']) active @endif" href="{{$archive['url']}}">{{$archive['title']}} ({{$archive['count']}})</a><br/>
                @endforeach
            </p>
        </div>
    </div>
@endsection

