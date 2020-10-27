<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class Disclosures extends Model
{
    public function namad()
    {
        return $this->belongsTo(Namad::class,'namad_id');
    }
      public function readed_notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notificationable');
    }

}
