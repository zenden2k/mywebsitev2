<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class DownloadController extends Controller
{
    public function index(string $filename)
    {
        $file_path = public_path() .'/downloads/' . $filename.'.txt';
        if ( !file_exists($file_path) ) {
            abort(404);
        }

        $url = file_get_contents($file_path);

        \DB::table('downloads')->insertOrIgnore([
            ['url' => $url, 'local_file_name' => $filename],
        ]);
        $downloadId = \DB::getPdo()->lastInsertId();
        if (!$downloadId) {
            $row = \DB::table('downloads')->where('url','=', $url)->first();

            $downloadId = $row->id;
        }

        $arr = [
            'ip' => sprintf('%u',ip2long(Request::ip())),
            'downloaded_at' => date('Y-m-d H:i:s'),
            'download_id' => $downloadId
        ];
        \DB::table('downloads_stats')->insert($arr);

        return \Redirect::to($url);
    }
}
