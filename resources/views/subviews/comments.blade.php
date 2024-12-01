<div class="page_block_header">
    <span>{{__('Comments')}}</span>
    <div class="page_block_header_shadow"></div>
</div>

<div class="comment-form" id="commentForm">
    {{__('Write your comment')}}
    <form action="#commentForm" method="POST">
        @csrf
        <table class="comment-form__table">
            <tr class="saveMe"><td>{{__('Nickname')}}:</td><td style="padding-left: 20px"><input type="text" name="name" value="" /></td></tr>
            <tr><td>{{__('Nickname')}}*:</td><td style="padding-left: 20px"><input type="text" name="eman" value="{{old('eman')}}" style="min-width: 300px" /></td></tr>
{{--            <tr><td>E-mail:</td><td style="padding-left: 20px"><input type="text" name="email" value="{{old('email')}}" style="min-width: 300px" /></td></tr>--}}
{{--            <tr><td></td><td style="padding-left: 20px">({{__('your e-mail will not be published')}})</td></tr>--}}
            <tr><td colspan="2"><input type="hidden" name="checkB" id="checkB" value="become" />
                    <textarea name="text" rows="8"  style="width: 100%">{{old('text')}}</textarea></td></tr>
            <tr><td colspan="2"><input class="comment-form__submit" type="submit" value="{{__('Send')}}" /></td> </tr>
        </table>
        @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </form>
</div>
<script type="text/javascript">
    document.getElementById('checkB').value = 'checkA';
</script>

<div class="div_comments" id="comments">

    @forelse($comments as $item)
    <div class="virtualpage hidepiece item" style="margin-bottom:4px;" id="comment_{{$item->id}}">
        <div class="div_header"><span class="msglabel">{{$item->name}}</span> ({{$item->createdAt }})</div>
        <div class="div_bottom">{!! nl2br(e($item->text))!!}
            @if($item->answer)<div class="answer">
                <b>Sergey Svistunov {{__('answered')}}:</b><br/>
                {!! nl2br($item->answer) !!}
            </div>@endif
        </div>
    </div>
        @empty
            <div style="font-weight: bold; margin-bottom: 15px;margin-top: 20px;">{{__('There are no comments yet.')}}</div>
            @endforelse
    {{ $comments->fragment('comments')->links('vendor.pagination.default') }}

</div>
