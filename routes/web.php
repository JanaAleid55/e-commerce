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

Auth::routes();
Route::group(['prefix' => 'merchant','middleware' => ['merchant','auth']], function ()
 {
    Route::post('/store', 'StoreContoller@createStroe')->name('createStore');
    Route::post('/products', 'ProductController@addProduct')->name('addProduct');
});
Route::get('/products/{id}', 'ProductController@index');
Route::post('/cart', 'ProductController@addToCart');
Route::get('/home', 'HomeController@index')->name('home');


