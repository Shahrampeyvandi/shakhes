<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Goutte;

class DailyReportScheduler
{


    public function __invoke()
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', "http://www.tsetmc.com/tsev2/data/MarketWatchInit.aspx?h=0&r=0");

        $datas = explode(';', explode('@', $response->getBody())[2]);

        foreach ($datas as $data) {
            try {
                $datafor = explode(',', $data);
                $this->saveDailyReport($datafor);
            } catch (Exception $e) {
            }
        }
    }

    public function saveDailyReport($data)
    {

        $inscode = $data[0];


        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
        $all = \strip_tags($crawler->html());

        $explode_all = explode(';', $all);
        $main_data = $explode_all[0];
        $buy_sell = $explode_all[4];
        $orders = $explode_all[2];



        $explode_orders = explode('@', $orders);
        $array['lastbuys'][] = array('tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
        $array['lastbuys'][] = array('tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);

        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
        $array['lastsells'][] = array('tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);


        $array['personbuy'] = explode(',', $buy_sell)[0];
        $array['legalbuy'] = explode(',', $buy_sell)[1];
        $array['personsell'] = explode(',', $buy_sell)[3];
        $array['legalsell'] = explode(',', $buy_sell)[4];
        $array['personbuycount'] = explode(',', $buy_sell)[5];
        $array['legalbuycount'] = explode(',', $buy_sell)[6];
        $array['personsellcount'] = explode(',', $buy_sell)[8];
        $array['legalsellcount'] = explode(',', $buy_sell)[9];





        $array['pl'] = explode(',', $main_data)[2];
        $array['pc'] = explode(',', $main_data)[3];
        $array['pf'] = explode(',', $main_data)[4];
        $array['py'] = explode(',', $main_data)[5];
        $array['pmax'] = explode(',', $main_data)[6];
        $array['pmin'] = explode(',', $main_data)[7];
        $array['pmin'] = explode(',', $main_data)[8];
        $array['tradevol'] = explode(',', $main_data)[9];
        $array['tradecash'] = explode(',', $main_data)[10];



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

        $dailyReport = new NamadsDailyReport;
        $dailyReport->lastbuys = serialize($array['lastbuys']);
        $dailyReport->lastsells = serialize($array['lastsells']);
        $dailyReport->personbuy = $array['personbuy'];
        $dailyReport->legalbuy = $array['legalbuy'];
        $dailyReport->personsell = $array['personsell'];
        $dailyReport->legalsell = $array['legalsell'];
        $dailyReport->personbuycount = $array['personbuycount'];
        $dailyReport->legalbuycount = $array['legalbuycount'];
        $dailyReport->personsellcount = $array['personsellcount'];
        $dailyReport->legalsellcount = $array['legalsellcount'];
        $dailyReport->pl = $array['pl'];
        $dailyReport->pc = $array['pc'];
        $dailyReport->pf = $array['pf'];
        $dailyReport->py = $array['py'];
        $dailyReport->pmax = $array['pmax'];
        $dailyReport->pmin = $array['pmin'];
        $dailyReport->tradevol = $array['tradevol'];
        $dailyReport->tradecash = $array['tradecash'];
        $dailyReport->BaseVol = $array['BaseVol'];
        $dailyReport->EPS = $array['EPS'];
        $dailyReport->minweek = $array['minweek'];
        $dailyReport->maxweek = $array['maxweek'];
        $dailyReport->monthAVG = $array['monthAVG'];
        $dailyReport->groupPE = $array['groupPE'];
        $dailyReport->sahamShenavar = $array['sahamShenavar'];


        $namad = Namad::where('inscode', $inscode)->first();
        if (!$namad) {
            $namad = new Namad;
            $namad->symbol =$data[2];
            $namad->name = $data[3];
            $namad->inscode =  $inscode;

            if ($array['flow'] == 1) {
                $namad->flow = 'بورس';
            } else {
                $namad->flow = 'فرابورس';
            }
            $namad->save();
        }

        echo 'namad = '.$namad->symbol.PHP_EOL;
        $dailyReport->namad_id = $namad->id;
        $dailyReport->save();
    }
}
