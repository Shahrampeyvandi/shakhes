<?php

namespace App\Models\CapitalIncrease;

use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class CapitalIncrease extends Model
{
    public function amounts()
    {
        return $this->hasMany(CapitalIncreasePercents::class);
    }

    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
}
