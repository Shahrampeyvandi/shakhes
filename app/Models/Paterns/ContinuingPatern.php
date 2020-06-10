<?php

namespace App\Models\Paterns;

use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class ContinuingPatern extends Model
{
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
}
