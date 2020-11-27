<?php

namespace App\Models\Member;

use App\Models\Plan;
use App\Models\Discount;
use App\Models\Namad\Namad;
use App\Models\Member\Subscribe;
use App\Models\Accounting\Transaction;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\Selected;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Morilog\Jalali\Jalalian;

class Member extends  Authenticatable  implements JWTSubject
{
    protected $guarded = [];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            'fname'      => $this->fname,
            'lname'       => $this->lname,
            'mobile'           => $this->phone,
        ];
    }


    public function transactions()
    {
        return $this->hasMany(Subscribe::class);
    }
      public function bookmarks()
    {
        return $this->hasMany(Selected::class);
    }

    public function subscribes()
    {
        return $this->hasMany(Transaction::class);
    }

    public function namads()
    {
        return $this->belongsToMany(Namad::class, 'members_namads')->withPivot(['amount', 'profit_loss_percent', 'price']);
    }
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'user_plan', 'user_id', 'plan_id')->withPivot('expire_date');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'user_discount', 'user_id', 'discount_id');
    }

    public function get_plan()
    {
        if($this->subscribe < Carbon::now() || !$this->subscribe){
            return false;   
        }
        return Jalalian::forge($this->subscribe)->format('%Y-%m-%d');
    }

    public function check_could_add($namad_id)
    {
        if ($this->namads->contains($namad_id)) {
            return  ['status' => 200, 'message' => 'نماد مورد نظر از قبل انتخاب شده است'];
        }
        if (count($this->namads) >= 15) {
            return  ['status' => 200, 'message' => "حداکثر میتوانید 15 سهام انتخاب کنید"];
        }
        if (count($this->namads) == 0) {
            return  ['status' => 201, 'message' => 'با موفقیت اضافه شد'];
        } else {
            if ($this->subscribe > Carbon::now()) {
                return  ['status' => 201, 'message' => 'با موفقیت اضافه شد'];
            } else {
                return  ['status' => 200, 'message' => 'برای افزودن نماد باید اشتراک خریداری کنید'];
            }
        }
    }

    public function get_notifications()
    {
        $my_namads = $this->namads;
        $all_notif = 0;
        foreach ($my_namads as $key => $namad) {
            $dd = $namad->getUserNamadNotifications($this);
            $array['my_namads'][] = $dd;
            $all_notif += $dd['count'];
        }
        $array['count'] = $all_notif;
        
        return $array;
    }

   

    public function check_if_has_namad($id)
    {
        return $this->namads->contains($id) ? true : false;

    }
}
