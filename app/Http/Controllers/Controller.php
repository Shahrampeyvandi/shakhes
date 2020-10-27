<?php

namespace App\Http\Controllers;

use App\Models\Member\Member;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Morilog\Jalali\Jalalian;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $year = $date_array[2];
        $month = $date_array[1];
        $day = $date_array[0];
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
}
