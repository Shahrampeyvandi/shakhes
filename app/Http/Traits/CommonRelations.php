<?php
namespace App\Http\Traits;

use App\Models\Notification;
use Morilog\Jalali\Jalalian;

trait CommonRelations {

   public function readed_notifications()
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }
    

    public function get_codal_time()
    {
        if($this->codal_date) {
            return explode(':',explode(' ',$this->codal_date)[1])[0] . ':' . explode(':',explode(' ',$this->codal_date)[1])[1];
        }
        return $this->created_at->format('H:i');
    }
     public function get_codal_date()
    {
        if($this->codal_date) {
            return explode(' ',$this->codal_date)[0];
        }
        return $this->created_at->format('Y/m/d');
    }

     public function get_current_date_shamsi($timestamp)
    {
        return Jalalian::forge($timestamp)->format('Y-m-d H:i');
    }
}