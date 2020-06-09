<?php

Route::post('getcode', 'Api\LoginSignUpController@getcode');
Route::post('verify', 'Api\LoginSignUpController@verify');
Route::post('register', 'Api\LoginSignUpController@register');
Route::post('login', 'Api\LoginSignUpController@login');


Route::get('/member', 'Api\MembersDataController@personaldata');
Route::get('/member/namads', 'Api\MembersDataController@namads');
Route::post('/member/namads/add', 'Api\MembersDataController@add');


Route::post('/namads/search', 'Api\NamadsController@search');



Route::post('/getnamadmonthlyreports', 'Api\MoneyReportsController@getnamadmonthlyreports'); // اگر نماد , شرکت سرمایه گزاری باشد ...
Route::post('/getnamadseasonalreports', 'Api\MoneyReportsController@getnamadseasonalreports');
Route::post('/getnamadyearlyreports', 'Api\MoneyReportsController@getnamadyearlyreports');


