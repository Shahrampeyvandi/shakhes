<?php

Route::post('getcode', 'Api\LoginSignUpController@getcode');
Route::post('verify', 'Api\LoginSignUpController@verify');
Route::post('register', 'Api\LoginSignUpController@register');
Route::post('login', 'Api\LoginSignUpController@login');



Route::group(['middleware' => ['jwt.verify']], function () {
    Route::any('/updateFirebaseToken', 'Api\LoginSignUpController@updateFirebaseToken');
    Route::get('/me', 'Api\LoginSignUpController@me');
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
    Route::get('/capitalincreases', 'Api\CapitalIncreasesController@getall'); // get all capitalincreases
    Route::get('/getvolumetrades', 'Api\VolumeTradesController@get');
    Route::get('/clarifications', 'Api\ClarificationController@getall'); // get all clarifications
    Route::get('addbookmark', 'Api\MembersDataController@addToSelected');
    Route::get('userbookmarks', 'Api\MembersDataController@userSelected');
    Route::post('ticket/submit', 'Api\TicketController@add');
    Route::get('tickets/list', 'Api\TicketController@list');
    Route::get('plans/list', 'Api\PlanController@list');
    Route::get('member/namad/tablist', 'Api\MembersDataController@tabList');


    //---------- بخش بازار------------------
    Route::group(['prefix' => 'market'], function () {
        
        Route::get('/index/chart','Api\MarketController@index_chart');
        Route::get('index/distributions-status','Api\MarketController@get_distributes');
        Route::get('/index/shakhes-status','Api\MarketController@index_values');

        Route::get('/1/topindex', 'Api\MarketController@bshackes');
        Route::get('/2/topindex', 'Api\MarketController@fshackes');
        Route::get('/1/mostvisited', 'Api\MarketController@bourseMostVisited');
        Route::get('/2/mostvisited', 'Api\MarketController@farabourceMostVisited');
        Route::get('/1/mosteffect', 'Api\MarketController@bourseEffectInShakhes');
        Route::get('/2/mosteffect', 'Api\MarketController@farabourseEffectInShakhes');
        Route::get('/1/most-price-increases', 'Api\MarketController@bourseMostPriceIncreases');
        Route::get('/2/most-price-increases', 'Api\MarketController@farabourseMostPriceIncreases');
        Route::get('/1/most-price-decreases', 'Api\MarketController@bourseMostPriceDecreases');
        Route::get('/2/most-price-decreases', 'Api\MarketController@farabourseMostPriceDecreases');
        Route::get('/namad', 'Api\MarketController@getNamad');
        Route::get('chart', 'Api\MarketController@chart');
       
        Route::get('/bnamads/search', 'Api\MarketController@search');
        //-----------بخش آموزشی------------------
        Route::get('/education/list', 'Api\EducationController@list');
        Route::get('/education/addview/{id}', 'Api\EducationController@addViewCount');
        Route::get('/education/{id}', 'Api\EducationController@view');
        //----------------------------------------------------------------
    });
    //----------------------------------------------------------------

});
Route::get('getholdings', 'Api\MoneyReportsController@getHoldings');



// پایان سهام من

Route::get('/getalldata/{id}', 'Api\NamadsController@getalldata'); // افشای اطلاعات با اهمیت سهم




// Route::get('/getnotifications/{id}', 'Api\NamadsController@getNamadNotifications');


//Route::get('/member/clarifications', 'Api\MembersDataController@getclarifications'); // دریافت شفاف سازی سهام من
//Route::get('/member/capitalincreases', 'Api\MembersDataController@getcapitalincreases'); // دریافت افزایش سرمایه سهام من

//Route::get('/clarifications', 'Api\ClarificationController@getall'); // get all clarifications
Route::get('/continuingpaterns', 'Api\PaternsController@getContinuingPaterns'); // get all capitalincreases





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
Route::get('/getholdingdata/{id}', 'Api\MoneyReportsController@get_holding_data');
Route::get('/getholdings', 'Api\MoneyReportsController@getHoldings');
Route::get('/holding-namads', 'Api\MoneyReportsController@showHolding');

//------------------------


// filter
Route::get('/filter/{key}', 'Api\FilterController@get');

Route::get('shownamad/{id}', 'Api\NamadsController@show');

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
