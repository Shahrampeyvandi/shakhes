<?php

namespace App\Http\Controllers\Api;

use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    public function add()
    {
        $member = $this->token(request()->header('Authorization'));
        try {
            $t = new Ticket;
            $t->member_id = $member->id;
            $t->subject = request()->subject;
            $t->content = request()->message;
            $t->status = 'unread';
            $t->save();
            $data = 'پیام شما با موفقیت ارسال شد و پاسخ از طریق پیامک به شما اعلام میشود';
            $error = null;
        } catch (\Throwable $th) {
            $data = null;
            $error = 'خطا در ارتباط با سرور لطفا مجددا امتحان کنید';
        }

        // send sms to admin
        return $this->JsonResponse($data, $error, 200);
    }


    public function list()
    {
        $member = $this->token(request()->header('Authorization'));
        try {

            $tickets = Ticket::where('member_id', $member->id)->latest()->take(10)->get();
            $data = TicketResource::collection($tickets);
            $error = null;
        } catch (\Throwable $th) {
            $data = null;
            $error = 'خطا در ارتباط با سرور لطفا مجددا امتحان کنید';
        }

        // send sms to admin
        return $this->JsonResponse($data, $error, 200);
    }
}
