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
		Route::resource('company', 'CompanyController');
		Route::resource('product', 'TourController');
		Route::resource('country', 'CountryController');
		Route::resource('language', 'LanguageController');
		Route::resource('province', 'ProvinceController');
		Route::resource('city', 'CityController');
		Route::resource('district', 'DistrictController');
		Route::resource('village', 'VillageController');
		Route::resource('tour-guide-service', 'TourGuideServiceController');
		Route::resource('tour-guide', 'TourGuideServiceController');

		Route::group(['prefix' => 'destination'],function($id){
			Route::post('deletePhoto','DestinationTypeController@deletePhoto');
			Route::get('find', 'DestinationController@find');
			Route::get('status/active/{id}', 'DestinationController@active');
			Route::get('status/disabled/{id}', 'DestinationController@disabled');
		});
		//Destination
		Route::resource('destination', 'DestinationController');

		//DestinationType
		Route::resource('destination-type', 'DestinationTypeController');

		//API
		Route::get('/findCity/{id}','CityController@findCity');
		Route::get('/findDistrict/{id}','DistrictController@findDistrict');
		Route::get('/findVillage/{id}','VillageController@findVillage');
	});
	Route::group(['prefix' => 'json'], function(){
		Route::get('country','CountryController@json');
		Route::get('language','LanguageController@json');
		Route::get('province','ProvinceController@json');
		// 
		Route::get('city','CityController@json');
		Route::get('findCity','CityController@findCity');
		// 
		Route::get('district','DistrictController@json');
		Route::get('village','VillageController@json');
		Route::get('company','CompanyController@json');
		
	});
	Route::group(['prefix' => 'product'],function(){
		Route::resource('tour-guide', 'TourGuideController');
	});

 	Route::resource('members', 'MembersController');
	Route::resource('coupon', 'CouponController');

	Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'EmployeeController@logout']);
});


