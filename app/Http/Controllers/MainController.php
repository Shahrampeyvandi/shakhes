<?php

namespace App\Http\Controllers;

use App\Models\Member\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function Index()
    {
        $notystatus = null;
        $notifications = [];
        $data['members'] = Member::count();
         $data['subscribers'] = Member::where('subscribe','>=',Carbon::now())->count();
        return view('dashboard', $data);
    }
    public function getTime()
    {
        $data = array(
            'fulldate' => date('d-m-Y H:i:s'),
            'date' => date('d'),
            'month' => date('m'),
            'year' => date('Y'),
            'hour' => date('H'),
            'minute' => date('i'),
            'second' => date('s')
        );
        return json_encode($data);
    }
}
