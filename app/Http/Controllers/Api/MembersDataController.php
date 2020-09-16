<?php

namespace App\Http\Controllers\Api;

use App\Setting;
use Carbon\Carbon;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use Illuminate\Http\Request;
use App\Models\clarification;
use App\Models\Namad\Disclosures;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\CapitalIncrease\CapitalIncrease;

class MembersDataController extends Controller
{

    public function personaldata(Request $request)
    {
        $member = $this->token($request->header('Authorization'));
        // $member=Member::find(2);

        $array['fname'] = $member->fname;
        $array['lname'] = $member->lname;
        $array['mobile'] = $member->phone;
        $array['namads'] = count($member->namads);
        $namads_array = $member->namads;
        foreach ($namads_array as $key => $namad) {

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
        $all = [
            'time' => $this->get_current_date_shamsi().'_'.date('H:i'),
            ];

        $namads_array = $member->namads;

        $array = [];
        foreach ($namads_array as $key => $namad) {
            $information = Cache::get($namad->id);

            $data = [];
            $data['symbol'] = $namad->symbol;
            $data['name'] = $namad->name;
            $data['id'] = $namad->id;
            $data['flow'] = $namad->flow;
            if (isset($information['pl']) && isset($information['py'])) {

                $data['final_price_value'] = $information['pl'];
                $data['final_price_percent'] = $information['py'] ?  abs(number_format((float)(($information['pl'] - $information['py']) * 100) / $information['py'], 2, '.', '')) : '';
                $data['last_price_change'] = abs($information['pl'] - $information['py']);
                $data['last_price_status'] = ($information['pl'] - $information['py']) > 0 ? '1' : '0';
                if (isset($information['pl'])) {
                    $data['status'] = $information['status'];
                } else {
                    $data['status'] = 'red';
                }
                if (isset($information['namad_status'])) {
                    $data['namad_status'] = $information['namad_status'];
                } else {
                    $data['namad_status'] = 'A';
                }
            } else {
                $data['final_price_value'] = '0';
                $data['final_price_percent'] = '0';
                $data['last_price_change'] = '0';
                $data['last_price_status'] = '0';
                $data['status'] = 'red';
                $data['namad_status'] = 'A';
            }

            $array[] = $data;
        }

        $all['data']=$array;

        return response()->json(
            $all,
            200
        );
    }

    public function add(Request $request)
    {
        $member = $this->token($request->header('Authorization'));
        $namad_id = $request->id;
        $namad_obj = Namad::whereId($namad_id)->first();
        //$namad_daily_report = $namad_obj->dailyReports->first();
        $price = 0;

        $member->namads()->attach($namad_id, ['amount' => 0, 'profit_loss_percent' => 0, 'price' => $price]);
        return response()->json(
            [
                'error' => '',
            ],
            200
        );
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
        foreach ($clarifications_array as $key => $clarification_obj) {
            $array[$count]['namad'] = $clarification_obj->namad->name;
            $array[$count]['subject'] = $clarification_obj->subject;
            $array[$count]['publish_date'] = $clarification_obj->publish_date;
            $array[$count]['link_to_codal'] = $clarification_obj->link_to_codal;
            $array[$count]['new'] = $clarification_obj->new;

            $count++;
        }
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

        if (count($capitalincreases_array)) {
            $list = [];
            foreach ($capitalincreases_array as $key => $capitalincrease_obj) {
                $array = [];
                $namad = Namad::where('id', $capitalincrease_obj->namad->id)->first();
                $array['namad'] = $capitalincrease_obj->namad ?  Cache::get($capitalincrease_obj->namad->id) : '';
                $array['namad']['symbol'] = $namad->symbol;
                $array['namad']['name'] = $namad->name;
                $array['step'] = $capitalincrease_obj->step;

                $array["from_cash"] = 0;
                $array["from_stored_gain"] = 0;
                $array["from_assets"] = 0;

                foreach ($capitalincrease_obj->amounts as $key => $item) {
                    $array["from_$item->type"] = $item->percent;
                }

                $array['publish_date'] = $capitalincrease_obj->publish_date;
                $array['link_to_codal'] = $capitalincrease_obj->link_to_codal;
                $array['description'] = $capitalincrease_obj->description;
                $array['new'] = $capitalincrease_obj->new();

                $list[] = $array;
            }
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
            $sum = array_sum($namad->getNamadNotifications());
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
}
