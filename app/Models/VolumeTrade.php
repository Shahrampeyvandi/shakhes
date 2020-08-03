<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class VolumeTrade extends Model
{


    protected $guarded  = ['id'];
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }

    public static function check($id) {
       $count = static::where('namad_id',$id)->where('created_at',Carbon::today())->count();
        if($count == 0) {
            return true;
        }else{
            return false;
        }
    }
}
