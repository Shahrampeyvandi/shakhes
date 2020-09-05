<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class VolumeTrade extends Model
{


    protected $guarded  = ['id'];
    protected $table = 'volume_trades';
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }

    public static function check($id) {
       $count = static::where('namad_id',$id)->count();
        if($count == 0) {
            return true;
        }else{
            return false;
        }
    }

    public function new()
    {
       return $this->created_at->isToday() ? true : false ;
    }
}
