<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;

class ApiScheduler
{

  
    public function __invoke()
    {

       $ch = curl_init('https://oneapi.ir/api/bourse');
       //curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
       //curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'OneAPI-Key: 5890dbe511e8498ade2f324c293cc7df'
       ));
       $result = curl_exec($ch);
       $err = curl_error($ch);
       $result = json_decode($result, true);
       curl_close($ch);

       //echo $result[0]['symbol']."this is the output of the cron".PHP_EOL;
       $this->saveDailyReport($result);
       

       $ch = curl_init('https://oneapi.ir/api/bourse/overseas');
       //curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
       //curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'OneAPI-Key: 5890dbe511e8498ade2f324c293cc7df'
       ));
       $result = curl_exec($ch);
       $err = curl_error($ch);
       $result = json_decode($result, true);
       curl_close($ch);


       $this->saveDailyReport($result);
    }

public function saveDailyReport($result){

    foreach($result as $info){

        $namad= Namad::where('symbol',$info['symbol'])->first();

        if(!$namad){
            $namad=new Namad;
            $namad->symbol=$info['symbol'];
            $namad->name=$info['name'];
            $namad->market=$info['market'];
            $namad->flow=$info['flow'];
            $namad->save();
        }

        $report=new NamadsDailyReport;
        $report->namad_id=$namad->id;


        $report->last_price_value=$info['last_price']['value'];
        $report->last_price_change=$info['last_price']['change'];
        $report->last_price_percent=$info['last_price']['percent'];
        $report->last_price_status=$info['last_price']['status'];


        $report->final_price_value=$info['final_price']['value'];
        $report->final_price_change=$info['final_price']['change'];
        $report->final_price_percent=$info['final_price']['percent'];
        $report->final_price_status=$info['final_price']['status'];


        $report->trades_date=$info['trades']['date'];
        $report->trades_count=$info['trades']['count'];
        $report->trades_volume=$info['trades']['volume'];
        $report->trades_value=$info['trades']['value'];
        $report->trades_medium=$info['trades']['medium'];


        $report->prices_yesterday=$info['prices']['yesterday'];
        $report->prices_first=$info['prices']['first'];
        $report->prices_low=$info['prices']['low'];
        $report->prices_high=$info['prices']['high'];


        $report->buy_count=$info['buy']['count'];
        $report->buy_volume=$info['buy']['volume'];
        $report->buy_price=$info['buy']['price'];


        $report->sale_count=$info['sale']['count'];
        $report->sale_volume=$info['sale']['volume'];
        $report->sale_price=$info['sale']['price'];


        $report->market_value=$info['market_value'];
        $report->property_today=$info['property_today'];
        $report->property_realty=$info['property_realty'];
        $report->last_capital=$info['last_capital'];
        $report->debt=$info['debt'];
        $report->salary=$info['salary'];
        $report->income=$info['income'];
        $report->ttm=$info['ttm'];


        $report->buy_vol_person=$info['buy_vol']['person'];
        $report->buy_vol_legal=$info['buy_vol']['legal'];

        $report->sale_vol_person=$info['sale_vol']['person'];
        $report->sale_vol_legal=$info['sale_vol']['legal'];


        $report->save();

       }


}

}
