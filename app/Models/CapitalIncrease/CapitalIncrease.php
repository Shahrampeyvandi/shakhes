<?php

namespace App\Models\CapitalIncrease;

use Illuminate\Database\Eloquent\Model;

class CapitalIncrease extends Model
{
    public function amounts()
    {
        return $this->hasMany(CapitalIncreasePercents::class);
    }
}
