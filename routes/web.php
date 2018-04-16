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

Route::get('/', 'HomeController@landing');

Route::get('/getDeviceList/{areaId}', 'HomeController@getDeviceList');

Route::get('/map', 'HomeController@getMap');

Auth::routes();



Route::get('/home', 'HomeController@index')->name('home');

Route::get('/monthly', 'HomeController@monthly');

Route::get('/all_time', 'HomeController@yearly');

Route::get('/realtime', 'HomeController@realtime');

Route::get('/getLastPoint/{areaId}/{deviceId}/{timestamp}/{level}', 'HomeController@getLastPoint');

Route::get('/getChartData/{areaId}/{deviceId}', 'HomeController@getChartData');

Route::get('/getDataForYearly/{areaId}/{deviceId}', 'HomeController@getDataForYearly');


Route::get('/getLastPointLanding/{areaId}/{deviceId}', 'HomeController@getLastPointLanding');

Route::get('/data_map', 'HomeController@data_map');

