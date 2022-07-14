<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallbackController extends SiteController
{
    public function index(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');
        if ((!is_string($code) || $code === '') && $state !== 'token') {
            abort(404);
        }

        $bottomPageBlocks = [];
        $leftPageBlocks = [];

        return view('callback', [
            'leftPageBlocks' => $leftPageBlocks,
            'bottomPageBlocks' => $bottomPageBlocks,
            'currentTab' => 'imageuploader',
            'code' => $code,
            'codeFromHash' => $state === 'token' ? 'true' : 'false',
            'state' => $state
        ]);
    }
}
