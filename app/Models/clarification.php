<?php

namespace App\Models;

use App\Http\Traits\CommonRelations;
use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class clarification extends Model
{
    use CommonRelations;
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
     

}
