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

Route::get('/', function () {
    return view('welcome');
});
// test === /test    /test overwrite test
Route::get('test', function () {
        echo 'test';
});
Route::get('/test', function () {
        echo 'test2';
});
Route::post('testPost', function () {
        echo 'test';
});
//當get和post進行相同的處理的時候
Route::match(['get','post'],'multi',function (){
        echo "it's multi";
});
//當所有路由都同時請求的時候
Route::any('any',function (){
        echo "it's any";
});
//動態路由
Route::get('user/{id}',function ($id) {
        echo 'userId is '.$id;
});
//需要默認值的參數  使用問號
Route::get('username/{username?}',function ($name = '張三') {
        echo 'username is '.$name;
});
//動態參數的路由正則驗證
Route::get('reg/{reg?}',function ($name = '張三') {
        echo 'reg is '.$name;
})->where('reg','[A-Za-z]+')
;

//多條件驗證
Route::get('reg_multi/{regstr?}/{regnum?}',function (string $name = '張三', int $num = 5) {
        echo 'reg is '.$name.' num is '.$num;
})->where(['regstr'=>'[A-Za-z]+','regnum'=>'[0-9]+'])
;

//別名 ????? todo
Route::get('f',function(){
        //return route('f');
        return 'fffff == f';
});
Route::get('fffff',['as'=>'f',function(){
        return route('f');
        //echo 'fffff == f';
}]);


//路由羣組
Route::group(['prefix'=>'p'],function (){
        Route::any('any',function (){
                echo "it's prefix any";
        });
        Route::any('any1',function (){
                echo "it's prefix any1";
        });
});

//視圖
Route::get('view',function (){
        return view('view');
});