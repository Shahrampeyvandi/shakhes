<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Plan;
use App\User;
use Carbon\Carbon;
use App\Notification;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class PayController extends Controller
{

    public function list()
    {
        $data['paymants'] = Payment::latest()->get();
        return view('Paymants',$data);
    }
    public function pay(Request $request)
    {
        // if(session()->has('amount' . auth()->user()->id)){
        //     dd('ok');
        // }else{
        //     dd('no');
        // }

        if (auth()->guard('admin')->check()) {
            toastr()->success('تمام اشتراک ها در دسترس ادمین قرار دارند');
            return back();
        }


        $plan = Plan::whereId($request->plan_name)->first();


        if (!$plan) return back();

        $expire_date = Carbon::now()->addDays($plan->days);

        //برای تست کردن مقدار دیباگ مد رو روی یک قررا بده وگرنه صفر
        $debugmode = 0;
        $user = auth()->user();



        $payment = new Payment;
        $payment->user_id = $user->id;
        $payment->plan_id = $plan->id;
        if (session()->has('amount' . auth()->user()->id)) {
            $amount = session()->get('amount' . auth()->user()->id);
        } else {
            $amount =  $plan->price;
        }
        $payment->amount =  $amount;
        $payment->save();




        if ($payment->amount == 0) {
            $plan = Plan::find($payment->plan_id);
            $expire = Carbon::parse(auth()->user()->expire_date)->timestamp;
            $now = Carbon::now()->timestamp;
            if ($expire < $now) {
                $expire_date = Carbon::now()->addDays($plan->days);
            } else {
                $expire_date = Carbon::parse(auth()->user()->expire_date)->addDays($plan->days);
            }

            $user->expire_date = $expire_date;
            $user->update();
            $user->fresh();


            $payment->success = 1;
            $payment->update();

            if (session()->has('discount_id' . $user->id)) {
                $discount = Discount::find(session()->get('discount_id' . $user->id));
                if ($discount->count !== null) {
                    $discount->decrement('count', 1);
                }
                $user->discounts()->attach(session()->get('discount_id' . $user->id));
            }
            session()->forget('discount_id' . $user->id);
            session()->forget('amount' . auth()->user()->id);

            $this->sendNoty(auth()->user(), $plan);

            return redirect()->route('MainUrl');
        }


        $data = array(
            'MerchantID' => '2a00b862-a97e-11e6-9e29-005056a205be',
            'Amount' => $payment->amount,
            'CallbackURL' => route('Pay.CallBack') . '?id=' . $payment->id,
            'Description' => 'پرداخت از سایت'
        );
        $jsonData = json_encode($data);
        if ($debugmode == 1) {
            $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        } else {
            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ($result["Status"] == 100) {

                if ($debugmode == 1) {
                    $link = 'https://sandbox.zarinpal.com/pg/StartPay/' . $result["Authority"];
                } else {
                    $link = 'https://www.zarinpal.com/pg/StartPay/' . $result["Authority"];
                }

                return redirect($link);


                die();
            } else {
                echo 'ERR: ' . $result["Status"];
            }
        }
    }

    public function callback(Request $request)
    {
        $user = auth()->user();
        //برای تست کردن مقدار دیباگ مد رو روی یک قررا بده وگرنه صفر
        $debugmode = 0;

        $payment = Payment::find($request->id);

        $Authority = $request->Authority;

        $data = array('MerchantID' => '2a00b862-a97e-11e6-9e29-005056a205be', 'Authority' => $Authority, 'Amount' => $payment->amount);
        $jsonData = json_encode($data);
        if ($debugmode == 1) {
            $ch = curl_init('https://sandbox.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
        } else {
            $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ($result['Status'] == 100) {
                //echo 'Transation success. RefID:' . $result['RefID'];

                $payment->success = 1;
                $payment->transaction_code = $result['RefID'];
                $payment->update();



                // به اعتبارش اضافه کن
                // تراکنش موفق بود هر جا می خوای ریدایرکتش کن
                $plan = Plan::find($payment->plan_id);
                $expire = Carbon::parse(auth()->user()->expire_date)->timestamp;
                $now = Carbon::now()->timestamp;
                if ($expire < $now) {
                    $expire_date = Carbon::now()->addDays($plan->days);
                } else {
                    $expire_date = Carbon::parse(auth()->user()->expire_date)->addDays($plan->days);
                }


                $user->expire_date = $expire_date;
                $user->update();

                // برای ارسال پیامک ثبت خرید اشتراک
                $patterncode = "w2z4s4pd1e";
                $data = array("name" => auth()->user()->first_name, "day" => $plan->days);
                $this->sendSMS($patterncode, auth()->user()->mobile, $data);



                if (session()->has('discount_id' . $user->id)) {
                    $discount = Discount::find(session()->get('discount_id' . $user->id));
                    if ($discount->count !== null) {
                        $discount->decrement('count', 1);
                    }
                    $user->discounts()->attach(session()->get('discount_id' . $user->id));
                }
                session()->forget('discount_id' . $user->id);
                session()->forget('amount' . auth()->user()->id);
                //auth()->user()->plans()->attach($plan->id, ['expire_date' => $expire_date]);
                // send sms 
                $this->sendNoty(auth()->user(), $plan);
                return redirect()->route('MainUrl');
            } else {

                // تراکنش ناموفق بوده
                toastr()->error('تراکنش ناموفق بود');
                return redirect()->route('S.SiteSharing');
            }
        }
    }
}
