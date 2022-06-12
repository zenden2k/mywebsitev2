<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ArrayHelper;
use App\Http\Requests\EditCommentRequest;
use App\Http\Requests\EditMenuItemRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Tab;
use Illuminate\Http\Request;


class MenuItemController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $pages = Page::orderBy('title_ru')->get();
        $tabs = Tab::orderBy('title_ru')->get();
        return $this->sendResponse([
            'pages' => $pages,
            'tabs' => $tabs
        ], 'OK');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $model = MenuItem::with(['targetPage'])->orderBy('id');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $model->where('title_ru', "like", "%$searchQuery%");
        }
        /*$pageId = (int)$request->get('pageId');
        if ($pageId) {
            $model->where('pageId', '=', $pageId);
        }*/

        return $this->sendResponse($model->paginate(config('app.pagesize', 20)), 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditMenuItemRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditMenuItemRequest $request)
    {
        $page = new MenuItem($request->validated());
        $page->save();
        return $this->sendResponse([
            'item' => $page,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param int $menuitemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($menuitemId)
    {
        $page = MenuItem::with(['targetPage'])->findOrFail($menuitemId);
        return $this->sendResponse($page, '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditMenuItemRequest $request
     * @param MenuItem $menuitem
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditMenuItemRequest $request, MenuItem $menuitem)
    {
        $vals = $request->validated();
        $menuitem->update($vals);
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuItem $menuitem
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(MenuItem $menuitem)
    {
        $menuitem->delete();
        return $this->sendResponse(true, 'success');
    }
}
