@extends('layouts.main')

@section('content')
    @if($showPage)
    <article>
        <h1>{{$title}}</h1>
        <ul class="builds-hamburger">
            @forelse($builds as $build)
                <li class="builds-hamburger__item">
                    <span class="builds-hamburger__item-caption builds-hamburger__item-caption_first-level">{{{__('messages.build')}}} {{$build['build_number']}} <span title="{{$build['datetime']}}">({{$build['date']}}) <span class="builds-hamburger__time-ago-label">{{$build['time_ago']}}</span></span> @if ($loop->first)<span class="builds-hamburger__new-label">*{{__('LATEST BUILD')}}*</span>  @endif</span>
                    <ul class="builds-hamburger__item-container">
                        @foreach($build['subproducts'] as $osName => $subproducts)
                            <li  class="builds-hamburger__item">
                                <span href="#" class="builds-hamburger__item-caption">{{$osName}}</span>
                                <ul class="builds-hamburger__item-container">
                                    @foreach($subproducts as $productName => $product)
                                    <li class="builds-hamburger__item">
                                        <span href="#" class="builds-hamburger__item-caption">{{$productName}}</span>
                                        <ul class="builds-hamburger__item-container">
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
{{--                                            <li>--}}
{{--                                                Git commit: <a href="{{$build['commit_url']}}" target="_blank">{{$build['commit_hash_short']}}</a>--}}
{{--                                            </li>--}}
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                        @if (!empty($build['commits']))
                        <p>
                        Commits:
                            <ul>
                                @foreach($build['commits'] as $commit)
                                    <li>
                                        <span title="{{ $commit['datetime']}}">[{{ $commit['date']}}]</span> {{ $commit['commit_message']}} <a href="{{ $commit['commit_url']}}" target="_blank">{{ $commit['commit_hash_short']}}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </p>
                            @endif
                    </ul>
                </li>
            @empty
                <h2>{{__('No builds found')}}</h2>
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
