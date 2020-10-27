<?php

namespace App\Models;

use App\Models\Discount;
use App\Models\Member\Member;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(Member::class, 'plan_user');
    }

    public function priceWithDiscount()
    {
        return (int)$this->price - (int)$this->discount;
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_plan', 'plan_id', 'discount_id');
    }

    
}
