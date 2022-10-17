<?php

namespace Muserpol\Models\Notification;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\Module;

class NotificationCarrier extends Model
{
    public function module() {
        // return $this->belongsTo('Muserpol\Models\Module');
        return $this->belongsTo(Module::class);
    }

    public function send() {
        return $this->hasOne(NotificationSend::class);
    }

    public function sends(){
        return $this->morphMany(NotificationSend::class, 'sendable');
    }
}
