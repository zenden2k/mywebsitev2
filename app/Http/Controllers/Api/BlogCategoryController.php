<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BlogCategoryRequest;
use App\Models\BlogCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;


class BlogCategoryController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
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
        $model = BlogCategory::orderBy('id');
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
     * @param BlogCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BlogCategoryRequest $request)
    {
        $blogCategory = new BlogCategory($request->validated());
        $blogCategory->save();
        return $this->sendResponse([
            'item' => $blogCategory,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param int $menuitemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BlogCategory $blogcategory)
    {
        return $this->sendResponse($blogcategory, '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlogCategoryRequest $request
     * @param BlogCategory $blogcategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BlogCategoryRequest $request, BlogCategory $blogcategory)
    {
        $vals = $request->validated();
        $blogcategory->update($vals);
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuItem $menuitem
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BlogCategory $blogcategory)
    {
        $blogcategory->delete();
        return $this->sendResponse(true, 'success');
    }
}
