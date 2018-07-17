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
	Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'EmployeeController@logout']);
});
