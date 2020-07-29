<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Goutte;

class ApiScheduler
{


    public function __invoke()
    {

        $namads = Namad::all();

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
        $array['monthAVG'] = count($matches) ? $matches[1] : '';

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

        Cache::store()->put($namad->id, $array, 10000000); // 10 Minutes

        echo 'pomad = ' . $namad->symbol . PHP_EOL;

        //$dailyReport->save();

    }
}
