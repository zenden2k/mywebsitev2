<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ArrayHelper;
use App\Http\Requests\EditBlogPostRequest;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;


class BlogPostController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $categories = BlogCategory::orderBy('title_ru')->get();
        return $this->sendResponse([
            'categories' => $categories,
        ], 'OK');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $model = BlogPost::withCount(['comments'])->with(['category'])->orderBy('id');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $model->where('title_ru', "like", "%$searchQuery%");
        }
        $pageId = (int)$request->get('pageId');
        if ($pageId) {
            $model->where('pageId', '=', $pageId);
        }

        $data = ArrayHelper::prepareCollectionPagination(BlogPostResource::collection($model->paginate(20)));

        return $this->sendResponse($data, 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditBlogPostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditBlogPostRequest $request)
    {
        $blogPost = new BlogPost($request->validated());
        $blogPost->save();
        return $this->sendResponse([
            'item' => $blogPost,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($postId)
    {
        $blogPost = BlogPost::findOrFail($postId);
        return $this->sendResponse($blogPost, '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditBlogPostRequest $request
     * @param BlogPost $blogpost
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditBlogPostRequest $request, BlogPost $blogpost)
    {
        $blogpost->update($request->validated());
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BlogPost $blogpost
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BlogPost $blogpost)
    {
        $blogpost->delete();
        return $this->sendResponse(true, 'success');
    }
}
