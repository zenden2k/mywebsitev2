<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ArrayHelper;
use App\Http\Requests\EditCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Page;
use Illuminate\Http\Request;


class CommentController extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $pages = Page::orderBy('title_ru')->get();
        return $this->sendResponse([
            'pages' => $pages,
        ], 'OK');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $model = Comment::with(['page'])->orderBy('id', 'desc');
        $searchQuery = $request->get('query');
        if (strlen($searchQuery)) {
            $model->where('text', "like", "%$searchQuery%");
            $model->orWhere('name', "like", "%$searchQuery%");
        }
        $pageId = (int)$request->get('pageId');
        if ($pageId) {
            $model->where('pageId', '=', $pageId);
        }

        $data = ArrayHelper::prepareCollectionPagination(CommentResource::collection($model->paginate(20)));

        return $this->sendResponse($data, 'success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EditCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EditCommentRequest $request)
    {
        $page = new Comment($request->validated());
        $page->save();
        return $this->sendResponse([
            'item' => $page,
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
        $page = Comment::with(['page'])->findOrFail($commentId);
        return $this->sendResponse($page, '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditCommentRequest $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EditCommentRequest $request, Comment $comment)
    {
        $vals = $request->validated();
        $comment->update($vals);
        return $this->sendResponse(true, 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->sendResponse(true, 'success');
    }
}
