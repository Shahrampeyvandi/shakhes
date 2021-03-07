<?php

namespace App\Http\Controllers\Api;


use App\Setting;
use Carbon\Carbon;
use App\Models\Selected;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\clarification;
use App\Models\Namad\Disclosures;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\VolumeTradeResource;
use App\Http\Resources\ClarificationResource;
use App\Http\Resources\CapitalIncreaseResource;
use App\Http\Resources\NamadResource;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\Holding\Holding;

class MembersDataController extends Controller
{

    public function personaldata(Request $request)
    {
        $member = $this->token($request->header('Authorization'));
        // $member=Member::find(2);
        $array['fname'] = $member->fname;
        $array['lname'] = $member->lname;
        $array['mobile'] = $member->phone;
        $member_namads = $member->namads;
        $array['namads'] = count($member_namads);

        foreach ($member_namads as $key => $namad) {
            $array['count_capital_inc'] = $namad->capital_increases()->where('created_at', '>', Carbon::now()->subDay(3))
                ->where('new', 1)->count();
            $array['count_clarification'] = $namad->clarifications()->where('created_at', '>', Carbon::now()->subDay(3))
                ->where('new', 1)->count();
            // فعلا همین دو مورد هست صورت های مالی و پرتفوی روزانه شرکت ها نیاز به نوتیفیکیشن ندارد
        }

        return response()->json(
            $array,
            200
        );
    }


    public function namads(Request $request)
    {
        $member = $this->token($request->header('Authorization'));
        // $member=Member::find(2);

        $namads_array = $member->namads;
        $array = [];

        $array = NamadResource::collection($namads_array);
        $error = null;

        return $this->JsonResponse($array, $error, 200);
    }

    public function mark_to_read()
    {
        $member = $this->token(request()->header('Authorization'));
        if (request()->type == 'capitalincrease') {
            $type = 'App\Models\CapitalIncrease\CapitalIncrease';
        }
        if (request()->type == 'clarification') {
            $type = 'App\Models\clarification';
        }
        if (request()->type == 'disclosure') {
            $type = 'App\Models\Namad\Disclosures';
        }
        if (request()->type == 'volumetrade') {
            $type = 'App\Models\VolumeTrade';
        }


        $n = new Notification();
        $n->member_id = $member->id;
        $n->namad_id = request()->namad_id;
        $n->notificationable_id    = request()->id;
        $n->notificationable_type = $type;
        $n->text = '';
        $n->save();


        return response()->json('success', 200);
    }
    

    public function tabList()
    {
        $user = $this->token(request()->header('Authorization'));
        $namad_id = request()->id;
        $namad = Namad::find($namad_id);
        $arr = [];
        if ($holding = Holding::where('namad_id', $namad_id)->first()) {
            $arr[] = [
                'tabName' => 'portfoy',
                'tabNotificationCount' => $holding->updated_at->isToday() ? 1 : 0
            ];
        }

        $arr[] = [
            'tabName' => 'capital_increases',
            'tabNotificationCount' => $namad->getUserNamadNotifications($user)['capital_increases']
        ];
        $arr[] = [
            'tabName' => 'volume_trades',
            'tabNotificationCount' => $namad->getUserNamadNotifications($user)['volume_trades']
        ];
        $arr[] = [
            'tabName' => 'disclosures',
            'tabNotificationCount' => $namad->getUserNamadNotifications($user)['disclosures']
        ];
        $arr[] = [
            'tabName' => 'clarifications',
            'tabNotificationCount' => $namad->getUserNamadNotifications($user)['clarifications']
        ];

        return $this->JsonResponse($arr,null,200);
    }


    public function add(Request $request)
    {
        $member = $this->token($request->header('Authorization'));
        $namad_id = $request->id;
        //$namad_daily_report = $namad_obj->dailyReports->first();
        $price = 0;
        $res =  $member->check_could_add($namad_id);

        if ($res['status'] == 201) {
            $member->namads()->attach($namad_id, ['amount' => 0, 'profit_loss_percent' => 0, 'price' => $price]);
            return $this->JsonResponse($res['message'], null, 201);
        }

        return $this->JsonResponse(null, $res['message'], 200);
    }

    public function remove(Request $request)
    {
        $member = $this->token($request->header('Authorization'));
        if (is_array($request->id)) {
            $namad_ids = $request->id;
            foreach ($namad_ids as $namad) {
                $member->namads()->detach($namad);
            }
            $count = count($request->id);
        } else {
            $member->namads()->detach($request->id);
            $count = 1;
        }
        $data = $count . ' نماد با موفقیت حذف شدند';
        return $this->JsonResponse($data, null, 200);
    }


