<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['cors', 'json.response']], function () {

/*
|   Auth
|--------------------------------------------------------------------------
*/
    Route::post('/adduser','Api\UserController@adduser');

    Route::post('/login','Api\UserController@login');

    Route::post('/forgot','Api\ForgotPasswordController@forgotpassword');

    Route::post('/reset','Api\ForgotPasswordController@resetpassword');


    Route::group(['middleware' => ['auth:api']], function () {
    /*
    |   User
    |--------------------------------------------------------------------------
    */
        Route::get('/data','Api\UserController@index')->middleware('api.superAdmin');
    
        Route::post('/deleteuser','Api\UserController@deleteuser');
    
        Route::post('/getuser','Api\UserController@getuser');
    
        Route::post('/edituser','Api\UserController@edituser');
    
        Route::post('/logout','Api\UserController@logout');
    
    /*
    |   Cart & Order
    |--------------------------------------------------------------------------
    */
        Route::post('/addToCart','Api\CartOrderController@cart');
    
        Route::post('/getcart','Api\CartOrderController@getcart');
    
        Route::post('/cartproduct','Api\CartOrderController@cartproduct');
    
        Route::post('/changequantity','Api\CartOrderController@changequantity');
    
        Route::post('/placeOrder','Api\CartOrderController@placeorder');
    
        Route::post('/getorder','Api\CartOrderController@getorder');
    
        Route::post('/getorderdetails','Api\CartOrderController@getorderdetails');
    
        Route::post('/changestatus','Api\CartOrderController@changestatus');
    
    /*
    |   Category
    |--------------------------------------------------------------------------
    */
        Route::get('/category','Api\CategoryController@index')->middleware('api.admin');
    
        Route::post('/addcategory','Api\CategoryController@addcategory')->middleware('api.admin');
    
        Route::post('/deletecategory','Api\CategoryController@deletecategory')->middleware('api.admin');
    
        Route::post('/getcategory','Api\CategoryController@getcategory')->middleware('api.admin');
    
        Route::post('/editcategory','Api\CategoryController@editcategory')->middleware('api.admin');
    
    /*
    |   Product
    |--------------------------------------------------------------------------
    */
        Route::get('/product','Api\ProductController@index');
    
        Route::post('/addproduct','Api\ProductController@addproduct')->middleware('api.admin');
    
        Route::post('/deleteproduct','Api\ProductController@deleteproduct')->middleware('api.admin');
    
        Route::post('/getproduct','Api\ProductController@getproduct');
    
        Route::post('/editproduct','Api\ProductController@editproduct')->middleware('api.admin');
    });
});