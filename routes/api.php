<?php

use Illuminate\Http\Request;

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

Route::get('/test',function(){
     return "ok"; 
});

Route::post( '/webhooks/order', 'webhooks\Orders@listen' );

Route::post( '/webhooks/refund', 'webhooks\Orders@refund' );

Route::post( '/webhooks/cancel', 'webhooks\Orders@cancel' );

Route::get( '/getProducts', 'api\getProducts@all' );