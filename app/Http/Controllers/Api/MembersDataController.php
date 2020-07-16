<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\clarification;
use App\Models\Namad\Disclosures;
use App\Models\Namad\Namad;
use App\Models\VolumeTrade;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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

        $namads_array = $member->namads;

        $array = [];
        foreach ($namads_array as $key => $namad_data) {
           
            $array[$key]['id'] = $namad_data->id;
            $array[$key]['symbol'] = $namad_data->symbol;

            $inscode = $namad_data->inscode;
            $crawler = Goutte::request('GET', 'http://www.tsetmc.com/tsev2/data/instinfofast.aspx?i=' . $inscode . '&c=57');
            $all = \strip_tags($crawler->html());

            $explode_all = explode(';', $all);
            $main_data = $explode_all[0];
            $pl = explode(',', $main_data)[2];
            $py = explode(',', $main_data)[5];
            if($pl && $py) {
            $array[$key]['final_price_value'] = $pl;
            $array[$key]['final_price_percent'] = $py ? (($pl-$py) * 100) / $py : '';
            $array[$key]['last_price_change'] = $pl - $py ;
            $array[$key]['last_price_status'] = ($pl - $py) > 0 ? '1' : '0';
            }else{
            $array[$key]['final_price_value'] = '';
            $array[$key]['final_price_percent'] = '';
            $array[$key]['last_price_change'] = '' ;
            $array[$key]['last_price_status'] = '';
            }

        }

        return response()->json(
            ['data' => $array],
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
    public function namadclarifications($namad_id)
    {

        // $member = $this->token(request()->header('Authorization'));

        // $member_namads = $member->namads->pluck('id')->toArray();

        if (count($clarifications_array = clarification::where('namad_id', $namad_id)->latest()->get())) {
            $all = [];
            foreach ($clarifications_array as $key => $clarification_obj) {
                $array['namad'] = $clarification_obj->namad->name;
                $array['subject'] = $clarification_obj->subject;
                $array['publish_date'] = $clarification_obj->publish_date;
                $array['link_to_codal'] = $clarification_obj->link_to_codal;
                $array['new'] = $clarification_obj->new;
                $all[] = $array;
            }
            return response()->json(
                ['data' => $all],
                200
            );
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

    public function namadcapitalincreases($namad_id)
    {

        // $member = $this->token(request()->header('Authorization'));
        $namad = Namad::where('id', $namad_id)->first();
        if ($namad) {

            if (count($capitalincreases_array = $namad->capital_increases)) {
                $count = 1;
                $all = [];
                foreach ($capitalincreases_array as $key => $capitalincrease_obj) {

                    $array['namad'] = $capitalincrease_obj->namad->name;
                    $array['step'] = $capitalincrease_obj->step;
                    // چک میشود اگر افزایش سرمایه ترکیبی باشد
                    $array["from_cash"] = 0;
                    $array["from_stored_gain"] = 0;
                    $array["from_assets"] = 0;

                    foreach ($capitalincrease_obj->amounts as $key => $item) {

                        $array["from_$item->type"] = $item->percent;
                    }

                    $percents = $capitalincrease_obj->showPercents();
                    $array["percent_from_cash"] = $percents['percent_from_cash'];
                    $array["percent_from_stored_gain"] = $percents['percent_from_stored_gain'];
                    $array["percent_from_assets"] = $percents['percent_from_assets'];

                    $array['publish_date'] = $capitalincrease_obj->publish_date;
                    $array['link_to_codal'] = $capitalincrease_obj->link_to_codal;
                    $array['description'] = $capitalincrease_obj->description;
                    $array['new'] = $capitalincrease_obj->new;
                    $all[] = $array;
                    $count++;
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
            if (count($disclosures = $namad->disclosures()->latest()->get())) {
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
