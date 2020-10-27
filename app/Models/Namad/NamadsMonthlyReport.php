<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class NamadsMonthlyReport extends Model
{
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
      public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notificationable');
    }

}
