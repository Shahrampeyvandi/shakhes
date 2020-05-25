<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class NamadSeasonalReport extends Model
{
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
}
