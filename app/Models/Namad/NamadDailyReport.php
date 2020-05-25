<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\Namad;

class NamadDailyReport extends Model
{
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
}
