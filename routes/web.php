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

Route::get('/', ['as' => 'index', 'uses' => 'ArloController@index']);
Route::post('/arm', ['as' => 'arm', 'uses' => 'ArloController@arm']);
Route::post('/disarm', ['as' => 'disarm', 'uses' => 'ArloController@disarm']);