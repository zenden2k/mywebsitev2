<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EditPageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\SidebarBlock;
use App\Models\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends BaseController
{
    public function options()
    {
        $tabs = Tab::orderBy('id', 'asc')->get();
        $sidebarBlocks = SidebarBlock::orderBy('id')->get();
        return $this->sendResponse([
            'tabs' => $tabs,
            'sidebarBlocks' => $sidebarBlocks
        ], 'OK');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $model = Page::withCount(['comments'])->orderBy('id');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $model->where('title_ru', "like", "%$searchQuery%");
            $model->orWhere('alias', "like", "%$searchQuery%");
        }

        return $this->sendResponse($model->paginate(config('app.pagesize', 20)), 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditPageRequest $request)
    {
        $page = null;
        DB::transaction(function() use ($request, &$page) {
            $page = new Page($request->validated());
            $page->save();
            $blocks = $request->post('blocks');
            if ($blocks !== null) {
                $page->saveBlocks($blocks);
            }
            $sidebarBlocks = $request->post('sidebarBlocks');
            if ($sidebarBlocks !== null) {
                $blockIds = [];
                foreach ($sidebarBlocks as $block) {
                    $blockIds[] = $block['id'];
                }
                $page->sidebarBlocks()->sync($blockIds);
            }

        });

        return $this->sendResponse([
            'item' => $page,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param int $pageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($pageId)
    {
        $page = Page::with(['blocks', 'sidebarBlocks'])->findOrFail($pageId);
        return $this->sendResponse(new PageResource($page), '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditPageRequest $request, Page $page)
    {
        DB::transaction(function() use ($page, $request) {
            $page->update($request->validated());
            $blocks = $request->post('blocks');
            if ($blocks !== null) {
                $page->saveBlocks($blocks);
            }
            $sidebarBlocks = $request->post('sidebarBlocks');
            if ($sidebarBlocks !== null) {
                $blockIds = [];
                foreach ($sidebarBlocks as $block) {
                    $blockIds[] = $block['id'];
                }
                $page->sidebarBlocks()->sync($blockIds);
            }
        });
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return $this->sendResponse(true, 'success');
    }
}
