<?php

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

Route::get('/', 'PublicController@welcome')->name('welcome');
Route::get('/about', 'PublicController@about')->name('about');
Route::get('/public', 'PublicController@publicShares')->name('public');

Route::match(['get', 'post'], '/file/{id}', 'FileController@get')->name('file');

Auth::routes();

Route::middleware('auth')->group(function() {
    Route::get('/file/{id}/edit', 'HomeController@edit')->name('file.edit');
    Route::post('/file/{id}/edit', 'HomeController@update')->name('file.update');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/upload', 'HomeController@upload')->name('upload');
});
