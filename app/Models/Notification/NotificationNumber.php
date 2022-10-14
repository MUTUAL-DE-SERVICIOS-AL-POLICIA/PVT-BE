<?php

namespace Muserpol\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class NotificationNumber extends Model
{
    public function send(){
        return $this->hasOne(NotificationSend::class);
    }

    public function sends(){
        return $this->morphMany(NotificationSend::class, 'sendable');
    }
}
