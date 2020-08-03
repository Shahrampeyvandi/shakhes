<?php

namespace App\Http\Controllers;

use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Goutte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Models\VolumeTrade;
use Carbon\Carbon;
use Exception;

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
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', "http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");

        // $data = explode(',', explode(';', explode('@', $response->getBody())[2])[222])[2];

        // // return $data;

        // $inscode = [
        //     '32097828799138957' => 'شاخص کل',
        //     '5798407779416661' => 'شاخص قیمت',
        //     '67130298613737946' => 'شاخص کل(هم وزن)',
        //     '8384385859414435' => 'شاخص قیمت(هم وزن)',
        //     '49579049405614711' => 'شاخص آزاد شناور',
        //     '62752761908615603' => 'شاخص بازار اول',
        //     '71704845530629737' => 'شاخص بازار دوم',
        // ];

        $namads = Namad::all();
       $this->saveDailyReport(Namad::find(248));

         foreach ($namads as $namad) {
            try {
                $this->saveDailyReport($namad);
            } catch (Exception $e) {
            }
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
        if ((int) $explode_orders[1] > 1000000 && (int) $explode_orders[1] < 1000000000) {
            $explode_orders[1] = number_format((int) $explode_orders[1] / 1000000, 2) . "M";
        } elseif ((int) $explode_orders[1] > 1000000000) {
            $explode_orders[1] = number_format((int) $explode_orders[1] / 1000000000, 1) . "B";
        } else {
            $explode_orders[1] = (int) $explode_orders[1];
        }
        $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
        if ((int) $explode_orders[6] > 1000000 && (int) $explode_orders[6] < 1000000000) {
            $explode_orders[6] = number_format((int) $explode_orders[6] / 1000000, 2) . "M";
        } elseif ((int) $explode_orders[6] > 1000000000) {
            $explode_orders[6] = number_format((int) $explode_orders[6] / 1000000000, 1) . "B";
        } else {
            $explode_orders[6] = (int) $explode_orders[6];
        }
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
        if ((int) $explode_orders[11] > 1000000 && (int) $explode_orders[11] < 1000000000) {
            $explode_orders[11] = number_format((int) $explode_orders[11] / 1000000, 2) . "M";
        } elseif ((int) $explode_orders[11] > 1000000000) {
            $explode_orders[11] = number_format((int) $explode_orders[11] / 1000000000, 1) . "B";
        } else {
            $explode_orders[11] = (int) $explode_orders[11];
        }

        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);

        $dailyReport->lastbuys = serialize($array['lastbuys']);
        if ((int) $explode_orders[4] > 1000000 && (int) $explode_orders[4] < 1000000000) {
            $explode_orders[4] = number_format((int) $explode_orders[4] / 1000000, 2) . "M";
        } elseif ((int) $explode_orders[4] > 1000000000) {
            $explode_orders[4] = number_format((int) $explode_orders[4] / 1000000000, 1) . "B";
        } else {
            $explode_orders[4] = (int) $explode_orders[4];
        }
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
        if ((int) $explode_orders[9] > 1000000 && (int) $explode_orders[9] < 1000000000) {
            $explode_orders[9] = number_format((int) $explode_orders[9] / 1000000, 2) . "M";
        } elseif ((int) $explode_orders[9] > 1000000000) {
            $explode_orders[9] = number_format((int) $explode_orders[9] / 1000000000, 1) . "B";
        } else {
            $explode_orders[9] = (int) $explode_orders[9];
        }
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
        if ((int) $explode_orders[14] > 1000000 && (int) $explode_orders[14] < 1000000000) {
            $explode_orders[14] = number_format((int) $explode_orders[14] / 1000000, 2) . "M";
        } elseif ((int) $explode_orders[14] > 1000000000) {
            $explode_orders[14] = number_format((int) $explode_orders[14] / 1000000000, 1) . "B";
        } else {
            $explode_orders[14] = (int) $explode_orders[14];
        }
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);
        $dailyReport->lastsells = serialize($array['lastsells']);
        $data['personbuy'] = explode(',', $buy_sell)[0];
        $data['legalbuy'] = explode(',', $buy_sell)[1];
        $data['personsell'] = explode(',', $buy_sell)[3];
        $data['legalsell'] = explode(',', $buy_sell)[4];
        $data['personbuycount'] = explode(',', $buy_sell)[5];
        $data['legalbuycount'] = explode(',', $buy_sell)[6];
        $data['personsellcount'] = explode(',', $buy_sell)[8];
        $data['legalsellcount'] = explode(',', $buy_sell)[9];

        foreach ($data as $key => $item) {
            if ((int)$item > 1000000 && (int)$item < 1000000000) {
                $array[$key] = number_format((int)$item / 1000000, 1) . "M";
            } elseif ((int)$item > 1000000000) {
                $array[$key] = number_format((int)$item / 1000000000, 1) . "B";
            } else {
                $array[$key] = (int)$item;
            }
        }

        if ($data['personbuy'] &&  $data['personbuycount'] &&  $data['personsell'] && $data['personsellcount']) {
            $array['person_buy_power'] = number_format((float)(($data['personbuy'] / $data['personbuycount']) / (($data['personbuy'] / $data['personbuycount']) + ($data['personsell'] / $data['personsellcount']))), 2, '.', '') * 100;
            $array['person_sell_power'] = number_format((float)(100 - $array['person_buy_power']), 0, '.', '');
        } else {
            $array['person_buy_power'] = 0;
            $array['person_sell_power'] = 0;
        }

        $totalbuy =  explode(',', $buy_sell)[0] + explode(',', $buy_sell)[1];
        $totalsell =  explode(',', $buy_sell)[3] + explode(',', $buy_sell)[4];

        if ($totalbuy && $buy_sell) {
            $array['percent_person_buy'] = number_format((float)((explode(',', $buy_sell)[0] * 100) / $totalbuy), 0, '.', '');
        } else {
            $array['percent_person_buy'] = 0;
        }
        if ($totalbuy && $buy_sell) {
            $array['percent_legal_buy'] = number_format((float)((explode(',', $buy_sell)[1] * 100) / $totalbuy), 0, '.', '');
        } else {
            $array['percent_legal_buy'] = 0;
        }

        if ($totalsell && $buy_sell) {
            $array['percent_person_sell'] = number_format((float)((explode(',', $buy_sell)[3] * 100) / $totalsell), 0, '.', '');
        } else {
            $array['percent_person_sell'] = 0;
        }
        if ($totalsell && $buy_sell) {
            $array['percent_legal_sell'] = number_format((float)((explode(',', $buy_sell)[4] * 100) / $totalsell), 0, '.', '');
        } else {

            $array['percent_legal_sell'] = 0;
        }


        $dailyReport->personbuy = $array['personbuy'];
        $dailyReport->legalbuy = $array['legalbuy'];
        $dailyReport->personsell = $array['personsell'];
        $dailyReport->legalsell = $array['legalsell'];
        $dailyReport->personbuycount = $array['personbuycount'];
        $dailyReport->legalbuycount = $array['legalbuycount'];
        $dailyReport->personsellcount = $array['personsellcount'];
        $dailyReport->legalsellcount = $array['legalsellcount'];

        $array['time']  = explode(',', $main_data)[0];
        $array['pl'] = explode(',', $main_data)[2];
        $array['pc'] = explode(',', $main_data)[3];
        $array['pf'] = explode(',', $main_data)[4];
        $array['py'] = explode(',', $main_data)[5];
        $array['pmin'] = explode(',', $main_data)[7];
        $array['pmax'] = explode(',', $main_data)[6];
        $array['tradecount'] = explode(',', $main_data)[8];
        $array['N_tradeVol'] =  explode(',', $main_data)[9];
        $tradeVOL = explode(',', $main_data)[9];
        if ((int)$tradeVOL > 1000000 && (int)$tradeVOL < 1000000000) {
            $array['tradevol'] = number_format((int)$tradeVOL / 1000000, 1) . "M";
        } elseif ((int)$tradeVOL > 1000000000) {
            $array['tradevol'] = number_format((int)explode(',', $main_data)[10] / 1000000000, 1) . "B";
        } else {
            $array['tradevol'] = (int)$tradeVOL;
        }

        $tradeCASH = explode(',', $main_data)[10];
        if ((int)$tradeCASH > 1000000 && (int)$tradeCASH < 1000000000) {
            $array['tradecash'] =  number_format((int)$tradeCASH / 1000000, 1) . "M";
        } elseif ((int)$tradeCASH > 1000000000) {

            $array['tradecash'] =  number_format((int)$tradeCASH / 1000000000, 1) . "B";
        } else {
            $array['tradecash'] =  (int)$tradeCASH;
        }

        if ($array['pl'] && $array['py']) {

            $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
        } else {
            $array['status'] = null;
        }
        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];

        $dailyReport->tradevol = $array['tradevol'];
        $dailyReport->tradecash = $array['tradecash'];


        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=' . $inscode . '');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);

        preg_match('/=\'?(\d+)/',  \explode(',', $all)[34], $matches);
        $array['maxrange'] =  count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/',  \explode(',', $all)[35], $matches);
        $array['minrange'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['BaseVol'] =  count($matches) ? $matches[1] : '';

        preg_match('/=\'?(\d+)/', $explode[28], $matches);
        $array['TedadShaham'] =  count($matches) ? $matches[1] : '';







        if ($array['TedadShaham'] && $array['TedadShaham'] !== '' && $array['pl']) {
            $array['MarketCash'] = $array['TedadShaham'] * $array['pl'];
            if ((int)$array['MarketCash'] > 1000000 && (int)$array['MarketCash'] < 1000000000) {
                $array['MarketCash'] =  number_format((int)$array['MarketCash'] / 1000000, 1) . "M";
            } elseif ((int)$array['MarketCash'] > 1000000000) {

                $array['MarketCash'] =  number_format((int)$array['MarketCash'] / 1000000000, 1) . "B";
            } else {
                $array['MarketCash'] =  (int)$array['MarketCash'];
            }
        }


        if ($array['TedadShaham'] && $array['TedadShaham'] !== '') {

            if ((int)$array['TedadShaham'] > 1000000 && (int)$array['TedadShaham'] < 1000000000) {
                $array['TedadShaham'] =  number_format((int)$array['TedadShaham'] / 1000000, 1) . "M";
            } elseif ((int)$array['TedadShaham'] > 1000000000) {

                $array['TedadShaham'] =  number_format((int)$array['TedadShaham'] / 1000000000, 1) . "B";
            } else {
                $array['TedadShaham'] =  (int)$array['TedadShaham'];
            }
        }


        preg_match('/\'?(\d+)/', $explode[27], $matches);
        $array['EPS'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[38], $matches);
        $array['minweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[39], $matches);
        $array['maxweek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[42], $matches);
        $array['N_monthAVG'] = count($matches) ? $matches[1] : '';


        if ($array['N_monthAVG']) {

            if ((int)$array['N_monthAVG'] > 1000000 && (int)$array['N_monthAVG'] < 1000000000) {
                $array['monthAVG'] =  number_format((int)$array['N_monthAVG'] / 1000000, 1) . "M";
            } elseif ((int)$array['N_monthAVG'] > 1000000000) {

                $array['monthAVG'] =  number_format((int)$array['N_monthAVG'] / 1000000000, 1) . "B";
            } else {
                $array['monthAVG'] =  (int)$array['N_monthAVG'];
            }
        }

        preg_match('/\'?(\d+)/', $explode[43], $matches);
        $array['groupPE'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[44], $matches);
        $array['sahamShenavar'] = count($matches) ? $matches[1] : '';

        if (isset($array['pl']) && isset($array['py'])) {
            $array['final_price_value'] = $array['pl'];
            $array['final_price_percent'] = $array['py'] ?  abs(number_format((float)(($array['pl'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';
            $array['last_price_change'] = abs($array['pl'] - $array['py']);
            $array['last_price_status'] = ($array['pl'] - $array['py']) > 0 ? '1' : '0';
        } else {
            $array['final_price_value'] = '0';
            $array['final_price_percent'] = '0';
            $array['last_price_change'] = '0';
            $array['last_price_status'] = '0';
        }

        $dailyReport->pmax = $array['pmax'];
        $dailyReport->pmin = $array['pmin'];
        $dailyReport->BaseVol = $array['BaseVol'];
        $dailyReport->EPS = $array['EPS'];
        $dailyReport->minweek = $array['minweek'];
        $dailyReport->maxweek = $array['maxweek'];
        $dailyReport->monthAVG = $array['monthAVG'];
        $dailyReport->groupPE = $array['groupPE'];
        $dailyReport->sahamShenavar = $array['sahamShenavar'];


        $start = Carbon::parse('09:00')->timestamp;
        $end = Carbon::parse('18:30')->timestamp;
        //$time = Carbon::parse($array['time'])->timestamp;
        $time = Carbon::now()->timestamp;

        //dd([Carbon::now()->timestamp,$start,$end,$time]);


        if (($time > $start) && ($time < $end) &&  ((int)$array['N_tradeVol'] > (int)$array['N_monthAVG'])) {

            $zarib =   (int)$array['N_tradeVol'] / (int)$array['N_monthAVG'];
            if ($zarib > 4 && VolumeTrade::check($namad->id)) {
               // dd($namad->id);

                VolumeTrade::create(['namad_id' => $namad->id, 'trade_vol' => $array['N_tradeVol'], 'month_avg' => $array['N_monthAVG'], 'volume_ratio' => $zarib]);
            }
        }

        dd($dailyReport);

        Cache::store()->put($namad->id, $array, 10000000); // 10 Minutes

        echo 'pomad = ' . $namad->symbol . PHP_EOL;

        //$dailyReport->save();

    }
}
