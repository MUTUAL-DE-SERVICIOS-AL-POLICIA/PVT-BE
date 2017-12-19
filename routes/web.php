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

Route::get('/', 'HomeController@index')->name("main");
Route::get('/minor', 'HomeController@minor')->name("minor");

//afiliates
Route::resource('affiliate', 'AffiliateController');
Route::patch('/update_affiliate/{affiliate}','AffiliateController@update')->name('update_affiliate');
Route::get('get_all_affiliates', 'AffiliateController@getAllAffiliates');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

