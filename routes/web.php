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

Route::get('/login', 'AuthController@Login')->name('login');
Route::post('/login', 'AuthController@Verify')->name('login')->middleware("throttle:10,2");

    Route::get('/getdata', 'RedisController@getmain');
    Route::get('/shakhes', 'RedisController@shakhes');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/', 'MainController@Index')->name('BaseUrl');
    Route::get('/moneyreports', 'MoneyReportsController@Index')->name('MoneyReports');
    Route::post('/moneyreports/monthly', 'MoneyReportsController@SubmitMonthly')->name('MonyReport.Monthly');
    Route::post('/moneyreports/seasonly', 'MoneyReportsController@SubmitSeasonly')->name('MonyReport.Seasonly');
    Route::get('/moneyreports/showchart/{id}', 'MoneyReportsController@ShowMonthlyChart')->name('ShowMonthlyChart');
    Route::get('/yearlyreports/showchart/{id}', 'MoneyReportsController@ShowYearlyChart')->name('ShowYearlyChart');
    Route::get('/seasonalreports/showchart/{id}', 'MoneyReportsController@ShowSeasonalChart')->name('ShowSeasonalChart');
    Route::get('/namadreports/delete/{id}', 'MoneyReportsController@Delete')->name('Reports.Delete');
    Route::get('/users', 'UsersController@Index')->name('Users');
    Route::post('/user/delete', 'UsersController@Delete')->name('Users.Delete');
    Route::get('/portfoy', 'PortfoyController@Index')->name('PortfoyList');
    Route::get('/holding/create', 'PortfoyController@CreateHolding')->name('Holding.Create');
    Route::post('/holding/create', 'PortfoyController@InsertHolding')->name('Holding.Create');
    Route::post('/holding/delete', 'PortfoyController@DeleteHolding')->name('Holding.Delete');
    Route::post('/holding/namad/delete', 'PortfoyController@DeleteHoldingNamad')->name('Holding.Namad.Delete');
    Route::get('/holding/{id}/namads', 'PortfoyController@ShowNamads')->name('Holding.Namads');
    Route::get('/capitalincrease', 'CapitalIncreaseController@Index')->name('CapitalIncrease');
    Route::post('/capitalincrease', 'CapitalIncreaseController@Insert')->name('CapitalIncrease');
    Route::post('/capitalincrease/delete', 'CapitalIncreaseController@Delete')->name('CapitalIncrease.Delete');
    Route::get('/clarifications', 'ClarificationController@Index')->name('Clarifications');
    Route::get('/clarifications/create', 'ClarificationController@Create')->name('Clarification.Create');
    Route::post('/clarifications/create', 'ClarificationController@Insert')->name('Clarification.Create');
    Route::post('/clarification/delete', 'ClarificationController@Delete')->name('Clarification.Delete');
    Route::get('/continuingpaterns', 'ContinuingPaternsController@Index')->name('ContinuingPaterns');
    Route::get('/continuingpaterns/create', 'ContinuingPaternsController@Create')->name('ContinuingPaterns.Create');
    Route::post('/continuingpaterns/create', 'ContinuingPaternsController@Insert')->name('ContinuingPaterns.Create');
    Route::post('/continuingpatern/delete', 'ContinuingPaternsController@Delete')->name('ContinuingPaterns.Delete');
    Route::get('/disclosures', 'DisclosuresController@Index')->name('Disclosures');
    Route::get('/disclosures/create', 'DisclosuresController@Create')->name('Disclosures.Create');
    Route::post('/disclosures/create', 'DisclosuresController@Insert')->name('Disclosures.Create');
    Route::post('/disclosures/delete', 'DisclosuresController@Delete')->name('Disclosures.Delete');
    Route::get('/volumetrades', 'VolumeTradesController@Index')->name('VolumeTrades');
    Route::get('/education/add', 'EducationController@Add')->name('Education.Add');
    Route::post('/education/add', 'EducationController@Save')->name('Education.Add');
    Route::post('/education/delete', 'EducationController@Delete');
    Route::get('/education/list', 'EducationController@List')->name('Education.List');
    // ajax routes
    Route::post('/moneyreports/getdata', 'Ajax\MoneyReportController@getmoneyreportsdata')->name('getmoneyreportsdata');
    // Route::post('/moneyreports/getseasondata', 'AjaxController@getmoneyreportseasonaldata')->name('getmoneyreportseasonaldata');
    Route::post('/getNamadData', 'Ajax\MoneyReportController@getNamadData')->name('getNamadData');
    Route::post('panel/upload-image', 'EducationController@UploadImage')->name('UploadImage');
    Route::post('/getcapitalincreases', 'Ajax\CapitalIncreaseController@getCapitalIncreases')->name('getCapitalIncreases');

    Route::get('/saveDailyReport', 'RedisController@saveDailyReport');

    Route::get('/logout', 'AuthController@Logout')->name('logout');


    // plans
     Route::get('plans/add', 'PlanController@Add')->name('Panel.AddPlan');
    Route::post('plans/add', 'PlanController@Save')->name('Panel.AddPlan');
    Route::get('plans/{id}/edit', 'PlanController@Edit')->name('Panel.EditPlan');
    Route::put('plans/{id}/edit', 'PlanController@SaveEdit')->name('Panel.EditPlan');
    Route::get('plans/list', 'PlanController@List')->name('Panel.PlanList');
    Route::delete('plans/delete', 'PlanController@Delete')->name('Panel.DeletePlan');

 Route::get('paymants', 'PayController@list')->name('Panel.Pays');
});

    Route::get('/education/{id}', 'EducationController@Show')->name('Education.Show');

