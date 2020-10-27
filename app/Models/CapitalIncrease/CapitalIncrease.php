<?php

namespace App\Models\CapitalIncrease;

use App\Models\Namad\Namad;
use Illuminate\Database\Eloquent\Model;

class CapitalIncrease extends Model
{
    public function amounts()
    {
        return $this->hasMany(CapitalIncreasePercents::class);
    }
    
     public function readed_notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notificationable');
    }

    public function namad()
    {
        return $this->belongsTo(Namad::class, 'namad_id');
    }

    public function get_percent($from)
    {

        $item = $this->amounts()->where('type', $from)->first();
        if ($item) {
            return $item->percent;
        }
        return null;
    }

    public function member_selected()
    {
        # code...
    }

    public function showPercents()
    {
        if (count($this->amounts) == 0) {
            $array["percent_from_cash"] = 0;
            $array["percent_from_stored_gain"] = 0;
            $array["percent_from_assets"] = 0;
        }

        $array["percent_from_cash"] = 0;
        $array["percent_from_stored_gain"] = 0;
        $array["percent_from_assets"] = 0;
        $total = 0;
        foreach ($this->amounts as $key => $amount) {
            $total += $amount->percent;
        }

        foreach ($this->amounts as $key => $amount) {
            $array["percent_from_$amount->type"] = ($amount->percent * 100) / $total;
        }

        return $array;
    }

    public function new()
    {
        return $this->created_at->isToday() ? true : false;
    }
}
