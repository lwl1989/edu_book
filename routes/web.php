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

    Route::get('plan/count','Actions\PlanController@count');
    Route::get('plan/select','Actions\PlanController@select');
    Route::get('plan/get','Actions\PlanController@get');
    Route::post('plan/create','Actions\PlanController@create');
    Route::post('plan/update','Actions\PlanController@update');
    Route::delete('plan/delete','Actions\PlanController@delete');

    Route::get('order/count','Actions\BookController@countOrder');
    Route::get('order/select','Actions\BookController@selectOrder');
    Route::get('order/get','Actions\BookController@Order');
    Route::post('order/create','Actions\BookController@createOrder');
    Route::post('order/update','Actions\BookController@updateOrder');
    Route::delete('order/delete','Actions\BookController@deleteOrder');
});

Route::group(['prefix'=>'class','middleware'=>['format']], function (){

    Route::get('count','Actions\ClassesController@count');
    Route::get('select','Actions\ClassesController@select');
    Route::get('get','Actions\ClassesController@get');
    Route::post('create','Actions\ClassesController@create');
    Route::post('update','Actions\ClassesController@update');
    Route::delete('delete','Actions\ClassesController@delete');
    
});