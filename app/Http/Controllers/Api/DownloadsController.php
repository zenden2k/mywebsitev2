<?php

namespace App\Http\Controllers\Api;

use App\Models\Download;
use Illuminate\Http\Request;

class DownloadsController extends BaseController
{
    public function options()
    {
        return $this->sendResponse([
        ], 'OK');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = \DB::table('downloads')
            ->selectRaw('downloads.id, url, COUNT(*) as cnt')
            ->leftJoin('downloads_stats', 'downloads.id', '=', 'download_id')
            ->groupBy('downloads.id')
            ->orderBy('downloads.id');

        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $query->where('url', "like", "%$searchQuery%");
        }
        return $this->sendResponse($query->paginate(config('app.pagesize', 20)), 'success');
    }

    public function show(Download $download)
    {
        $days = [];
        for ($i = 30; $i >= 0; $i--) {
            $days[] = date("Y-m-d", strtotime('-' . $i . ' days'));
        }
        $res = \DB::select(
            'SELECT date(downloaded_at) as d, count(*) as cnt FROM downloads_stats
             WHERE `download_id`=:id and `downloaded_at` >= NOW() - INTERVAL 30 DAY group by d ',
            [
                 ':id' => $download->id
            ]
        );
        $res = \Arr::pluck($res, 'cnt', 'd');
        $stats = [];
        foreach ($days as $day) {
            $stats[$day] = isset($res[$day]) ? (int)$res[$day] : 0;
        }

        $data = [
            'datasets' => [
                [
                    'label' => 'Downloads',
                    'data' => array_values($stats),
                ]
            ],
            'labels' => array_keys($stats)
        ];
        return $this->sendResponse($data, 'success');
    }
}
