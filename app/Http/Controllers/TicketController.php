<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
       
        $data['tickets'] = Ticket::orderByRaw("FIELD(status, \"unread\", \"suspended\", \"readed\", \"expired\")")->get();
        return view('Tickets.index',$data);
    }

    public function show()
    {
        $data['ticket'] = Ticket::find(request()->id);
        return view('Tickets.show',$data);
    }

    public function insertAnswer()
    {
        // dd(request()->all());
        
        $t = Ticket::find(request()->id);
        if(isset(request()->suspended) && request()->suspended == 'on'){
            $t->status = 'suspended';
        }elseif(request()->text){
            $t->status = 'readed';
            $t->answer = request()->text;
        }
        $t->update();
        // send sms or notif
        return back()->with('success', 'با موفقیت ثبت شد');
    }
  

    
}
