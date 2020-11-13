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
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function check_could_add($namad_id)
    {
        if ($this->namads->contains($namad_id)) {
            return  ['success' => false, 'message' => 'Namad Already Exist'];
        }
        if (count($this->namads) == 0) {
            return  ['success' => true, 'message' => 'Namad Added Successfuly'];
        } else {
            if ($this->expire_date > Carbon::now()) {
                return  ['success' => true, 'message' => 'Namad Added Successfuly'];
            } else {
                return  ['success' => false, 'message' => 'Sorry! You Are Not Active Plan'];
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
        $array['my_namads']['all_notif'] = $all_notif;
        return $array;
    }
}
