<?php

namespace Muserpol\Models\QuotaAidMortuary;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }
    public function kinship()
    {
        return $this->belongsTo('Muserpol\Models\Kinship');
    }
}
