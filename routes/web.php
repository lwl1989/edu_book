<?php
//普通路由
Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'Actions\AdminController@index');
Route::get('/home', 'Actions\AdminController@index')->name('home');


//不需要驗證登錄的接口路由
Route::group(['middleware' => ['format'] ], function (){
    Route::post('login','Auth\LoginController@login');
});
//'auth',
Route::group(['prefix'=>'book','middleware'=>['format']], function (){

    Route::get('count','Actions\BookController@count');
    Route::get('select','Actions\BookController@select');
    Route::get('get','Actions\BookController@get');
    Route::post('create','Actions\BookController@create');
    Route::post('update','Actions\BookController@update');
    Route::delete('delete','Actions\BookController@delete');
    
});