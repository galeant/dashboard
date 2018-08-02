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

	Route::resource('partner', 'CompanyController');
	Route::group(['prefix' => 'partner'], function(){
		Route::get('registration/activity', 'CompanyController@registrationList');
		Route::post('{id}/change/status', 'CompanyController@changeStatus');
	});
	Route::group(['prefix' => 'master'],function(){
		Route::resource('language', 'LanguageController');
		Route::resource('province', 'ProvinceController');
		Route::resource('city', 'CityController');
		Route::resource('district', 'DistrictController');
		Route::resource('village', 'VillageController');
		Route::resource('tour-guide-service', 'TourGuideServiceController');
		Route::resource('tour-guide', 'TourGuideServiceController');

		//Delete Photo
		Route::post('destination_photo/destroy','DestinationPhotoController@destroy');

		Route::group(['prefix' => 'destination'],function($id){
			Route::get('find', 'DestinationController@find');
			Route::get('status/active/{id}', 'DestinationController@active');
			Route::get('status/disabled/{id}', 'DestinationController@disabled');
		});
		//Destination
		Route::resource('destination', 'DestinationController');

		//DestinationType
		Route::resource('destination-type', 'DestinationTypeController');

		//DestinationTipsQuestion
		Route::resource('tips-question', 'DestinationTipsQuestionController');

		//API
		Route::get('/findCity/{id}','CityController@findCity');
		Route::get('/findDistrict/{id}','DistrictController@findDistrict');
		Route::get('/findVillage/{id}','VillageController@findVillage');

		//Activity Tag
		Route::resource('activity-tag', 'ActivityTagController');

		Route::group(['prefix' => 'supplier'],function($id){
			Route::get('password_reset/{id}','SupplierController@password_reset')->name('supplier.password_reset');
		});
		//Supplier
		Route::resource('supplier', 'SupplierController');

		// 
		

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
		Route::get('activity','ActivityTagController@activityList');

		Route::get('findDestination','DestinationController@findDestination');
		Route::get('destination','DestinationController@json');

		Route::post('changeStatus/{status}','TourController@changeStatus');
	});
	Route::group(['prefix' => 'product'],function(){
		Route::get('tour-activity/{id}/schedule', 'TourController@schedule');
		Route::post('tour-activity/{id}/{type}/schedule/save', 'TourController@scheduleSave');
		// change status
		Route::post('tour-activity/{id}/change/status', 'TourController@changeStatus');
		// UPDATE SHCHEDULE
		Route::post('tour-activity/schedule/update', 'TourController@scheduleUpdate');
		// DELETE SHCHEDULE
		Route::get('tour-activity/schedule/{id}/delete', 'TourController@scheduleDelete');
		Route::resource('tour-guide', 'TourGuideController');
		Route::resource('tour-activity', 'TourController');
		Route::post('/upload/image', 'TourController@uploadImageAjax');
		Route::post('/delete/image', 'TourController@deleteImageAjax');
		Route::post('/productinfo/update1','TourController@update1');
		Route::post('/productinfo/update2','TourController@update2');
		Route::post('/productinfo/update3','TourController@update3');
		Route::post('/productinfo/update4','TourController@update4');
		Route::post('/productinfo/update5','TourController@update5');
	});

 	Route::resource('members', 'MembersController');
	Route::resource('coupon', 'CouponController');

	Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'EmployeeController@logout']);
});


