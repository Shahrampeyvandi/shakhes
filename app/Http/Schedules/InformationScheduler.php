<?php

namespace App\Http\Schedules;

use Goutte;
use Exception;
use App\Models\Namad\Namad;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\clarification;
use App\Models\Namad\Disclosures;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Namad\NamadsDailyReport;
use App\Models\CapitalIncrease\CapitalIncrease;

class InformationScheduler extends Scheduler
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


        $date = Jalalian::forge('now')->format('%Y/%m/%d');
        // $date="1399/01/01";


        foreach ($namads as $namad) {
            echo 'start namad searching in codal = ' . $namad->symbol . PHP_EOL;
            try {
                $this->capitalIncrease($namad, $date);
            } catch (Exception $e) {
            }
            try {
                $this->clarification($namad, $date);
            } catch (Exception $e) {
            }
            try {
                // $this->disclor($namad,$date);
            } catch (Exception $e) {
            }
            sleep(1);
        }
    }


    public function capitalIncrease($namad, $date)
    {

        $url = "https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=7&Childs=true&CompanyState=-1&CompanyType=-1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new capital increase for namad = ' . $namad->symbol . PHP_EOL;
            if (CapitalIncrease::where('namad_id', $namad->id)
                ->where('publish_date', date('Y-m-d'))
                ->where('link_to_codal', 'https://www.codal.ir/' . $info['Url'])->first()
            ) {
            } else {
                $capitalincrease = new CapitalIncrease;
                $capitalincrease->namad_id = $namad->id;
                $capitalincrease->from = 'assets';
                $capitalincrease->description = $info['Title'];
                $capitalincrease->publish_date = date('Y-m-d');
                $capitalincrease->codal_date = isset($info['PublishDateTime']) ? $this->convertPersianToEnglish($info['PublishDateTime']) : '';
                $capitalincrease->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
                $capitalincrease->pdf_link = isset($info['PdfUrl']) ? 'https://www.codal.ir/' . $info['PdfUrl'] : null;
                $capitalincrease->save();


                // send to users
                foreach ($namad->users as $key => $user) {
                    $this->sendnotification($user->firebase_token,'آگهی افزایش سرمایه',Str::limit($info['Title'], 30, '...'));   
                }
            }



        }
    }

    public function clarification($namad, $date)
    {
        $url = "https://search.codal.ir/api/search/v2/q?&Audited=true&AuditorRef=-1&Category=2&Childs=true&CompanyState=0&CompanyType=1&Consolidatable=true&FromDate=$date&IsNotAudited=false&Isic=210102&Length=-1&LetterType=-1&Mains=true&NotAudited=true&NotConsolidatable=true&PageNumber=1&Publisher=false&Symbol=$namad->symbol&TracingNo=-1&search=true";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        foreach ($result['Letters'] as $info) {
            echo 'new clarification for namad = ' . $namad->symbol . PHP_EOL;
            if (clarification::where('namad_id', $namad->id)
                ->where('publish_date', date('Y-m-d'))
                ->where('link_to_codal', 'https://www.codal.ir/' . $info['Url'])->first()
            ) {
            } else {
                $clarification = new clarification;
                $clarification->namad_id = $namad->id;
                $clarification->subject = $info['Title'];
                $clarification->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
                $clarification->codal_date = isset($info['PublishDateTime']) ? $this->convertPersianToEnglish($info['PublishDateTime']) : '';
                $clarification->publish_date = date('Y-m-d');
                $clarification->pdf_link = isset($info['PdfUrl']) ? 'https://www.codal.ir/' . $info['PdfUrl'] : null;
                $clarification->save();

                 // send to users
                 foreach ($namad->users as $key => $user) {
                    $this->sendnotification($user->firebase_token,'آگهی شفاف سازی',Str::limit($info['Title'], 30, '...'));   
                }
            }
        }
    }

    public function disclor($namad, $date)
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
            echo 'new disclor for namad = ' . $namad->symbol . PHP_EOL;
            $disclosures = new Disclosures;
            $disclosures->namad_id = $namad->id;
            $disclosures->publish_date = date('Y-m-d');
            $disclosures->subject = $info['Title'];
            $disclosures->link_to_codal = 'https://www.codal.ir/' . $info['Url'];
            $disclosures->group = 'a';
            $disclosures->save();
        }
    }
    public function convertPersianToEnglish($string)
    {
       
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $output = str_replace($persian, $english, $string);
        return $output;
    }
}
