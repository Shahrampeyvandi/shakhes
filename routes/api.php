<?php

Route::post('getcode', 'Api\LoginSignUpController@getcode');
Route::post('verify', 'Api\LoginSignUpController@verify');
Route::post('register', 'Api\LoginSignUpController@register');
Route::post('login', 'Api\LoginSignUpController@login');