    public function namadclarifications($namad_id = null)
    {

        if ($namad_id) {
            $clarifications_array = clarification::where('namad_id', $namad_id)->latest()->paginate(20);
        } else {
            $clarifications_array = clarification::latest()->paginate(20);
        }
        $all = [];


        if (count($clarifications_array)) {
            $list = [];
            foreach ($clarifications_array as $key => $clarification_obj) {
                $array['namad'] = $clarification_obj->namad ? Cache::get($clarification_obj->namad->id) : '';
                $namad = Namad::where('id', $clarification_obj->namad->id)->first();
                $array['namad'] = $clarification_obj->namad ?  Cache::get($clarification_obj->namad->id) : '';
                $array['namad']['symbol'] = $namad->symbol;
                $array['namad']['name'] = $namad->name;
                $array['subject'] = $clarification_obj->subject;
                $array['publish_date'] = $clarification_obj->publish_date;
                $array['link_to_codal'] = $clarification_obj->link_to_codal;
                $array['new'] = $clarification_obj->new;
                $array['id'] = $clarification_obj->id;
                $list[] = $array;
            }


            if ($namad_id) {
                return response()->json(
                    ['data' => $list],
                    200
                );
            } else {
                $all = [
                    'time' => $this->get_current_date_shamsi() . '_' . date('H:i'),
                ];
                $all['data'] = $list;

                return response()->json($all, 200);
            }
        } else {
            return response()->json(
                ['error' => 'هیج اطلاعاتی وجود ندارد'],
                401
            );
        }
    }


    public function getclarifications()
    {
        $member = $this->token(request()->header('Authorization'));

        $member_namads = $member->namads->pluck('id')->toArray();
        $clarifications_array = clarification::whereIn('namad_id', $member_namads)->latest()->get();
        $count = 1;
        $array = ClarificationResource::collection($clarifications_array);

        return response()->json(
            $array,
            200
        );
    }

    public function namadcapitalincreases($namad_id = null)
    {


        if ($namad_id) {
            $namad = Namad::where('id', $namad_id)->first();
            $capitalincreases_array = $namad->capital_increases()->paginate(20);
        } else {
            $capitalincreases_array = CapitalIncrease::latest()->paginate(20);
        }

        $all = [];
        $list = [];
        if (count($capitalincreases_array)) {
            $list = CapitalIncreaseResource::collection($capitalincreases_array);
        } else {
            return response()->json(
                ['error' => 'هیج اطلاعاتی وجود ندارد'],
                401
            );
        }

        if ($namad_id) {
            return response()->json(
                ['data' => $list],
                200
            );
        } else {
            $all = [
                'time' => $this->get_current_date_shamsi() . '_' . date('H:i'),
            ];
            $all['data'] = $list;

            return response()->json($all, 200);
        }
    }

    public function getcapitalincreases()
    {
        $member = $this->token(request()->header('Authorization'));

        $member_namads = $member->namads->pluck('id')->toArray();
        $capitalincreases_array = CapitalIncrease::whereIn('namad_id', $member_namads)->latest()->get();
        $count = 1;
        foreach ($capitalincreases_array as $key => $capitalincrease_obj) {
            $array[$count]['namad'] = $capitalincrease_obj->namad->name;
            $array[$count]['step'] = $capitalincrease_obj->step;
            // چک میشود اگر افزایش سرمایه ترکیبی باشد
            $array["from_cash"] = 0;
            $array["from_stored_gain"] = 0;
            $array["from_assets"] = 0;

            if ($capitalincrease_obj->from == 'compound') {
                foreach ($capitalincrease_obj->amounts as $key => $item) {

                    $array["from_$item->type"] = $item->percent;
                }
            } else {
                $array['from'][$capitalincrease_obj->from] = '100';
            }

            $array[$count]['publish_date'] = $capitalincrease_obj->publish_date;
            $array[$count]['link_to_codal'] = $capitalincrease_obj->link_to_codal;
            $array[$count]['description'] = $capitalincrease_obj->description;
            $array[$count]['new'] = $capitalincrease_obj->new;

            $count++;
        }
        return response()->json(
            $array,
            200
        );
    }

