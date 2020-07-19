<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Namad\Namad;
use Goutte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MarketController extends Controller
{

    public function getNamad()
    {

        $id = request()->id;
        $inscode = Namad::find($id);
        if ($namad) {
            $inscode = $namad->inscode;
            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
            $all = \strip_tags($crawler->html());

            $explode_all = explode(';', $all);
            $main_data = $explode_all[0];
            $buy_sell = $explode_all[4];
            $orders = $explode_all[2];

            $explode_orders = explode('@', $orders);
            $array['orders'][] = array('type' => 'buy', 'order' => '1', 'tedad' => $explode_orders[0], 'vol' => $explode_orders[1], 'price' => $explode_orders[2]);
            $array['orders'][] = array('type' => 'buy', 'order' => '2', 'tedad' => explode(',', $explode_orders[5])[1], 'vol' => $explode_orders[6], 'price' => $explode_orders[7]);
            $array['orders'][] = array('type' => 'buy', 'order' => '3', 'tedad' => explode(',', $explode_orders[10])[1], 'vol' => $explode_orders[11], 'price' => $explode_orders[12]);
            $array['orders'][] = array('type' => 'sell', 'order' => '1', 'tedad' => explode(',', $explode_orders[5])[0], 'vol' => $explode_orders[4], 'price' => $explode_orders[3]);
            $array['orders'][] = array('type' => 'sell', 'order' => '2', 'tedad' => explode(',', $explode_orders[10])[0], 'vol' => $explode_orders[9], 'price' => $explode_orders[8]);
            $array['orders'][] = array('type' => 'sell', 'order' => '3', 'tedad' => explode(',', $explode_orders[15])[0], 'vol' => $explode_orders[14], 'price' => $explode_orders[13]);

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

            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=9211775239375291');
            $all = \strip_tags($crawler->html());
            $explode = \explode(',', $all);

            preg_match('/=\'?(\d+)/', $explode[25], $matches);
            $array['Inscode'] = count($matches) ? $matches[1] : '';
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

            return response()->json($array, 200);
        }

    }

    public function shackes()
    {

        $inscode = [
            '32097828799138957' => 'شاخص کل',
            '5798407779416661' => 'شاخص قیمت',
            '67130298613737946' => 'شاخص کل(هم وزن)',
            '8384385859414435' => 'شاخص قیمت(هم وزن)',
            '49579049405614711' => 'شاخص آزاد شناور',
            '62752761908615603' => 'شاخص بازار اول',
            '71704845530629737' => 'شاخص بازار دوم',
        ];

        $all = [];
        foreach ($inscode as $key => $name) {
            $array = [];

            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=15131J&i=' . $key . '');

            $crawler->filter('#MainContent')->each(function ($node) use ($key, &$name, &$array, &$all) {

                $last_val = $node->filter('tr:contains("آخرین مقدار شاخص") td:nth-of-type(2)')->text();
                $high_val = $node->filter('tr:contains("بیشترین مقدار روز") td:nth-of-type(2)')->text();
                $low_val = $node->filter('tr:contains("کمترین مقدار روز") td:nth-of-type(2)')->text();
                $time = $node->filter('tr:contains("زمان انتشار") td:nth-of-type(2)')->text();
                $prev_val = $node->filter('.silver.tbl tr:nth-of-type(1) td:nth-of-type(2)')->text();

                $last_val = str_replace(',', '', $last_val);
                $prev_val = str_replace(',', '', $prev_val);
                $high_val = str_replace(',', '', $high_val);
                $low_val = str_replace(',', '', $low_val);

                $percent_change = ($last_val - $prev_val) * 100 / $prev_val;
                $array['title'] = $name;
                $array['inscode'] = $key;
                $array['last_val'] = $last_val;
                $array['prev_val'] = $prev_val;
                $array['high_val'] = $high_val;
                $array['low_val'] = $low_val;
                $array['percent_change'] = number_format((float) $percent_change, 2, '.', '');

                $all[] = $array;

            });

        }

        return response()->json([
            'data' => $all,
        ], 200);
    }

    public function bourseMostVisited()
    {

        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=MostVisited&Flow=1';
        $crawler = Goutte::request('GET', $url);
        $array = [];
        $all = [];
        $crawler->filter('td:nth-of-type(1) a')->each(function ($node) use (&$array, &$all) {

            $symbol = $node->attr('href');
            if ($symbol) {
                $parse = parse_url($symbol);
                parse_str($parse['query'], $query);
                $inscode = $query['i'];
            } else {
                $inscode = '';
            }
            

            $array[] = $inscode;
            // get symbol data from redis with $inscode's
            
        });
    //  return sort($array);
        // return $array;
        $all = [];
        foreach ($array as $key => $inscode) {
            $namad = Namad::where('inscode', $inscode)->first();
            if ($namad) {
                $id = $namad->id;
                $information = Cache::get($id);
                // return $information;
                if(!is_null($information)) {
                if (array_key_exists('pl', $information) && array_key_exists('py', $information)) {
                    $pl = $information['pl'];
                    $py = $information['py'];
                    if ($pl && $py) {
                        $data['symbol'] = $namad->symbol;
                        $data['name'] = $namad->name;
                        $data['final_price_value'] = $pl;
                        $data['final_price_percent'] = $py ? (($pl - $py) * 100) / $py : '';
                        $data['last_price_change'] = $pl - $py;
                        $data['last_price_status'] = ($pl - $py) > 0 ? '1' : '0';
                    } else {
                        $data['symbol'] = $namad->symbol;
                        $data['name'] = $namad->name;
                        $data['final_price_value'] = '';
                        $data['final_price_percent'] = '';
                        $data['last_price_change'] = '';
                        $data['last_price_status'] = '';
                    }

                    $all[] = $data;
                }

            }
        }

        }
        return response()->json($all, 200);
    }

    public function farabourceMostVisited()
    {
        $url = 'http://www.tsetmc.com/Loader.aspx?Partree=151317&Type=MostVisited&Flow=2';
        $crawler = Goutte::request('GET', $url);
        $array = [];
        $all = [];
        $crawler->filter('td:nth-of-type(1) a')->each(function ($node) use (&$array, &$all) {

            $symbol = $node->attr('href');
            if ($symbol) {
                $parse = parse_url($symbol);
                parse_str($parse['query'], $query);
                $inscode = $query['i'];
            } else {
                $inscode = '';
            }

            $array[] = $inscode;
            // get symbol data from redis with $inscode's

        });
        return response()->json($array, 200);
    }

}
