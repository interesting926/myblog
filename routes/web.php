<?php
use Illuminate\Routing\Route;

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

Route::get('/admin', function () {
    return redirect('/admin/post');
});

Route::middleware('auth')->namespace('Admin')->group(function(){
    Route::resource('admin/post', 'PostController');
    Route::resource('admin/tag', 'TagController');
    Route::get('admin/upload',"UploadController@index");
});

//登陆退出
Route::get('/login','Auth\LoginController@showLoginFrom')->name('login');
Route::post('/login','Auth\LoginController@login');
Route::get('/login','Auth\LoginController@logout')->name('logout');

