<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => ['auth:sanctum'/*, 'isAdmin'*/], 'namespace' => 'App\Http\Controllers\Api'/*, 'prefix' => 'api'*/], function() {
    Route::get('page/options', 'PageController@options');
    Route::apiResource('page', 'PageController');
    Route::get('comment/options', 'CommentController@options');
    Route::apiResource('comment', 'CommentController');
    Route::get('tab/options', 'TabController@options');
    Route::apiResource('tab', 'TabController');
    Route::get('sidebarblock/options', 'SidebarBlockController@options');
    Route::apiResource('sidebarblock', 'SidebarBlockController');
    Route::get('menuitem/options', 'MenuItemController@options');
    Route::apiResource('menuitem', 'MenuItemController');
    Route::apiResource('blogcategory', 'BlogCategoryController');
    Route::get('blogpost/options', 'BlogPostController@options');
    Route::apiResource('blogpost', 'BlogPostController');
    Route::get('blogcomment/options', 'BlogCommentController@options');
    Route::apiResource('blogcomment', 'BlogCommentController');
    Route::get('download/options', 'DownloadsController@options');
    Route::apiResource('download', 'DownloadsController');
    Route::post('upload-image', [\App\Http\Controllers\ParserController::class, 'uploadImage']);
});

//Route::post('register', 'App\Http\Controllers\Api\RegisterController@register');
Route::post('login', 'App\Http\Controllers\Api\RegisterController@login');
Route::post('logout', 'App\Http\Controllers\Api\RegisterController@logout')->middleware('auth:sanctum');
