<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EditPageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\SidebarBlock;
use App\Models\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
    public function index()
    {
        $pages = PageResource::collection(Page::orderBy('id')->paginate(100));
        //return response()->json($pages);

        return $this->sendResponse(['items' => $pages], 'OK');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EditPageRequest $request)
    {
        $page = new Page($request->validated());
        $page->save();
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
        $page->update($request->validated());
        $blocks = $request->post('blocks');
        if ($blocks) {
            $blockObjects = [];
            foreach ($blocks as $block) {
                unset($block['id']);
                $blockObjects[] = new PageBlock($block);
            }
            $page->blocks()->delete();
            $page->blocks()->saveMany($blockObjects);
        }

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
