<?php

Route::post('getcode', 'Api\LoginSignUpController@getcode');
Route::post('verify', 'Api\LoginSignUpController@verify');
Route::post('register', 'Api\LoginSignUpController@register');
Route::post('login', 'Api\LoginSignUpController@login');

// سهام من
Route::get('/member', 'Api\MembersDataController@personaldata');
Route::get('/member/namads', 'Api\MembersDataController@namads');
Route::post('/member/namads/add', 'Api\MembersDataController@add');
Route::get('/member/clarifications', 'Api\MembersDataController@getclarifications'); // دریافت شفاف سازی سهام من
Route::get('/member/clarifications/{id}', 'Api\MembersDataController@namadclarifications'); // دریافت شفاف سازی های یک نماد از سهام من

Route::post('/member/capitalincreases/marktoread', 'Api\MembersDataController@read_capitalincreases'); // با کلیک روی لینک به حالت خوانده شده تغییر میکند
Route::post('/member/clarification/marktoread', 'Api\MembersDataController@read_clarification'); // با کلیک روی لینک به حالت خوانده شده تغییر میکند

Route::get('/member/capitalincreases', 'Api\MembersDataController@getcapitalincreases'); // دریافت افزایش سرمایه سهام من
Route::get('/member/capitalincreases/{id}', 'Api\MembersDataController@namadcapitalincreases'); // دریافت افزایش سرمایه های یک نماد از سهام من





// پایان سهام من




Route::get('/namad', 'Api\NamadsController@getnamad');
Route::get('/namads/search', 'Api\NamadsController@search');



Route::get('/getnamadmonthlyreports', 'Api\MoneyReportsController@getnamadmonthlyreports'); // اگر نماد , شرکت سرمایه گزاری باشد ...
Route::get('/getnamadseasonalreports', 'Api\MoneyReportsController@getnamadseasonalreports');
Route::get('/getnamadyearlyreports', 'Api\MoneyReportsController@getnamadyearlyreports');

Route::get('/getholdingdata', 'Api\MoneyReportsController@get_holding_data');

Route::get('/clarifications', 'Api\ClarificationController@getall'); // get all clarifications
Route::get('/capitalincreases', 'Api\CapitalIncreasesController@getall'); // get all capitalincreases
Route::get('/continuingpaterns', 'Api\PaternsController@getContinuingPaterns'); // get all capitalincreases
