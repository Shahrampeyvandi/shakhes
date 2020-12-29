<?php

namespace App\Http\Schedules;

use App\Http\Controllers\Controller;
use App\Models\Holding\Holding;
use App\Models\Member\Member;
use Goutte;
use Exception;
use Carbon\Carbon;
use App\MovingAverage;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Namad\NamadsDailyReport;

class ApiScheduler extends Controller
{


    public function __invoke()
    {

        $bstatclose = false;

        if (Cache::has('bazarstatus')) {
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



        $namads = [];
        if(Cache::get('bazarstatus') !== 'close') {

        
            if (Cache::has('namadlist')) {
                $namads = Cache::get('namadlist');
            } else {
                $namads = Namad::all();
                Cache::store()->put('namadlist', $namads, 86400); // 10 Minutes
            }

            foreach ($namads as $namad) {

                try {
                    $this->saveDailyReport($namad);
                } catch (Exception $e) {
                }
            }

        }
        
        
    }

    public function saveDailyReport($namad)
    {

        $m = Member::find(3);
        $m->fname = date('H:i');
        $m->save();

        $inscode = $namad->inscode;
        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
        $all = \strip_tags($crawler->html());
        $array = [];
        $array['symbol'] = $namad->symbol;
        $array['name'] = $namad->name;
        $explode_all = explode(';', $all);
        $main_data = $explode_all[0];
        $buy_sell = $explode_all[4];
        $orders = $explode_all[2];
        $dailyReport = new NamadsDailyReport;
        $dailyReport->namad_id = $namad->id;
        $array['namad_status'] = trim(explode(',', $main_data)[1]);

        $dailyReport->lastsells = isset($array['lastsells']) ? serialize($array['lastsells']) : '';
        $data['personbuy'] = $buy_sell ?  explode(',', $buy_sell)[0] : 0;
        $data['legalbuy'] = $buy_sell ? explode(',', $buy_sell)[1] : 0;
        $data['personsell'] = $buy_sell ? explode(',', $buy_sell)[3] : 0;
        $data['legalsell'] = $buy_sell ? explode(',', $buy_sell)[4] : 0;
        $data['personbuycount'] = $buy_sell ? explode(',', $buy_sell)[5] : 0;
        $data['legalbuycount'] = $buy_sell ? explode(',', $buy_sell)[6] : 0;
        $data['personsellcount'] = $buy_sell ? explode(',', $buy_sell)[8] : 0;
        $data['legalsellcount'] = $buy_sell ? explode(',', $buy_sell)[9] : 0;

        $array['N_personbuy'] = $data['personbuy'];
        $array['N_legalbuy'] = $data['legalbuy'];
        $array['N_personsell'] = $data['personsell'];
        $array['N_legalsell'] = $data['legalsell'];


        foreach ($data as $key => $item) {
            $array[$key] = $this->format((int)$item);
        }


        if ($data['personbuy'] &&  $data['personbuycount'] &&  $data['personsell'] && $data['personsellcount']) {
            $array['person_buy_power'] = number_format((float)(($data['personbuy'] / $data['personbuycount']) / (($data['personbuy'] / $data['personbuycount']) + ($data['personsell'] / $data['personsellcount']))), 2, '.', '') * 100;
            $array['person_sell_power'] = number_format((float)(100 - $array['person_buy_power']), 0, '.', '');
        } else {
            $array['person_buy_power'] = 0;
            $array['person_sell_power'] = 0;
        }

        $totalbuy = $buy_sell ? explode(',', $buy_sell)[0] + explode(',', $buy_sell)[1] : 0;
        $totalsell = $buy_sell ? explode(',', $buy_sell)[3] + explode(',', $buy_sell)[4] : 0;

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
        $array['tradeCount'] = explode(',', $main_data)[8];
        $array['N_tradeVol'] =  explode(',', $main_data)[9];
        $tradeVOL = explode(',', $main_data)[9];

        $array['pmin_change_percent'] = isset($array['pmin']) && $array['py'] !== 0 ?  strval(abs(number_format((float)(($array['pmin'] - $array['py']) * 100) / $array['py'], 2, '.', ''))) : '';
        $array['pmax_change_percent'] = isset($array['pmax']) && $array['py'] !== 0 ?  strval(abs(number_format((float)(($array['pmax'] - $array['py']) * 100) / $array['py'], 2, '.', ''))) : '';

        if ($orders) {
            $explode_orders = explode('@', $orders);
            $explode_orders[1] = $this->format((int) $explode_orders[1]);
            $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2], 'color' => $explode_orders[2] < $array['pmin'] ? 'gray' : 'black');
            $explode_orders[6] = $this->format((int) $explode_orders[6]);
            $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7], 'color' => $explode_orders[7] < $array['pmin'] ? 'gray' : 'black');
            $explode_orders[11] = $this->format((int) $explode_orders[11]);
            $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12], 'color' => $explode_orders[12] < $array['pmin'] ? 'gray' : 'black');
            $dailyReport->lastbuys = serialize($array['lastbuys']);
            $explode_orders[4] = $this->format((int) $explode_orders[4]);
            $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3], 'color' => $explode_orders[3] > $array['pmax'] ? 'gray' : 'black');
            $explode_orders[9] = $this->format((int) $explode_orders[9]);
            $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8], 'color' => $explode_orders[8] > $array['pmax'] ? 'gray' : 'black');
            $explode_orders[14] = $this->format((int) $explode_orders[14]);
            $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13], 'color' => $explode_orders[13] > $array['pmax'] ? 'gray' : 'black');
        }



        $array['tradeVol'] = $this->format((int)$tradeVOL);


        $tradeCASH = explode(',', $main_data)[10];
        $array['N_tradeCash'] = $tradeCASH;


        $array['tradeCash'] = $this->format((int)$tradeCASH);


        if ($array['pl'] && $array['py']) {

            $array['status'] =  ($array['pl'] - $array['py'])  > 0 ? 'green' : 'red';
        } else {
            $array['status'] = null;
        }
        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];

        $dailyReport->tradevol = $array['tradeVol'];
        $dailyReport->tradecash = $array['tradeCash'];


        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=' . $inscode . '');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);

        preg_match('/=\'?(\d+)/',  \explode(',', $all)[34], $matches);
        $array['maxRange'] =  count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/',  \explode(',', $all)[35], $matches);
        $array['minRange'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['baseVol'] =  count($matches) ? $matches[1] : '';

        preg_match('/=\'?(\d+)/', $explode[28], $matches);
        $array['tedadSaham'] =  count($matches) ? $matches[1] : '';




        if ($array['tedadSaham'] && $array['tedadSaham'] !== '' && $array['pl']) {
            $array['marketCash'] = $array['tedadSaham'] * $array['pl'];
            $array['N_marketCash'] = $array['marketCash'];

            $array['marketCash'] = $this->format((int)$array['marketCash']);
        }


        if ($array['tedadSaham'] && $array['tedadSaham'] !== '') {

            $array['tedadSaham'] = $this->format((int)$array['tedadSaham']);
        }


        preg_match('/\'?(-?\d+)/', $explode[27], $matches);
        $array['EPS'] = count($matches) ? $matches[1] : '';
        $array['P/E'] = isset($array['EPS']) && $array['EPS'] ? number_format(($array['pc'] / $array['EPS']), 2, '.', '') : '';
        preg_match('/=\'?(\d+)/', $explode[38], $matches);
        $array['minWeek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[39], $matches);
        $array['maxWeek'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[42], $matches);
        $array['N_monthAVG'] = count($matches) ? $matches[1] : '';


        if ($array['N_monthAVG']) {


            $array['monthAVG'] = $this->format($array['N_monthAVG']);
        }

        preg_match('/\'?(\d+)/', $explode[43], $matches);
        $array['groupPE'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[44], $matches);
        $array['sahamShenavar'] = count($matches) ? $matches[1] : '';

        $array['pc_change_percent'] = isset($array['py']) && $array['py'] !== 0 ?  abs(number_format((float)(($array['pc'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';
        $array['pf_change_percent'] = isset($array['pf']) && $array['py'] !== 0 ?  abs(number_format((float)(($array['pf'] - $array['py']) * 100) / $array['py'], 2, '.', '')) : '';

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


        $dailyReport->pmax = isset($array['pmax']) ? $array['pmax'] : '';
        $dailyReport->pmin = isset($array['pmin']) ? $array['pmin'] : '';
        $dailyReport->BaseVol = isset($array['baseVol']) ? $array['baseVol'] : '';
        $dailyReport->EPS = isset($array['EPS']) ? $array['EPS'] : '';
        $dailyReport->minweek = isset($array['minWeek']) ? $array['minWeek'] : '';
        $dailyReport->maxweek = isset($array['maxWeek']) ? $array['maxWeek'] : '';
        $dailyReport->monthAVG = isset($array['monthAVG']) ? $array['monthAVG'] : '';
        $dailyReport->groupPE = isset($array['groupPE']) ? $array['groupPE'] : '';
        $dailyReport->sahamShenavar = isset($array['sahamShenavar']) ? $array['sahamShenavar'] : '';


        if (((int)$array['N_tradeVol'] > (int)$array['N_monthAVG'])) {
            $zarib =   (float)((int)$array['N_tradeVol'] / (int)$array['N_monthAVG']);
            if ($zarib > 4) {
                if (VolumeTrade::check($namad->id)) {
                    VolumeTrade::create([
                        'namad_id' => $namad->id,
                        'trade_vol' => $array['N_tradeVol'],
                        'month_avg' => $array['N_monthAVG'],
                        'volume_ratio' => $zarib
                    ]);
                } else {
                    VolumeTrade::where('namad_id', $namad->id)
                        ->whereDate('created_at', Carbon::today())
                        ->update([
                            'trade_vol' => $array['N_tradeVol'],
                            'month_avg' => $array['N_monthAVG'],
                            'volume_ratio' => $zarib
                        ]);
                }
            }
        }


        // echo $array['pl'] . '<br/>';
        // echo ($array['pl'] - ($array['pl'] * 5) / 100) . ' ';
        // $days = 100;

        // $url = 'http://www.tsetmc.com/tsev2/data/InstTradeHistory.aspx?i=' . $inscode . '&Top=' . $days . '&A=1';
        // $crawler = Goutte::request('GET', $url);
        // $history = \strip_tags($crawler->html());
        // $explode_history = explode(';', $history);
        // foreach ($explode_history as $key => $row) {
        //     (int)$last_price_day = isset(explode('@', $row)[4]) ? explode('@', $row)[4] : '';
        //     $array[] = (int)$last_price_day;
        // }
        // $count =  count($array);
        // $sum = array_sum($array);

        //  $avg = number_format((float)($sum / $count), 2, '.', '');

        // $five_percent = ($array['pl'] * 5) / 100;
        // $min_check = $array['pl'] - $five_percent;
        // $max_check = $array['pl'] + $five_percent;

        // if ($avg > $min_check && $avg < $max_check) {
        //     $oneDayAgo = $array[0];
        //     $twoDayAgo = $array[1];
        //     $threeDayAgo = $array[2];
        //     if($avg > $oneDayAgo && $avg > $twoDayAgo && $avg >  $threeDayAgo ){
        //         MovingAverage::create([
        //             'namad_id' => $namad->id,
        //             'symbol' => $namad->symbol,
        //             'avg' => $avg ,
        //             'status' => 'moghavemat',
        //             'days' => $days
        //         ]);
        //     }
        //      if($avg < $oneDayAgo && $avg < $twoDayAgo && $avg <  $threeDayAgo ){
        //         MovingAverage::create([
        //             'namad_id' => $namad->id,
        //             'symbol' => $namad->symbol,
        //             'avg' => $avg ,
        //             'status' => 'hemayat',
        //             'days' => $days
        //         ]);
        //     }
        // }


        // filter calculate

        if ($buy_sell) {

            $array['filter']['person_most_buy_sell'] = $data['personbuycount'] > 0 && $data['personsellcount'] ? (float)($array['N_personbuy'] / $data['personbuycount']) / (float)($array['N_personsell'] / $data['personsellcount']) : 0;
            $array['filter']['person_most_sell_buy'] = $data['personbuycount'] > 0 && $data['personsellcount'] ? (float)($array['N_personsell'] / $data['personsellcount']) / (float)($array['N_personbuy'] / $data['personbuycount']) : 0;
            $array['filter']['legal_most_buy_sell'] = $data['legalbuycount'] > 0 && $data['legalsellcount'] ? (float)($array['N_legalbuy'] / $data['legalbuycount']) / (float)($array['N_legalsell'] / $data['legalsellcount']) : 0;
            $array['filter']['legal_most_sell_buy'] = $data['legalbuycount'] > 0 && $data['legalsellcount'] ? (float)($array['N_legalsell'] / $data['legalsellcount']) / (float)($array['N_legalbuy'] / $data['legalbuycount']) : 0;
            $array['filter']['person_buy_avg'] = $data['personbuycount'] > 0  && $data['legalbuycount'] > 0 ?   $array['N_personbuy'] /   (float)($data['personbuycount'] +  $data['legalbuycount']) : 0;
            $array['filter']['person_sell_avg'] = $data['personsellcount'] > 0  && $data['legalsellcount'] > 0 ? $array['N_personsell'] /   (float)($data['personsellcount'] +  $data['legalsellcount']) : 0;
            if ($array['pc'] && isset($array['N_personbuy']) && isset($array['N_personsell'])) {
                $array['filter']['power_person_buy'] = $data['personbuycount'] > 0  ? ((int)$array['N_personbuy'] /  $data['personbuycount']) * $array['pc'] : 0;
                $array['filter']['power_person_sell'] = $data['personsellcount'] > 0  ? (int)$array['N_personsell'] / $data['personsellcount'] * $array['pc'] : 0;
            }
        }
        Cache::store()->put($namad->id, $array, 10000000); // 10 Minutes

        echo 'pomad = ' . $namad->symbol . PHP_EOL;

        //$dailyReport->();


    }
}
