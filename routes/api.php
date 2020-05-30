<?php

Route::post('getcode', 'Api\LoginSignUpController@getcode');
Route::post('verify', 'Api\LoginSignUpController@verify');
Route::post('register', 'Api\LoginSignUpController@register');
Route::post('login', 'Api\LoginSignUpController@login');

Route::post('/getnamadmonthlyreports', 'Api\MoneyReportsController@getnamadmonthlyreports');
Route::post('/getnamadseasonalreports', 'Api\MoneyReportsController@getnamadseasonalreports');
Route::post('/getnamadyearlyreports', 'Api\MoneyReportsController@getnamadyearlyreports');
