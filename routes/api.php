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

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1',
], function () {
    Route::post('auth', 'API\AuthController@store');
    Route::get('city', 'API\CityController@index');
    Route::get('eco_com_state', 'API\EcoComStateController@index');
});

Route::group([
    'middleware' => ['api', 'api_auth'],
    'prefix' => 'v1',
], function () {
    Route::get('auth', 'API\AuthController@index');
    Route::delete('auth', 'API\AuthController@destroy');
    Route::resource('economic_complement', 'API\EconomicComplementController')->only('index', 'show');
    Route::resource('liveness', 'API\LivenessController')->only('index', 'store');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['asssspi']], function () {
    Route::get('documents/received/{rol_id}/{user_id}', 'API\DocumentController@received');
    Route::get('documents/edited/{rol_id}/{user_id}', 'API\DocumentController@edited');
// });/