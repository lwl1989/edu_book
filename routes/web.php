<?php
//普通路由
Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');


//不需要驗證登錄的接口路由
Route::group(['middleware' => ['format'] ], function (){
    Route::post('login','Auth\LoginController@login');
});

Route::group(['prefix'=>'book','middleware'=>['auth','format']], function (){

    Route::get('count','Manager\AdminController@count');
    Route::get('select','Manager\AdminController@select');
    Route::post('create','Manager\AdminController@create');
    Route::put('update','Manager\AdminController@update');
    Route::delete('delete','Manager\AdminController@delete');


    Route::get('routes','Manager\RouterController@getRouter');
    Route::get('routes1','Manager\RouterController@getRouter1');
});