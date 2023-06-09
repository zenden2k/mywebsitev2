@extends('layouts.main')

@section('content')
    <article>
        <h1>{{$title}}</h1>
        <ul class="builds-hamburger">

            @forelse($builds as $build)


                <li class="builds-hamburger__item">
                    <span class="builds-hamburger__item-caption .builds-hamburger__item-caption_first-level">Build {{$build['build_number']}} ({{$build['date']}}) <span class="builds-hamburger__time-ago-label">{{$build['time_ago']}}</span> @if ($loop->first)<span class="builds-hamburger__new-label">*{{__('LATEST BUILD')}}*</span>  @endif</span>
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
                                                        <li class="builds-hamburger__files-list-item"><a href="{{$file['download_url']}}" data-hash="{{$file['sha256']}} *{{$file['filename']}}" data-hash-file="{{{$file['hash_file_url']}}}">{{$file['name']}} for {{$osName}} ({{!empty($file['arch_alt']) ? $file['arch_alt'] :$archName }})</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>

                                            @endforeach
                                            <li>
                                                Git commit: <a href="{{$build['commit_url']}}" target="_blank">{{$build['commit_hash_short']}}</a>
                                            </li>
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @empty
                <h2>{{__('No builds found')}}</h2>
            @endforelse
        </ul>
    </article>
@endsection
