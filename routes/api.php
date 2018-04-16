<?php

use Illuminate\Http\Request;
Use App\SensorData;

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

Route::post('register', 'Auth\RegisterController@register');


Route::group(['middleware' => 'auth:api'], function() {	
   	Route::get('sensor_data/{areaId}/{deviceId}/{from}/{to}', 'SensorDataController@getSensorDataByDate');
   	Route::get('sensor_data/{areaId}/{deviceId}/realtime', 'SensorDataController@getSensorDataRealtime');
	Route::get('sensor_data', 'SensorDataController@index');
	Route::get('sensor_data/{sensordata}', 'SensorDataController@show');
	Route::post('sensor_data', 'SensorDataController@store');
	Route::put('sensor_data/{sensordata}', 'SensorDataController@update');
	Route::delete('sensor_data/{sensordata}', 'SensorDataController@delete');


	Route::get('area', 'AreaController@index');
	Route::get('area/{area}', 'AreaController@show');
	Route::post('area', 'AreaController@store');
	Route::put('area/{area}', 'AreaController@update');
	Route::delete('area/{area}', 'AreaController@delete');


	//Custom
	


});
