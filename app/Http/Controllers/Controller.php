<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
use App\Models\Member\Member;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\clarification;
use App\Models\Namad\Disclosures;
use App\Models\VolumeTrade;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function token()
    {
        $payload = JWTAuth::parseToken(request()->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $member = Member::where('phone', $mobile)->first();
        return $member;
    }


    public function convertDate($date)
    {

        $date_array = explode('/', $date);
        $day = $date_array[2];
        $month = $date_array[1];
        $year = $date_array[0];
        if (strlen($month) == 1) {
            $month = '0' . $month;
        }
        if (strlen($day) == 1) {
            $day = '0' . $day;
        }

        $new_date_array = array($year, $month, $day);
        $new_date_string = implode('/', $new_date_array);
        $carbon = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $new_date_string);

        return $carbon;
    }

    public function show_with_symbol($value, $seprator = 1)
    {
        if ((int)$value > 1000000 && (int)$value < 1000000000) {
            return number_format((int)$value / 1000000, $seprator) . "M";
        } elseif ((int)$value > 1000000000) {

            return number_format((int)$value / 1000000000, $seprator) . "B";
        } else {
            return (int)$value;
        }
    }

    public function format($number)
    {
        if ($number > 0 &&  $number < 1000000) {
            return number_format($number, 0);
        } elseif ($number > 1000000 &&  $number < 1000000000) {
            return $number = number_format($number / 1000000, 2) . "M";
        } elseif ($number > 1000000000) {
            return  $number = number_format($number / 1000000000, 2) . "B";
        }
    }

    public function get_current_date_shamsi()
    {
        return Jalalian::forge('now')->format('%Y/%m/%d');
    }

    public function sendSMS($patterncode, $phone, $data)
    {
        $datas = array(
            "pattern_code" => $patterncode,
            "originator" => "+985000125475",
            "recipient" => '+98' . substr($phone, 1),
            "values" => $data
        );

        $url = "http://rest.ippanel.com/v1/messages/patterns/send";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, json_encode($datas));
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: AccessKey LH5pTlnaCiZKZiEL7gPYh_nr-c6OmdmhRh9uKLSkkP0='
        ));

        $response = curl_exec($handler);
        return $response;
    }

    public function get_history_data($inscode, $days)
    {
        $array = [];
        $ch = curl_init("http://members.tsetmc.com/tsev2/data/InstTradeHistory.aspx?i=$inscode&Top=$days&A=0");
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $result = curl_exec($ch);
        $day_data = explode(';', $result);
        foreach ($day_data as $key => $value) {
            $data = explode('@', $value);

            if (count($data) == 10) {
                $pl = substr($data[4], 0, -3);
                $pc = substr($data[3], 0, -3);;
                $year = substr($data[0], 0, 4);
                $month = substr($data[0], 4, 2);
                $day = substr($data[0], 6, 2);
                $timestamp = mktime(0, 0, 0, $month, $day, $year);
                $shamsi = Jalalian::forge($timestamp)->format('%d/%m/%y');
                $array[] = [
                    'pl' => $pl,
                    'pc' => $pc,
                    'date' => $shamsi
                ];
            }
        }
        return $array;
    }

    public function get_home_notifications($user)
    {
        $capital_increases = 0;
        $clarifications = 0;
        $disclosures = 0;
        $volume_trades_report = 0;
        $date = Carbon::today();


        $capital_increases += CapitalIncrease::whereDate('updated_at', $date)
            ->whereDoesntHave('readed_notifications', function ($q) use ($user) {
                $q->where(['member_id' => $user->id]);
            })
            ->count();
        $clarifications += clarification::whereDate('updated_at', $date)->whereDoesntHave('readed_notifications', function ($q) use ($user) {
            $q->where(['member_id' => $user->id]);
        })->count();
        $disclosures += Disclosures::whereDate('updated_at', $date)->whereDoesntHave('readed_notifications', function ($q) use ($user) {
            $q->where(['member_id' => $user->id]);
        })->count();
        $volume_trades_report += VolumeTrade::whereDate('updated_at', $date)->whereDoesntHave('readed_notifications', function ($q) use ($user) {
            $q->where(['member_id' => $user->id]);
        })->count();


        $array['capital_increases'] = $capital_increases;
        $array['clarifications'] = $clarifications;
        $array['disclosures'] = $disclosures;
        $array['volume_trades'] = $volume_trades_report;

        return $array;
    }
}
