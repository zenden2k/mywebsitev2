<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/downloads/{filename}', [\App\Http\Controllers\DownloadController::class, 'index'])->where('filename', '[a-zA-Z0-9\.\-_]+');

Route::get('/vt/{any?}', function () {
    return view('admin/index');
})/*->namespace('Admin')*/->middleware(/*'can:admin'*/['auth', 'isAdmin'])
->where('any', '.*')->name('admin.dashboard');

$optionalLanguageRoutes = function() {
    Route::get('/blog', '\App\Http\Controllers\BlogController@index');
    Route::get('/blog/category/{category}', '\App\Http\Controllers\BlogController@index')->where('category', '[a-zA-Z_\-0-9]+');
    $req = [
        'year' => '\d+',
        'month' => '\d+',
        'day' => '\d+',
        'alias' => '[a-zA-Z_\-0-9]+',
    ];
    Route::get('/blog/{year}/{month}','\App\Http\Controllers\BlogController@index')->where(['year'=> '[0-9]+','month' => '[0-9]{1,2}']);

    Route::get('/blog/{year}/{month}/{day}/{alias}','\App\Http\Controllers\BlogController@show')->where($req);
    Route::post('/blog/{year}/{month}/{day}/{alias}','\App\Http\Controllers\BlogController@show')->where($req);

    Route::get('/{any?}', [\App\Http\Controllers\StaticPageController::class, 'index'])->where('any', '.*');
    Route::post('/{any?}', [\App\Http\Controllers\StaticPageController::class, 'index'])->where('any', '.*');
};

Route::group(
    ['prefix' => '/{lang}/', 'where' => ['lang' => 'ru']],
    $optionalLanguageRoutes
);

$optionalLanguageRoutes();
