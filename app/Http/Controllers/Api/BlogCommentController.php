<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ArrayHelper;
use App\Http\Requests\EditBlogCommentRequest;
use App\Http\Resources\BlogCommentResource;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogCommentController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $posts = BlogPost::orderBy('title_ru')->get();
        return $this->sendResponse([
            'posts' => $posts,
        ], 'OK');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $model = BlogComment::with(['post'])->orderBy('id', 'desc');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $model->where('text', "like", "%$searchQuery%");
            $model->orWhere('name', "like", "%$searchQuery%");
        }
        $postId = (int)$request->get('post_id');
        if ($postId) {
            $model->where('blog_post_id', '=', $postId);
        }

        $data = ArrayHelper::prepareCollectionPagination(BlogCommentResource::collection($model->paginate(20)));

        return $this->sendResponse($data, 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditBlogCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditBlogCommentRequest $request)
    {
        $blogComment = new BlogComment($request->validated());
        $blogComment->ip = ip2long($request->ip());
        $blogComment->save();
        return $this->sendResponse([
            'item' => $blogComment,
        ], 'OK');
    }

    /**
     * Display the specified resource.
     *
     * @param int $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($commentId)
    {
        $page = BlogComment::with(['post'])->findOrFail($commentId);
        return $this->sendResponse($page, '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditBlogCommentRequest $request
     * @param BlogComment $blogcomment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditBlogCommentRequest $request, BlogComment $blogcomment)
    {
        $blogcomment->update($request->validated());
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BlogComment $blogcomment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BlogComment $blogcomment)
    {
        $blogcomment->delete();
        return $this->sendResponse(true, 'success');
    }
}
