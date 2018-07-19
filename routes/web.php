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

Route::get('login', function () {
	return view('login');
})->name('login');
Route::post('/authenticate', ['as' => 'auth', 'uses' => 'EmployeeController@authenticate']);
Route::group(['middleware' => ['auth:web']], function () {
	Route::get('/', function () {
	    return view('layouts.app');
	});
	Route::group(['prefix' => 'master'],function(){
		Route::get('country/any-data', 'CountryController@anyData');
		Route::resource('country', 'CountryController');
		Route::resource('language', 'LanguageController');
		Route::resource('province', 'ProvinceController');
		Route::resource('city', 'CityController');
		Route::resource('district', 'DistrictController');
		Route::resource('village', 'VillageController');
		Route::resource('tour-guide-service', 'TourGuideServiceController');

		//Destination
		Route::resource('destination', 'DestinationController');
		
		Route::group(['prefix' => 'destination'],function($id){
			Route::get('status/active/{id}', 'DestinationController@active');
			Route::get('status/disabled/{id}', 'DestinationController@disabled');
		});
		//DestinationType
		Route::resource('destination-type', 'DestinationTypeController');

		//API
		Route::get('/findCity/{id}','CityController@findCity');
		Route::get('/findDistrict/{id}','DistrictController@findDistrict');
		Route::get('/findVillage/{id}','VillageController@findVillage');
	});
	Route::group(['prefix' => 'product'],function(){
		Route::resource('tour-guide', 'TourGuideController');
	});
	Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'EmployeeController@logout']);
});
