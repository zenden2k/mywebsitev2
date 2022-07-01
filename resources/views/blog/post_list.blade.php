@extends('layouts.blog')

@section('content')

    <div>
        @forelse($posts as $post)
        <div class="b-item" @if($loop->index !=0 && $loop->index !=1) style="margin-top: 30px" @endif>
            <h1><a href="{{$post->getUrl(false)}}">{{$post->title}}</a></h1>
            {!! $post->foreword !!}

            <div class="b-item-footer">
                @if($post->content)
                    <div class="b-item-footer-read-more"><a href="{{$post->getUrl(false)}}">{{__('Read more')}}...</a>
                    </div>
                @endif
                <span>{{__('Published')}} {{$post->created_at}}</span>
            </div>
        </div>

        @empty
            <h2>{{__('No posts found')}}</h2>
            @endforelse

            <p>
                {{$posts->links('vendor.pagination.default')}}
            </p>
            </div>



    </div>


@endsection
