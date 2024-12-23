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
    Route::resource('auth', 'API\AuthController')->only('store');
    Route::get('version', 'API\VersionController@versiones');
    Route::resource('city', 'API\CityController')->only('index');
    Route::resource('affiliate.observation', 'API\AffiliateObservationController')->only('index');
    Route::resource('policy', 'API\PolicyController')->only('index');
    Route::resource('eco_com_procedure', 'API\EcoComProcedureController')->only('index');
    Route::post('version', 'API\VersionController@version');
});

Route::group([
    'middleware' => ['api', 'api_auth'],
    'prefix' => 'v1',
], function () {
    Route::resource('auth', 'API\AuthController')->only('index', 'destroy');
    Route::resource('economic_complement', 'API\EconomicComplementController')->only('index', 'store', 'show');
    Route::get('economic_complement/print/{economic_complement}', 'API\EconomicComplementController@print');
    Route::resource('liveness', 'API\LivenessController')->only('index', 'store', 'show');
    Route::resource('message', 'API\MessageController')->only('show');
    Route::resource('eco_com_procedure', 'API\EcoComProcedureController')->only('show');
});

Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('kioskoComplemento', 'API\EconomicComplementController@checkAvailability');
});

Route::group([
    'middleware' => ['verify.bearer'],
    'prefix' => 'v1',
], function () {
    Route::get('eco_com/{eco_com_id}', 'API\EconomicComplementController@showFormatedEcoCom');
    Route::post('eco_com', 'API\EconomicComplementController@createEcoCom');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['middleware' => ['asssspi']], function () {
Route::get('documents/received/{rol_id}/{user_id}', 'API\DocumentController@received');
Route::get('documents/edited/{rol_id}/{user_id}', 'API\DocumentController@edited');
// });/