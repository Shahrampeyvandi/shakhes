<?php

namespace App\Http\Controllers;

use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Goutte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function getmain()
    {
        //$redis = Redis::connection();
        $values = Redis::command('keys', ['*']);
        // dd($values);

        // foreach($values as $vla){
        //     $data = Redis::hgetall($vla);
        //     $data = json_decode(end($data), true);

        //     //dd($data);
        //     echo $data['l30'];
        //     echo '</br>';

        // }
        $allmarket = Redis::hgetall('IRB3TB630091')['13:42'];
        //$user = json_decode(end($user), true);
        return $allmarket;
        $all = [];
        foreach ($allmarket as $key => $item) {
            $item = [];
        }
        // echo $user;

    }

    public function shakhes()
    {
       
        // $value = Cache::get('778253364357513');
        // dd($value);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");

        $data = explode(',', explode(';', explode('@', $response->getBody())[2])[222])[2];

        return $data;

        $inscode = [
            '32097828799138957' => 'شاخص کل',
            '5798407779416661' => 'شاخص قیمت',
            '67130298613737946' => 'شاخص کل(هم وزن)',
            '8384385859414435' => 'شاخص قیمت(هم وزن)',
            '49579049405614711' => 'شاخص آزاد شناور',
            '62752761908615603' => 'شاخص بازار اول',
            '71704845530629737' => 'شاخص بازار دوم',
        ];

        $namads = Namad::all();

        foreach ($namads as $namad) {

            $this->saveDailyReport($namad);
        }
    }

    public function saveDailyReport($namad)
    {

        $inscode = $namad->inscode;
        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
        $all = \strip_tags($crawler->html());
        $explode_all = explode(';', $all);
        $main_data = $explode_all[0];
        $buy_sell = $explode_all[4];
        $orders = $explode_all[2];

        $dailyReport = new NamadsDailyReport;
        $dailyReport->namad_id = $namad->id;

        $explode_orders = explode('@', $orders);
        $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);

        $dailyReport->lastbuys = serialize($array['lastbuys']);

        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);

        $dailyReport->lastsells = serialize($array['lastsells']);

        $array['personbuy'] = explode(',', $buy_sell)[0];
        $array['legalbuy'] = explode(',', $buy_sell)[1];
        $array['personsell'] = explode(',', $buy_sell)[3];
        $array['legalsell'] = explode(',', $buy_sell)[4];
        $array['personbuycount'] = explode(',', $buy_sell)[5];
        $array['legalbuycount'] = explode(',', $buy_sell)[6];
        $array['personsellcount'] = explode(',', $buy_sell)[8];
        $array['legalsellcount'] = explode(',', $buy_sell)[9];

        $dailyReport->personbuy = $array['personbuy'];
        $dailyReport->legalbuy = $array['legalbuy'];
        $dailyReport->personsell = $array['personsell'];
        $dailyReport->legalsell = $array['legalsell'];
        $dailyReport->personbuycount = $array['personbuycount'];
        $dailyReport->legalbuycount = $array['legalbuycount'];
        $dailyReport->personsellcount = $array['personsellcount'];
        $dailyReport->legalsellcount = $array['legalsellcount'];

        $array['pl'] = explode(',', $main_data)[2];
        $array['pc'] = explode(',', $main_data)[3];
        $array['pf'] = explode(',', $main_data)[4];
        $array['py'] = explode(',', $main_data)[5];
        $array['pmax'] = explode(',', $main_data)[6];
        $array['pmin'] = explode(',', $main_data)[7];
        $array['pmin'] = explode(',', $main_data)[8];
        $array['tradevol'] = explode(',', $main_data)[9];
        $array['tradecash'] = explode(',', $main_data)[10];

        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];
        $dailyReport->pmax = $array['pmax'];
        $dailyReport->pmin = $array['pmin'];
        $dailyReport->tradevol = $array['tradevol'];
        $dailyReport->tradecash = $array['tradecash'];

        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=' . $inscode . '');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);

        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['BaseVol'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[27], $matches);
        $array['EPS'] = count($matches) ? $matches[1] : '';

        preg_match('/=\'?(\d+)/', $explode[38], $matches);
        $array['minweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[39], $matches);
        $array['maxweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[42], $matches);
        $array['monthAVG'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[43], $matches);
        $array['groupPE'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[44], $matches);
        $array['sahamShenavar'] = count($matches) ? $matches[1] : '';

        $dailyReport->BaseVol = $array['BaseVol'];
        $dailyReport->EPS = $array['EPS'];
        $dailyReport->minweek = $array['minweek'];
        $dailyReport->maxweek = $array['maxweek'];
        $dailyReport->monthAVG = $array['monthAVG'];
        $dailyReport->groupPE = $array['groupPE'];
        $dailyReport->sahamShenavar = $array['sahamShenavar'];

        Cache::store()->put($namad->inscode, $array, 600); // 10 Minutes

        //$dailyReport->save();

        dd($array);
    }

}
