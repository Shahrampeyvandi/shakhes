<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\clarification;
use App\Models\Namad\NamadsDailyReport;
use App\Models\Namad\Disclosures;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Goutte;
use Morilog\Jalali\Jalalian;

class InformationScheduler
{


    public function __invoke()
    {

        $namads = [];
        if (Cache::has('namadlist')) {
            $namads = Cache::get('namadlist');
        } else {
            $namads = Namad::all();
            Cache::store()->put('namadlist', $namads, 86400); // 10 Minutes
        }


        $date=Jalalian::forge('now')->format('%Y/%m/%d');
        $date="1399/01/01";


        foreach ($namads as $namad) {
            echo 'start namad searching in codal = '.$namad->symbol . PHP_EOL;


            try {
                $this->capitalIncrease($namad,$date);
            } catch (Exception $e) {
            }
            try {
                $this->clarification($namad,$date);
            } catch (Exception $e) {
            }
            try {
                $this->disclor($namad,$date);
            } catch (Exception $e) {
            }
        }
    }


    public function capitalIncrease($namad,$date)
    {

        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=7&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=251103&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new capital increase for namad = '.$namad->symbol . PHP_EOL;
            $capitalincrease = new CapitalIncrease;
            $capitalincrease->namad_id = $namad->id;
            $capitalincrease->from ='assets';
            $capitalincrease->description = $info['Title'];
            $capitalincrease->publish_date = date('Y-m-d');
            $capitalincrease->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $capitalincrease->save();
        }
    }

    public function clarification($namad,$date)
    {
        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=-1&Childs=true&CompanyState=2&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=322001&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new clarification for namad = '.$namad->symbol . PHP_EOL;
            $clarification = new clarification;
            $clarification->namad_id = $namad->id;
            $clarification->subject = $info['Title'];
            $clarification->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $clarification->publish_date = date('Y-m-d');
            $clarification->save();
        }
    }

    public function disclor($namad,$date)
    {
        $ch = curl_init("https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=-1&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=422007&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new disclor for namad = '.$namad->symbol . PHP_EOL;
            $disclosures = new Disclosures;
            $disclosures->namad_id = $namad->id;
            $disclosures->publish_date = date('Y-m-d');
            $disclosures->subject = $info['Title'];
            $disclosures->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $disclosures->group = 'a';
            $disclosures->save();
        }
    }

}