    public function notifications()
    {
        $member = $this->token(request()->header('Authorization'));
        $member_namads = $member->namads;
        $all = [];
        foreach ($member_namads as $key => $namad) {
            $sum = array_sum($namad->getUserNamadNotifications());
            $array['namad'] = $namad->symbol;
            $array['notifications'] = $sum;
            $all[] = $array;
        }

        return response()->json(['data' => $all, 'error' => false], 200);
    }

    public function namadDisclosures($namad_id)
    {
        // $member = $this->token(request()->header('Authorization'));
        $namad = Namad::where('id', $namad_id)->first();
        if ($namad) {
            if (count($disclosures = $namad->disclosures()->latest()->paginate(20))) {
                $count = 1;
                $all = [];
                foreach ($disclosures as $key => $disclosure) {
                    $array['namad'] = $namad->symbol;
                    $array['group'] = $disclosure->group;
                    $array['subject'] = $disclosure->subject;
                    $array['link_to_codal'] = $disclosure->link_to_codal;
                    $array['publish_date'] = $disclosure->publish_date;
                    $all[] = $array;
                }
            } else {
                return response()->json(
                    ['error' => 'هیج اطلاعاتی وجود ندارد'],
                    401
                );
            }
        } else {
            return response()->json(
                ['error' => 'نماد مورد نظر پیدا نشد'],
                401
            );
        }

        return response()->json(
            ['data' => $all],
            200
        );
    }
    public function namadVolumeTrades($namad_id)
    {
        $volume_trade = VolumeTrade::find($namad_id);

        $array['symbol'] = $volume_trade->namad->symbol;
        $array['name'] = $volume_trade->namad->name;
        $array['price'] = $volume_trade->namad->dailyReports()->latest()->first()->last_price_value;
        $array['trades_volume'] = $volume_trade->namad->dailyReports()->latest()->first()->trades_volume;
        $array['base_zarib'] = Setting::first()->trading_volume_ratio;
        $array['current_zarib'] = $volume_trade->volume_ratio;

        return response()->json(
            $array,
            200
        );
    }

    public function addToSelected()
    {
        $member = $this->token(request()->header('Authorization'));

        if (request()->type == 'capital_increase') {
            $m = CapitalIncrease::find(request()->id);
        }
        if (request()->type == 'clarification') {
            $m = clarification::find(request()->id);
        }
        if (request()->type == 'volume_trade') {
            $m = VolumeTrade::find(request()->id);
        }

        if ($exist = $m->bookmarks()->where('member_id', $member->id)->first()) {
            $exist->delete();
            $res = 'با موفقیت حذف شد';
        } else {

            $m->bookmarks()->create([
                'member_id' => $member->id
            ]);
            $res = 'با موفقیت افزوده شد';
        }

        return $this->JsonResponse($res, null, 200);
    }

    public function userSelected()
    {

        $member = $this->token(request()->header('Authorization'));


        if (isset(request()->type)) {
            if (request()->type == 'capital_increase') {
                $c =  CapitalIncrease::whereHas('bookmarks', function ($q) use ($member) {
                    $q->where([
                        'member_id' => $member->id
                    ]);
                })->get();
                $data = CapitalIncreaseResource::collection($c);
            }
            if (request()->type == 'clarification') {
                $c =  clarification::whereHas('bookmarks', function ($q) use ($member) {
                    $q->where([
                        'member_id' => $member->id
                    ]);
                })->get();
                $data = ClarificationResource::collection($c);
            }
            if (request()->type == 'volume_trade') {
                $c =  VolumeTrade::whereHas('bookmarks', function ($q) use ($member) {
                    $q->where([
                        'member_id' => $member->id
                    ]);
                })->get();
                $data = VolumeTradeResource::collection($c);
            }
        } else {
            $c =  CapitalIncrease::whereHas('bookmarks', function ($q) use ($member) {
                $q->where([
                    'member_id' => $member->id
                ]);
            })->get();
            $data['capitalIncreases'] = CapitalIncreaseResource::collection($c);
            $c =  clarification::whereHas('bookmarks', function ($q) use ($member) {
                $q->where([
                    'member_id' => $member->id
                ]);
            })->get();
            $data['clarifications'] = ClarificationResource::collection($c);
            $c =  VolumeTrade::whereHas('bookmarks', function ($q) use ($member) {
                $q->where([
                    'member_id' => $member->id
                ]);
            })->get();
            $data['volumeTrades'] = VolumeTradeResource::collection($c);
        }



        return $this->JsonResponse($data, null, 200);
    }
}
