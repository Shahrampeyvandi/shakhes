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

Route::get('/member/capitalincreases', 'Api\MembersDataController@getcapitalincreases'); // دریافت افزایش سرمایه سهام من
Route::get('/member/capitalincreases/{id}', 'Api\MembersDataController@namadcapitalincreases'); // دریافت افزایش سرمایه های یک نماد از سهام من

Route::get('/member/notifications', 'Api\MembersDataController@notifications'); 


Route::get('/member/disclosures/{id}', 'Api\MembersDataController@namadDisclosures'); // افشای اطلاعات با اهمیت سهم


// پایان سهام من

Route::get('/getalldata/{id}', 'Api\NamadsController@getalldata'); // افشای اطلاعات با اهمیت سهم



Route::get('/namad', 'Api\NamadsController@getnamad');
Route::get('/namads/search', 'Api\NamadsController@search');


// notification routes
Route::get('/getnotifications', 'Api\NamadsController@GetAllNotifications');
// Route::get('/getnotifications/{id}', 'Api\NamadsController@getNamadNotifications');




Route::get('/getnamadmonthlyreports', 'Api\MoneyReportsController@getnamadmonthlyreports'); // اگر نماد , شرکت سرمایه گزاری باشد ...
Route::get('/getnamadseasonalreports', 'Api\MoneyReportsController@getnamadseasonalreports');
Route::get('/getnamadyearlyreports', 'Api\MoneyReportsController@getnamadyearlyreports');

Route::get('/getholdingdata', 'Api\MoneyReportsController@get_holding_data');

Route::get('/clarifications', 'Api\ClarificationController@getall'); // get all clarifications
Route::get('/capitalincreases', 'Api\CapitalIncreasesController@getall'); // get all capitalincreases
Route::get('/continuingpaterns', 'Api\PaternsController@getContinuingPaterns'); // get all capitalincreases


Route::get('/getvolumetrades/{id?}', 'Api\VolumeTradesController@get'); 



// *************  S A H A M     M A N   ***************** //

Route::get('/volumetradeincrease/{id}', 'Api\VolumeTradesController@VolumeTradeIncease'); 




Route::get('/education/list', 'Api\EducationController@list'); 

Route::get('/education/addview/{id}', 'Api\EducationController@addViewCount'); 
Route::get('/education/{id}', 'Api\EducationController@view'); 


// market routes
Route::get('/bourse/shakhes', 'Api\MarketController@bshackes');
Route::get('/farabourse/shakhes', 'Api\MarketController@fshackes');
Route::get('/bourse/mostvisited', 'Api\MarketController@bourseMostVisited'); 
Route::get('/farabourse/mostvisited', 'Api\MarketController@farabourceMostVisited');
Route::get('/bourse/mosteffect', 'Api\MarketController@bourseEffectInShakhes');
Route::get('/farabourse/mosteffect', 'Api\MarketController@farabourseEffectInShakhes');
Route::get('/bourse/mostpriceincreases', 'Api\MarketController@bourseMostPriceIncreases');
Route::get('/farabourse/mostpriceincreases', 'Api\MarketController@farabourseMostPriceIncreases');
Route::get('/bourse/mostpricedecreases', 'Api\MarketController@bourseMostPriceDecreases');
Route::get('/farabourse/mostpricedecreases', 'Api\MarketController@farabourseMostPriceDecreases');
Route::get('/bnamad', 'Api\MarketController@getNamad');
Route::get('/bnamads/search', 'Api\MarketController@search');
