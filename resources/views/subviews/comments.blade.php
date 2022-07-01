<div class="page_block_header">
    <span>{{__('Comments')}}</span>
    <div class="page_block_header_shadow"></div>
</div>

<div class="commentForm" id="commentForm">
    {{__('Write your comment')}}
    <form action="#commentForm" method="POST">
        @csrf
{{--        <input type="hidden" value="{%$csrf_token%}" name="token"/>--}}
        <table width="100%">
            <tr class="saveMe"><td>{{__('Name')}}:</td><td style="padding-left: 20px"><input type="text" name="name" value="" size="40"/></td></tr>
            <tr><td>{{__('Name')}}*:</td><td style="padding-left: 20px"><input type="text" name="eman" value="{{old('eman')}}" size="40"/></td></tr>
            <tr><td>E-mail:</td><td style="padding-left: 20px"><input type="text" name="email" value="{{old('email')}}" size="40" /></td></tr>
            <tr><td></td><td style="padding-left: 20px">({{__('your e-mail will not be published')}})</td></tr>
            <tr><td colspan="2"><input type="hidden" name="checkB" id="checkB" value="become" />
                    <textarea name="text" rows="8"  style="width: 100%">{{old('text')}}</textarea></td></tr>
            <tr><td colspan="2"><input type="submit" value="{{__('Send')}}" /></td> </tr>
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
                <b>zenden2k {{__('answered')}}:</b><br/>
                {!! nl2br(e($item->answer)) !!}
            </div>@endif
        </div>
    </div>
        @empty
            <div style="font-weight: bold; margin-bottom: 15px;margin-top: 20px;">{{__('There are no comments yet.')}}</div>
            @endforelse
    {{ $comments->fragment('comments')->links('vendor.pagination.default') }}

</div>
