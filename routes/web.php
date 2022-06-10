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

Route::get('/', function () {
    //die(phpinfo());
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin/{any?}', function () {
    return view('admin/index');
})/*->namespace('Admin')*/->middleware(/*'can:admin'*/['auth', 'isAdmin'])
->where('any', '.*')->name('admin.dashboard');

/*Route::group(['namespace' => 'Api', 'prefix' => 'api'], function() {
    Route::resource('api_page', 'PageController');
});*/
