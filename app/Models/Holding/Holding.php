<?php

namespace App\Models\Holding;

use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\Namad;

class Holding extends Model
{
    public function namads()
    {
        return $this->belongsToMany(Namad::class)->withPivot(['amount_percent','amount_value','change']);
    }
}
