<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/', 'HomeController@index')->name("main");
Route::get('/minor', 'HomeController@minor')->name("minor");

//afiliates
Route::resource('affiliate', 'AffiliateController');
Route::patch('/update_affiliate/{affiliate}','AffiliateController@update')->name('update_affiliate');
Route::patch('/update_affiliate_police/{affiliate}','AffiliateController@update_affiliate_police')->name('update_affiliate_police');
Route::get('get_all_affiliates', 'AffiliateController@getAllAffiliates');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('changerol','UserController@changerol');

//retirement fund
//RetirementFundRequirements
Route::resource('ret_fun', 'RetirementFundController');
Route::get('affiliate/{affiliate}/procedure_create', 'RetirementFundRequirementController@generateProcedure');
Route::get('affiliate/{affiliate}/ret_fun/create', 'RetirementFundController@generateProcedure');
