<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class Disclosures extends Model
{
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
}
