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

Route::get('/works', function() {
	return "works";
});

Route::post( '/webhooks/order', 'webhooks\Orders@listen' );

Route::get('/webhooks/process', 'webhooks\Orders@process');
