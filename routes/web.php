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

Route::get('/', 'MainController@Index')->name('BaseUrl');

Route::get('/moneyreports', 'MoneyReportsController@Index')->name('MoneyReports');
Route::post('/moneyreports/monthly', 'MoneyReportsController@SubmitMonthly')->name('MonyReport.Monthly');
Route::post('/moneyreports/seasonly', 'MoneyReportsController@SubmitSeasonly')->name('MonyReport.Seasonly');
Route::get('/users', 'UsersController@Index')->name('Users');
Route::get('/portfoy', 'PortfoyController@Index')->name('PortfoyList');
Route::get('/holding/create', 'PortfoyController@CreateHolding')->name('Holding.Create');
Route::post('/holding/create', 'PortfoyController@InsertHolding')->name('Holding.Create');

Route::post('/holding/delete', 'PortfoyController@DeleteHolding')->name('Holding.Delete');
Route::get('/holding/{id}/namads', 'PortfoyController@ShowNamads')->name('Holding.Namads');







// ajax routes
Route::post('/moneyreports/getdata', 'Ajax\MoneyReportController@getmoneyreportsdata')->name('getmoneyreportsdata');
// Route::post('/moneyreports/getseasondata', 'AjaxController@getmoneyreportseasonaldata')->name('getmoneyreportseasonaldata');

Route::post('/getNamadData', 'Ajax\MoneyReportController@getNamadData')->name('getNamadData');









