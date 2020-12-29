<?php


use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Member\Member;

Route::get('admin/login', 'AuthController@Login')->name('login');
Route::post('admin/login', 'AuthController@Verify')->name('login')->middleware("throttle:10,2");

Route::get('/getdata', 'RedisController@getmain');
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

Route::get('pay', 'PayController@pay')->middleware('jwt.verify');
Route::get('pay/callback', 'PayController@callback')->middleware('jwt.verify');

Route::get('/education/{id}', 'EducationController@Show')->name('Education.Show');
Route::get('/farsi', 'Controller@convertPersianToEnglish');
Route::get('/test-curl', function (Request $request) {
    $number = 132004344;
    if ($number > 0 &&  $number < 1000000) {
            return number_format($number);
        } elseif ($number > 1000000 &&  $number < 1000000000) {
           $number = number_format($number / 1000000,2,'.','') + 0;
            return $number = number_format($number, 2) . "M";
        } elseif ($number > 1000000000) {
           $number =  number_format($number / 1000000000,2,'.','') + 0;
            return  $number = number_format($number, 2) . "B";
            
        }
        $m = Member::find(3);
        $m->fname = 'fast '. date('H:i');
        $m->save();
        dd('d');
   
      if (Cache::has('bazarstatus')) {
          dd(Cache::get('bazarstatus'));
            echo 'cache has bazar status = ' . PHP_EOL;
            $status = Cache::get('bazarstatus');
            if ($status == 'close') {

                // echo ' cache is close= ' . PHP_EOL;
                echo ' cache show bazar is close= ' . PHP_EOL;
                $bstatclose = true;
                return;
            }
        } else {
            
            echo 'cache is empty = ' . PHP_EOL;
            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=15');
            $all = [];
            $crawler->filter('table')->each(function ($node) use (&$bstatclose) {
                $status = $node->filter('tr:nth-of-type(1)')->text();
                if (preg_match('/بسته/', $status)) {
                    echo 'bazar baste ast = ' . PHP_EOL;
                    Cache::store()->put('bazarstatus', 'close', 60 * 11); // 11 Minutes

                    $bstatclose = true;
                
                    return;
                }
            });
        }
    // dd(Cache::get('bazarstatus'));
        $m = Member::find(3);
        $m->fname = date('H:i');
        $m->save();
        
   dd(Cache::get('holding-5'));
    do {
        try {
            $status = false;
            $ch = curl_init("http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");
            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            $result = curl_exec($ch);

            $datas = explode(';', $result);
        } catch (\Throwable $th) {
            echo 'has error';
            $status = true;
            sleep(1);
        }
    } while ($status);


    $newdata = [];
    foreach ($datas as $key => $row) {




        $dd = explode(',', $datas[$key]);
        if (count($dd) == 23) {
            $persian = ['ي', 'ك'];
            $english = ['ی', 'ک'];
            $output = str_replace($persian, $english, $dd[2]);
            dump($dd);
         


            $newdata[] = [
                $dd[2],


            ];
        }
    }

    return $newdata;
});
