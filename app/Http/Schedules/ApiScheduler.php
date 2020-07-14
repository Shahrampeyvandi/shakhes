<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Exception;
use Illuminate\Support\Facades\Redis;

class ApiScheduler
{

  
    public function __invoke()
    {

        $values = Redis::command('keys', ['*']);

        foreach($values as $value){
            try{
                echo date('Y-m-d H:00:00')."this is the output of the cron".$value.PHP_EOL;

                $namad = Redis::hgetall($value);
                $namad = json_decode(end($namad), true);
                echo $namad['l18'].PHP_EOL;
                $this->saveDailyReport($namad);

            }catch(Exception $e){
                echo $e.PHP_EOL;
            }
           


        }

        //$values = Redis::command('FLUSHDB');

       
    }

public function saveDailyReport($namad){


        $savednamad= Namad::where('code',$namad['iid'])->first();

        if(!$savednamad){
            $savednamad=new Namad;
            $savednamad->symbol=$namad['l18'];
            $savednamad->name=$namad['l30'];
            $savednamad->market='سهام';
            $savednamad->flow='بورس';
            $savednamad->code=$namad['iid'];
            $savednamad->save();
        }

        $report=new NamadsDailyReport;
        $report->namad_id=$savednamad->id;


        $report->last_price_value=$namad['pl'];
        $report->last_price_change=$namad['plc'];
        $report->last_price_percent=$namad['plp'];
        $report->last_price_status='نامعلوم';


        $report->final_price_value=$namad['pc'];
        $report->final_price_change=$namad['pcc'];
        $report->final_price_percent=$namad['pcp'];
        $report->final_price_status='نامعلوم';


        $report->trades_date='امروز';
        $report->trades_count=$namad['tno'];
        $report->trades_volume=$namad['tvol'];
        $report->trades_value=$namad['tval'];


        $report->prices_yesterday=$namad['py'];
        $report->prices_first=$namad['pf'];
        $report->prices_low=$namad['pmin'];
        $report->prices_high=$namad['pmax'];


        $report->buy_count=$namad['ct']['Buy_CountI'];
        $report->buy_volume=$namad['ct']['Buy_I_Volume'];
        $report->buy_price=0;


        $report->sale_count=$namad['ct']['Sell_CountI'];
        $report->sale_volume=$namad['ct']['Sell_I_Volume'];
        $report->sale_price=0;


        $report->market_value=$namad['mv']?? 0;
        $report->property_today=0;
        $report->property_realty=0;
        $report->last_capital=0;
        $report->debt=0;
        $report->salary=0;
        $report->income=0;
        $report->ttm=0;


        $report->buy_vol_person=$namad['ct']['Buy_I_Volume'];
        $report->buy_vol_legal=$namad['ct']['Buy_N_Volume'];

        $report->sale_vol_person=$namad['ct']['Sell_I_Volume'];
        $report->sale_vol_legal=$namad['ct']['Sell_N_Volume'];


        $report->save();

       


}

}
