<?php

namespace App\Models;

use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class VolumeTrade extends Model
{
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
}