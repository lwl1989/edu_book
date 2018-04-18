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

Route::group(['prefix'=>'admin','middleware'=>['auth','format']], function (){
    Route::get('count','Manager\AdminController@count');
    Route::get('select','Manager\AdminController@select');
    Route::post('create','Manager\AdminController@create');
    Route::put('update','Manager\AdminController@update');
    Route::delete('delete','Manager\AdminController@delete');

    Route::post('change/password','Manager\AdminController@changePassword');
});
//'auth',
Route::group(['prefix'=>'book','middleware'=>['format']], function (){

    Route::get('count','Actions\BookController@count');
    Route::get('select','Actions\BookController@select');
    Route::get('get','Actions\BookController@get');
    Route::post('create','Actions\BookController@create');
    Route::post('update','Actions\BookController@update');
    Route::delete('delete','Actions\BookController@delete');

    Route::get('plan/count','Actions\BookController@countPlan');
    Route::get('plan/select','Actions\BookController@selectPlan');
    Route::get('plan/get','Actions\BookController@plan');
    Route::post('plan/create','Actions\BookController@createPlan');
    Route::post('plan/update','Actions\BookController@updatePlan');
    Route::delete('plan/delete','Actions\BookController@deletePlan');

    Route::get('order/count','Actions\BookController@countOrder');
    Route::get('order/select','Actions\BookController@selectOrder');
    Route::get('order/get','Actions\BookController@Order');
    Route::post('order/create','Actions\BookController@createOrder');
    Route::post('order/update','Actions\BookController@updateOrder');
    Route::delete('order/delete','Actions\BookController@deleteOrder');
});