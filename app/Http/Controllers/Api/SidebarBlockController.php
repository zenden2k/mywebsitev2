<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EditSidebarBlockRequest;
use App\Http\Requests\EditTabRequest;
use App\Models\SidebarBlock;
use App\Models\Tab;
use Illuminate\Http\Request;

class SidebarBlockController extends BaseController
{
    public function options()
    {
        return $this->sendResponse([
        ], 'OK');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $items = SidebarBlock::orderBy('id', 'asc');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $items->where('title_ru', "like", "%$searchQuery%");
        }
        return $items->paginate(config('app.pagesize', 20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditTabRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditSidebarBlockRequest $request)
    {
        $tab = new SidebarBlock($request->validated());
        $tab->save();
        return $this->sendResponse([
            'item' => $tab,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param SidebarBlock $sidebarblock
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SidebarBlock $sidebarblock)
    {
        return $this->sendResponse($sidebarblock, 'success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditSidebarBlockRequest $request
     * @param SidebarBlock $sidebarblock
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditSidebarBlockRequest $request, SidebarBlock $sidebarblock)
    {
        $sidebarblock->update($request->validated());
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Tab $sidebarblock
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SidebarBlock $sidebarblock)
    {
        $sidebarblock->delete();
        return $this->sendResponse(true, 'success');
    }
}
