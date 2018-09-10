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
Route::group(['middleware' => ['auth:web'/*,'permission'*/]], function () {
	Route::get('/', function () {
	    return view('layouts.app');
	})->name('overview');

	Route::resource('partner', 'CompanyController');
	
	Route::resource('partner-product-type', 'CompanyProductTypeController');
	Route::get('partner-product-type/delete/{company_id}/{product_type_id}', 'CompanyProductTypeController@delete')->name('company.product_type');
	Route::group(['prefix' => 'partner'], function(){
		Route::get('registration/activity', 'CompanyController@registrationList')->name('partner.activity');
		Route::post('{id}/change/status', 'CompanyController@changeStatus')->name('partner.change_status');
	});
	Route::group(['prefix' => 'master', 'middleware' => 'permission'],function(){
		Route::resource('language', 'LanguageController');
		Route::resource('province', 'ProvinceController');
		Route::resource('city', 'CityController');
		Route::resource('district', 'DistrictController');
		Route::resource('village', 'VillageController');
		Route::resource('tour-guide-service', 'TourGuideServiceController');
		Route::resource('tour-guide', 'TourGuideServiceController');

		//Delete Photo
		Route::post('destination_photo/destroy','DestinationPhotoController@destroy')->name('destination.delete_photo');
		Route::post('destination_tips/destroy','DestinationController@delTips')->name('destination.delete_tips');

		Route::group(['prefix' => 'destination'],function($id){
			Route::get('find', 'DestinationController@find')->name('destination.find');
			Route::get('status/active/{id}', 'DestinationController@active')->name('destination.status');
			Route::get('status/disabled/{id}', 'DestinationController@disabled')->name('destination.disabled');
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

		Route::post('changeStatus/{status}','TourController@changeStatus');
	});
	Route::group(['prefix' => 'product'],function(){
		Route::get('tour-activity/{id}/schedule', 'TourController@schedule')->name('product.schedule');
		Route::get('tour-activity/{id}/off-day', 'TourController@offDay')->name('product.schedule_off_day');
		Route::get('tour-activity/{id}/off-day/check', 'TourController@offDayCheck')->name('product.schedule_off_day_check');
		Route::post('tour-activity/{id}/off-day/save', 'TourController@offDayUpdate')->name('product.schedule_off_day_update');
		Route::post('tour-activity/{id}/{type}/schedule/save', 'TourController@scheduleSave')->name('product.schedule_update');
		// change status
		Route::get('tour-activity/{id}/change/status/{status}', 'TourController@changeStatus')->name('product.change_status');
		// UPDATE SHCHEDULE
		Route::post('tour-activity/schedule/update', 'TourController@scheduleUpdate')->name('product.schedule_update');
		// DELETE SHCHEDULE
		Route::post('tour-activity/schedule/{id}/delete', 'TourController@scheduleDelete')->name('product.schedule_delete');
		// UPDATE PRICE
		Route::post('tour-activity/price/update', 'TourController@priceUpdate')->name('product.price_update');
		// DELETE PRICE
		Route::get('tour-activity/{product_id}/price/{id}/delete', 'TourController@priceDelete')->name('product.price_delete');

		Route::resource('tour-guide', 'TourGuideController');
		Route::resource('tour-activity', 'TourController');
		Route::post('/upload/image', 'TourController@uploadImageAjax');
		Route::post('/delete/image', 'TourController@deleteImageAjax');

		//planning
		Route::get('planning/{transaction_number}/print/{type}', 'PlanningController@print')->name('planning.print');
	});
	Route::group(['prefix' => 'settlement'],function(){
		Route::get('/all', 'SettlementController@index')->name('settlement.view');
		Route::get('/detail/{id}', 'SettlementController@detail')->name('settlement.find');
		Route::get('/generate', 'SettlementController@generate')->name('settlement.generate');
		Route::post('/procced', 'SettlementController@poccedList')->name('settlement.process_list');
		Route::get('/excel/{id}', 'SettlementController@exportExcel')->name('settlement.export_excel');
		Route::get('/pdf/{id}', 'SettlementController@exportPdf')->name('settlement.export_pdf');
		Route::post('/filter', 'SettlementController@filter')->name('settlement.filter');
		Route::post('/paid', 'SettlementController@paid')->name('settlement.paid');
		Route::post('/update-notes', 'SettlementController@notes')->name('settlement.update_notes');
		Route::post('/update-bank', 'SettlementController@bank')->name('settlement.update_bank');
	});
	Route::resource('members', 'MembersController');
	Route::resource('products', 'ProductsController');
	Route::resource('coupon', 'CouponController');
	// 
	Route::group(['prefix' => 'autorization'],function(){
		Route::resource('/employee', 'EmployeeController');	
		Route::resource('/roles', 'RolesController');	
		Route::resource('/permission', 'PermissionController');	
	});
	Route::group(['prefix' => 'bookings'],function(){
		Route::resource('tour', 'BookingTourController');
		Route::get('tour/{kode}/refund','BookingTourController@refund')->name('booking_tour.refund');
		Route::resource('accomodation-uhotel', 'BookingAccomodationUHotelController');
		Route::get('accomodation-uhotel/{kode}/refund','BookingAccomodationUHotelController@refund')->name('booking_uhotel.refund');
		Route::resource('accomodation-tiket', 'BookingAccomodationTiketController');
		Route::get('accomodation-tiket/{kode}/refund','BookingAccomodationTiketController@refund')->name('booking_tiket.refund');
		Route::resource('rent-car', 'BookingRentCarController');
		Route::get('rent-car/{kode}/refund','BookingRentCarController@refund')->name('booking_rent_car.refund');
	});
	Route::resource('transaction', 'TransactionController');
	Route::get('transaction/{transaction_number}/print/{type}', 'TransactionController@print')->name('transaction.print');
	
	Route::get('transaction/{transaction_number}/print/itinerary/{type}', 'TransactionController@print_itinerary')->name('transaction.print_itinerary');
	
	Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'EmployeeController@logout']);
});