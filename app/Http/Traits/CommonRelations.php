<?php
namespace App\Http\Traits;

use App\Models\Notification;

trait CommonRelations {

   public function readed_notifications()
    {
        return $this->morphMany(Notification::class, 'notificationable');
    }
}