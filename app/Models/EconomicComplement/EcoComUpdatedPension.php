<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

class EcoComUpdatedPension extends Model
{
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function economic_complement()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EconomicComplement');
    }
    public function calculateTotalRentAps()
    {
        $this->total_rent = $this->aps_total_death +  $this->aps_disability + $this->aps_total_cc + $this->aps_total_fsa + $this->aps_total_fs;
        $this->save();
    }
}
