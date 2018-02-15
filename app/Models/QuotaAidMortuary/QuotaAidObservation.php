<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

class QuotaAidObservation extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function quota_aid_mortuary()
    {
        return $this->belongsTo('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary');
    }   
}
