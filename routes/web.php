<?php
use Illuminate\Support\Facades\Route;

/**
 * 微信消息接口
 */
Route::group ( [
    'namespace' => 'Awz\Notice\Http\Controllers' ,
] , function () {
    Route::post ( 'notice' , 'NoticeController@api' );
    Route::get ( 'notice' , 'NoticeController@api' );
} );
