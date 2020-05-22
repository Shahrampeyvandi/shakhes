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
// Route::post('/events-filter', 'FrontEnd\EventController@events_filter')->name('FrontEnd.event_filtering');
// Route::get('/event/{id}','FrontEnd\eventController@event_page')->name('FrontEnd.event_page');
// Route::get('/media', 'FrontEnd\MainController@Media')->name('FrontEnd.media');
// Route::get('/News/{id?}', 'FrontEnd\NewsController@singleNew')->name('FrontEnd.singleNew');
// Route::get('/Brokers', 'FrontEnd\MainController@brokers')->name('FrontEnd.brokers');
// Route::post('Brokers-filter', 'FrontEnd\BrokersController@filtering')->name('FrontEnd.brokers.filter');
// Route::get('/partners', 'FrontEnd\MainController@partners')->name('FrontEnd.partners');
// Route::get('/services', 'FrontEnd\MainController@Services')->name('FrontEnd.Services');
// Route::get('/StatisticS', 'FrontEnd\MainController@StatisticS')->name('FrontEnd.StatisticS');
// Route::get('/Successful-projects', 'FrontEnd\MainController@stories')->name('FrontEnd.stories');
// Route::get('/Project/{id?}', 'FrontEnd\ProjectController@project_page')->name('FrontEnd.project_page');