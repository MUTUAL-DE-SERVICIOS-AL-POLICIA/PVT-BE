<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Models\Degree;
use Muserpol\Models\ProcedureModality;
use Muserpol\User;

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
        'referencial_limit'
    ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
        public function degree()
        {
            return $this->belongsTo(Degree::class);
        }
    
        public function procedureModality()
        {
            return $this->belongsTo(ProcedureModality::class);
        }
}