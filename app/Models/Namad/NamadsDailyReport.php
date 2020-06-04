<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\Namad;

class NamadsDailyReport extends Model
{
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
}
