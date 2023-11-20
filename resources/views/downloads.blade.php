@extends('layouts.main')

@section('content')
    @if($showPage)
    <article>
        <h1>{{$title}}</h1>
        <ul class="builds-hamburger">
            @forelse($builds as $build)

                        @foreach($build['subproducts'] as  $productName => $subproducts)
                        <li class="builds-hamburger__item builds-hamburger__item_open">

                                <span href="#" class="builds-hamburger__item-caption">{{$productName}}    <span class="builds-hamburger__build-title">{{{__('messages.build')}}} {{$build['build_number']}} <span title="{{$build['datetime']}}">({{$build['date']}}) <span class="">{{$build['time_ago']}}</span></span></span></span>
                                <ul class="builds-hamburger__item-container builds-hamburger__item-container_open">
                                    @foreach($subproducts as $osName => $product)
                                    <li class="builds-hamburger__item builds-hamburger__item_inline builds-hamburger__item_open">
                                        <span href="#" class="builds-hamburger__item-caption"><i class="fa-brands fa-{{strtolower($osName)}}"></i> {{$osName}}</span>
                                        <ul class="builds-hamburger__item-container builds-hamburger__item-container_open">
                                            @foreach($product as $archName => $files)
                                            <li class="builds-hamburger__archs-list">
                                                <span class="builds-hamburger__archs-title">{{$archName}}:</span>
                                                <ul class="builds-hamburger__files-list">
                                                    @foreach($files as $file)
                                                        <li class="builds-hamburger__files-list-item"><a href="{{$file['download_url']}}" data-hash="{{$file['sha256']}} *{{$file['filename']}}" data-hash-file="{{{$file['hash_file_url']}}}">{{$file['name']}} for {{$osName}} ({{!empty($file['arch_alt']) ? $file['arch_alt'] :$archName }})</a> <span class="builds-hamburger__file-size">{{$file['size_readable']}}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>

                                            @endforeach
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                        </li>
                        @endforeach

            @empty
                <h2>{{__('No releases found')}}</h2>
            @endforelse
        </ul>
    </article>
    @foreach($bottomPageBlocks as $staticPageBlock)
        <div class="page_block_header">
            <span>{{ $staticPageBlock->title }}</span>
            <div class="page_block_header_shadow"></div>
        </div>
        @if(!empty($staticPageBlock->alias))
            <div id="{{ $staticPageBlock->alias }}"></div>
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
