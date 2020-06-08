<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member\Subscribe;
use App\Models\Accounting\Transaction;
use App\Models\Namad\Namad;
use Tymon\JWTAuth\Contracts\JWTSubject;
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
        return $this->belongsToMany(Namad::class,'members_namads')->withPivot(['amount', 'profit_loss_percent', 'price']);
    }
}
