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
	
	Route::resource('partner-product-type', 'CompanyProductTypeController');
	Route::get('partner-product-type/delete/{company_id}/{product_type_id}', 'CompanyProductTypeController@delete');
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
		Route::post('destination_tips/destroy','DestinationController@delTips');

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

	});
	//Supplier
	Route::resource('supplier', 'SupplierController');
	Route::group(['prefix' => 'supplier'],function($id){
		Route::get('password_reset/{id}','SupplierController@password_reset')->name('supplier.password_reset');
	});
	// 
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

		Route::get('tour','TourController@json');
		Route::get('campaign','CampaignController@json');

		Route::post('changeStatus/{status}','TourController@changeStatus');
	});
	Route::group(['prefix' => 'product'],function(){
		Route::get('tour-activity/{id}/schedule', 'TourController@schedule');
		Route::get('tour-activity/{id}/off-day', 'TourController@offDay');
		Route::get('tour-activity/{id}/off-day/check', 'TourController@offDayCheck');
		Route::post('tour-activity/{id}/off-day/save', 'TourController@offDayUpdate');
		Route::post('tour-activity/{id}/{type}/schedule/save', 'TourController@scheduleSave');
		// change status
		Route::get('tour-activity/{id}/change/status/{status}', 'TourController@changeStatus');
		// UPDATE SHCHEDULE
		Route::post('tour-activity/schedule/update', 'TourController@scheduleUpdate');
		// DELETE SHCHEDULE
		Route::post('tour-activity/schedule/{id}/delete', 'TourController@scheduleDelete');
		// UPDATE PRICE
		Route::post('tour-activity/price/update', 'TourController@priceUpdate');
		// DELETE PRICE
		Route::get('tour-activity/{product_id}/price/{id}/delete', 'TourController@priceDelete');

		Route::resource('tour-guide', 'TourGuideController');
		Route::resource('tour-activity', 'TourController');
		Route::post('/upload/image', 'TourController@uploadImageAjax');
		Route::post('/delete/image', 'TourController@deleteImageAjax');

		//planning
		Route::get('planning/{transaction_id}/{planning_id}/print/{type}', 'PlanningController@print');
	});
	Route::group(['prefix' => 'settlement'],function(){
		Route::get('/all', 'SettlementController@index');
		Route::get('/detail/{id}', 'SettlementController@detail');
		Route::get('/generate', 'SettlementController@generate');
		Route::post('/procced', 'SettlementController@poccedList');
		Route::get('/excel/{id}', 'SettlementController@exportExcel');
		Route::get('/pdf/{id}', 'SettlementController@exportPdf');
		Route::post('/filter', 'SettlementController@filter');
		Route::post('/paid', 'SettlementController@paid');
		Route::post('/update-notes', 'SettlementController@notes');
		Route::post('/update-bank', 'SettlementController@bank');
	});
	Route::resource('members', 'MembersController');
	Route::resource('products', 'ProductsController');
	Route::resource('coupon', 'CouponController');

	Route::group(['prefix' => 'campaign'],function(){
		Route::resource('campaign-list', 'CampaignController');
		Route::resource('campaign-product', 'CampaignProductController');
	});
	
	Route::group(['prefix' => 'bookings'],function(){
		Route::resource('tour', 'BookingTourController');
		Route::resource('accomodation-uhotel', 'BookingAccomodationUHotelController');
		Route::resource('accomodation-tiket', 'BookingAccomodationTiketController');
		Route::resource('rent-car', 'BookingRentCarController');
	});
	Route::resource('transaction', 'TransactionController');
	Route::get('transaction/{transaction_number}/print/{type}', 'TransactionController@print');
	Route::get('transaction/{transaction_number}/send_receipt', 'TransactionController@send_receipt');
	Route::get('transaction/{transaction_number}/print/{planning_id}/itinerary/{type}', 'TransactionController@print_itinerary');
	
	
	Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'EmployeeController@logout']);
	// Route::get('/emailTest', function(){
	// 	$details['email'] = 'ilham.rach.f@gmail.com';
	// 	dispatch(new App\Jobs\SendEmailTest($details));
	// 	dd('done');
	// });
});
// FOR EMAIL TESTING
// Route::get('/mailable', function () {
//     $company = App\Models\Company::where('id',9)->with(['suppliers' => function($query){
// 		$query->orderBy('created_at','asc')->first();
// 	}])->first();

//     return new App\Mail\StatusCompany($company);
// });
Route::get('lolo','SettlementController@tes');