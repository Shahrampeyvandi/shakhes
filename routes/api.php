<?php

Route::post('getcode', 'Api\LoginSignUpController@getcode');
Route::post('verify', 'Api\LoginSignUpController@verify');
Route::post('register', 'Api\LoginSignUpController@register');
Route::post('login', 'Api\LoginSignUpController@login');
 Route::get('/me', 'Api\LoginSignUpController@me');


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/member/notifications', 'Api\MembersDataController@notifications');
    //----------- بخش نماد من----------------
    Route::get('/namad', 'Api\NamadsController@getnamad');
    //---------- نمایش سرچ و افزودن----
    Route::get('/member', 'Api\MembersDataController@personaldata');
    Route::get('/member/namads', 'Api\MembersDataController@namads');
    Route::post('/member/namads/add', 'Api\MembersDataController@add');
    Route::post('/member/namads/remove', 'Api\MembersDataController@remove');
    Route::get('/namads/search', 'Api\NamadsController@search');
    Route::get('/getnotifications', 'Api\NamadsController@getHomeNotifications');
    Route::get('/read', 'Api\MembersDataController@mark_to_read');

});



// پایان سهام من

Route::get('/getalldata/{id}', 'Api\NamadsController@getalldata'); // افشای اطلاعات با اهمیت سهم




// Route::get('/getnotifications/{id}', 'Api\NamadsController@getNamadNotifications');


//Route::get('/member/clarifications', 'Api\MembersDataController@getclarifications'); // دریافت شفاف سازی سهام من
//Route::get('/member/capitalincreases', 'Api\MembersDataController@getcapitalincreases'); // دریافت افزایش سرمایه سهام من

//Route::get('/clarifications', 'Api\ClarificationController@getall'); // get all clarifications
//Route::get('/capitalincreases', 'Api\CapitalIncreasesController@getall'); // get all capitalincreases
Route::get('/continuingpaterns', 'Api\PaternsController@getContinuingPaterns'); // get all capitalincreases


//-----------بخش آموزشی------------------
Route::get('/education/list', 'Api\EducationController@list');
Route::get('/education/addview/{id}', 'Api\EducationController@addViewCount');
Route::get('/education/{id}', 'Api\EducationController@view');
//----------------------------------------------------------------

//---------- بخش بازار------------------
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
//----------------------------------------------------------------



// ----- صورت های مالی
Route::get('/getnamadmonthlyreports', 'Api\MoneyReportsController@getnamadmonthlyreports'); // اگر نماد , شرکت سرمایه گزاری باشد ...
Route::get('/getnamadseasonalreports', 'Api\MoneyReportsController@getnamadseasonalreports');
Route::get('/getnamadyearlyreports', 'Api\MoneyReportsController@getnamadyearlyreports');
//--------- دیگر بخش هاش
// Route::get('/getholdingdata', 'Api\MoneyReportsController@get_holding_data');
Route::get('/volumetradeincrease/{id?}', 'Api\VolumeTradesController@VolumeTradeIncease');
Route::get('disclosures/{id}', 'Api\MembersDataController@namadDisclosures'); // افشای اطلاعات با اهمیت سهم
Route::get('capitalincreases/{id?}', 'Api\MembersDataController@namadcapitalincreases'); // دریافت افزایش سرمایه های یک نماد از سهام من
Route::get('clarifications/{id?}', 'Api\MembersDataController@namadclarifications'); // دریافت شفاف سازی های یک نماد از سهام من
Route::get('getfinancial/{id}', 'Api\MoneyReportsController@getfinancial');
Route::get('/getvolumetrades/{id?}', 'Api\VolumeTradesController@get');
Route::get('/getholdingdata/{id}', 'Api\MoneyReportsController@get_holding_data');
Route::get('/getholdings', 'Api\MoneyReportsController@getHoldings');
//------------------------


// filter
Route::get('/filter/{key}', 'Api\FilterController@get');

Route::get('shownamad/{id}', 'Api\NamadsController@show');

Route::get('addselect/{type}/{id}', 'Api\MembersDataController@addToSelected');
Route::get('userselected/{type}', 'Api\MembersDataController@userSelected');

Route::get('namad/history', 'Api\NamadsController@namad_history_data');
Route::get('namad/support-resistance', 'Api\NamadsController@support_resistance');



/**
 * types : 
 * capital_increase,
 * clarification,
 * disclosure,
 * continuning_pattern,
 * support_resistance_line
 * 
 */




/** keys : 
 * 
 person_most_buy_sell
 person_most_sell_buy
 legal_most_buy_sell
legal_most_sell_buy
 most_cash_trade
 most_volume_trade
 most_person_buy
power_person_buy

most_person_buy
most_person_sell
most_legall_buy
most_legall_sell
 
 */
