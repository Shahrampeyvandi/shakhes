<?php

namespace App\Models\Namad;

use App\Http\Traits\CommonRelations;
use Illuminate\Database\Eloquent\Model;

class Disclosures extends Model
{
    use CommonRelations;
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
   

}
