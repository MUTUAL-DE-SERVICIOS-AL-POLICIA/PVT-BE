<?php

namespace Muserpol\Models\QuotaAidMortuary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
class QuotaAidAdvisor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identity_card',
        'last_name',
        'mothers_last_name',
        'first_name',
        'second_name',
        'surname_husband',
        'birth_day',
        'gender', 
        'type',
        'name_court',
        'resolution_number', 
        'resolution_date',
        'phone_number',
        'cel_phone_number',
    ];
    public function city_identity_card()    
    {
        return $this->belongsTo('Muserpol\Models\City', 'city_identity_card_id');
    }
    public function kinship()
    {
        return $this->belongsTo('Muserpol\Models\Kinship');
    }
    public function quota_aid_beneficiary()
    {
        return $this->belongsToMany('Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary', 'quota_aid_advisor_beneficiary', 'quota_aid_beneficiary_id', 'quota_aid_advisor_id');
    }
    public function getResolutionDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    public function getBirthDateAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }
    /**
     * Methods
     */
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
}
