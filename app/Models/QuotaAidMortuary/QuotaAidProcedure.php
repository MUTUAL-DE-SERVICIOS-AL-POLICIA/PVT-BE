<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

class QuotaAidProcedure extends Model
{
    //
    public function hierarchy()
    {
        return $this->belongsTo('Muserpol\Models\Hierarchy');
    }
    public function procedure_modality()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureModality');
    }
    public function quota_aid_mortuaries()
	{
		return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary');
    }
}
