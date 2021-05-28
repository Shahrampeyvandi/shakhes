<?php


use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Member\Member;

Route::any('pay', 'PayController@pay');
Route::get('pay/callback', 'PayController@callback')->name('Pay.CallBack');

Route::any('/pp', function(){
    return view('Pay.result');
});


Route::get('admin/login', 'AuthController@Login')->name('login');
Route::post('admin/login', 'AuthController@Verify')->name('login')->middleware("throttle:10,2");

Route::get('/getdata', 'RedisController@get_data1');
Route::get('/', 'MainController@home')->name('BaseUrl');
Route::get('/shakhes', 'RedisController@shakhes');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/dashboard', 'MainController@Index');
    Route::get('/moneyreports', 'MoneyReportsController@Index')->name('MoneyReports');
    Route::get('/moneyreports/add', 'MoneyReportsController@add')->name('MoneyReports.Add');
    Route::post('/moneyreports/monthly', 'MoneyReportsController@SubmitMonthly')->name('MonyReport.Monthly');
    Route::post('/moneyreports/seasonly', 'MoneyReportsController@SubmitSeasonly')->name('MonyReport.Seasonly');
    Route::get('/moneyreports/showchart/{id}', 'MoneyReportsController@ShowMonthlyChart')->name('ShowMonthlyChart');
    Route::get('/yearlyreports/showchart/{id}', 'MoneyReportsController@ShowYearlyChart')->name('ShowYearlyChart');
    Route::get('/seasonalreports/showchart/{id}', 'MoneyReportsController@ShowSeasonalChart')->name('ShowSeasonalChart');
    Route::post('/namadreports/delete', 'MoneyReportsController@Delete')->name('Reports.Delete');
    Route::get('/users', 'UsersController@Index')->name('Users');
    Route::post('/user/delete', 'UsersController@Delete')->name('Users.Delete');
    Route::post('/user/insert', 'UsersController@Save')->name('User.Insert');
    Route::put('/user/insert', 'UsersController@Edit')->name('User.Insert');
    Route::post('/user/get-data', 'UsersController@get_data');

    Route::get('/gettime', 'MainController@getTime');


    Route::get('/portfoy', 'PortfoyController@Index')->name('PortfoyList');
    Route::post('/portfoy/add-new-namad', 'PortfoyController@AddNewNamad')->name('Portfoy.AddNamad');
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
    Route::post('/volumetrades/delete', 'VolumeTradesController@Delete')->name('VolumeTrades.Delete');
    Route::get('/education/add', 'EducationController@Add')->name('Education.Add');
    Route::post('/education/add', 'EducationController@Save')->name('Education.Add');
    // Route::get('/education/edit', 'EducationController@Edit')->name('Education.Edit');
    // Route::post('/education/edit', 'EducationController@SaveEdit')->name('Education.Edit');
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

    Route::get('tickets', 'TicketController@index')->name('Panel.Tickets');

    Route::get('ticket/show', 'TicketController@show')->name('Panel.ShowTicket');
    Route::post('ticket/answer', 'TicketController@insertAnswer')->name('Panel.AnswerTicket');


});

Route::get('/education/{id}', 'EducationController@Show')->name('Education.Show');
Route::get('/farsi', 'Controller@convertPersianToEnglish');
Route::get('/d-namad/{name}',function($name){
    dd(\App\Models\Namad\Namad::search('صنعتی دریایی')->get());
//    dd(public_path('nnn.txt'));
//    $line=utf8_encode(fgets(file_get_contents(public_path('nnn.txt'))));
//    dd($line);
//    header('Content-Type: text/html; charset =utf-8');
//    $array =  mb_convert_encoding(file_get_contents(public_path('nnn.txt')), 'UTF-8',
//        mb_detect_encoding(file_get_contents(public_path('nnn.txt')), 'UTF-8, ISO-8859-1', true));
//    dd($array);

    $fh = fopen(public_path('nnn.txt'),'r');
    while ($line = fgets($fh)) {
         var_dump(utf8_encode($line));
    }
//    UPDATE namads
//SET
//    name = REPLACE(name,
//        'ك',
//        'ک');
    fclose($fh);
    dd('end');

   $namad= \App\Models\Namad\Namad::first();
    dd(Cache::get($namad->id));

});

Route::get('/sendsms',function(){


    dd(\App\Models\Namad\Namad::find(2)->users);
        $data = array('code' => '123456');
        $datas = array(
            "pattern_code" => 'e281gs93os',
            "originator" => "+9810003816",
            "recipient" => '+9899154131736',
            "values" => $data
        );

        $url = "http://rest.ippanel.com/v1/messages/patterns/send";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, json_encode($datas));
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: AccessKey LH5pTlnaCiZKZiEL7gPYh_nr-c6OmdmhRh9uKLSkkP0='
        ));

        $response = curl_exec($handler);
        dd($response);
    });



