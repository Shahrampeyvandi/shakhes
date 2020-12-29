<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class NamadsSeasonalReport extends Model
{
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
      public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notificationable');
    }
    public function get_label()
    {
        return 'سه ماهه ' . $this->season . ' ' . $this->year;
    }

}
