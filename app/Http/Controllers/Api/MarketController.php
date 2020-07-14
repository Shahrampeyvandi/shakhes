<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Goutte;
use Illuminate\Http\Request;

class MarketController extends Controller
{

    public function getNamad()
    {

        $url = 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=778253364357513&c=57';
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

    public function shackes()
    {
        $crawler = Goutte::request('GET', 'http://www.tsetmc.com/Loader.aspx?ParTree=151311&i=44818950263583523');
        // $crawler->filter('#MainContent')->each(function ($node) {

        //     return $node->text();
        // });
        //  $adminhelp = file_get_contents('http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=778253364357513&c=57');
        $all = \strip_tags($crawler->html());
        $explode = \explode(',', $all);
        // return array_search("'45128.00'", $explode);
        preg_match('/\'?(\d+)/', $explode[51], $matches);
        $array['pf'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[48], $matches);
        $array['pc'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[49], $matches);
        $array['py'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[23], $matches);
        $array['flow'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[24], $matches);
        $array['ID'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[26], $matches);
        $array['BaseVol'] = count($matches) ? $matches[1] : '';
        preg_match('/\'?(\d+)/', $explode[27], $matches);
        $array['EPS'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[34], $matches);
        $array['pmax'] = count($matches) ? $matches[1] : '';
        preg_match('/=\'?(\d+)/', $explode[35], $matches);
        $array['pmin'] = count($matches) ? $matches[1] : '';
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

        return $array;

        foreach ($explode as $key => $value) {
            if (strstr($value, 'EstimatedEPS')) {
                $array['eps'] = $value;
                preg_match('/=\'(-?\d+)/', $value, $matches);
                return $array['eps'] = $matches[1];

            }
        }
        foreach ($explode as $key => $value) {
            if (strstr($value, 'ZTitad')) {
                $array['tedadsaham'] = $value;
                preg_match('/=(\d+)/', $value, $matches);
                return $array['tedadsaham'] = $matches[1];

            }
        }

        foreach ($explode as $key => $value) {
            if (strstr($value, 'QTotTran5JAvg')) {
                preg_match('/=\'(\d+)/', $value, $matches);
                $array['monthavg'] = $matches[1];

            }
        }

        //  return \strpos($all,'QTotTran5JAvg',12);
        // return \strripos($all,'QTotTran5JAvg');
        return \substr($all, \strpos($all, 'QTotTran5JAvg'), 20);
        return $explode_all = explode(';', $all);
        $first_line = \substr($all, 0, \strpos($all, ';'));
        $explode = \explode(',', $first_line);
        $array['time'] = $explode[0];
        $array['pl'] = $explode[2]; //akharin
        $array['pc'] = $explode[3]; //payani
        $array['pf'] = $explode[4]; // avalin
        $array['py'] = $explode[5]; // diroz
        $array['pmax'] = $explode[6]; //max
        $array['pmin'] = $explode[7]; //min
        $array['tno'] = $explode[8]; //tedad moamelat
        $array['tvol'] = $explode[9]; // hajm moamelat
        $array['tval'] = $explode[10]; // arzesh moamelat

        return $array;

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

        return response()->json($all, 200);
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
        return response()->json($array, 200);
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
