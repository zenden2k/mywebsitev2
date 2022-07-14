@extends('layouts.main')

@section('content')
    <h1>Authentication</h1>
    <div>
        <p>Please paste the following code in Image Uploader:</p>
        <p><input type="text" value="{{ $code }}" size="60" id="code_edit" />
            <button id="copy_to_clipboard_btn" class="copy_btn" data-clipboard-target="#code_edit">Copy to clipboard</button></p>
    </div>
    <script>
        var codeFromHash = {{$codeFromHash}};
    </script>
@endsection

@section("js")
    <script src="/js/clipboard.min.js"></script>
    <script src="/js/callback.js?v=2"></script>
@endsection
