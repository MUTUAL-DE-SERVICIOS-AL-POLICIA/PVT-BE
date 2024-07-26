<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComRent extends Model
{
    protected $fillable = [
        'user_id',
        'degree_id',
        'year',
        'semester',
        'minor',
        'higher',
        'average',
        'procedure_modality_id',
    ];
}
