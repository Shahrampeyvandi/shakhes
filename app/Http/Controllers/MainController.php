<?php

namespace App\Http\Controllers;

use App\Models\Member\Member;
use App\Models\Namad\Namad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function Index()
    {
        
            
        

      dd( $status = \Cache::get('bazarstatus'));
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

    public function home()
    {
        // $this->sendnotification('czt3zPZBQEOOVT86uEGJE8:APA91bHgYVpA_WRADGUfZH7dL5P5LxZCa29Fjs0NTYEmjSiwGtAMV3NzcrgiZrPckUtWaVFGZSfwhTmp5n2ANEM9elUao6ZE9ongD_xlWIOm_Ixa-dv_Dr0I-b9XHVWH6Wsdca6nAmlz','وبملت','گزارش کدال , افزایش سرمایه دویست درصدی از محل سود انباشته');
        dd(\Config::get('app.FIREBASE_LEGACY_SERVER_KEY'));
        dd(env('FIREBASE_LEGACY_SERVER_KEY'));
    $namads=Namad::latest()->get();
    dd(collect($namads)->whereIn('id',[2,3,4,5,6]));
        return view('Home.index');
    }
}
