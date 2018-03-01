<?php

namespace Muserpol\Models\RetirementFund;

use Illuminate\Database\Eloquent\Model;
use Muserpol\Helpers\Util;

class RetFunLegalGuardian extends Model
{
    /**
     * Methods
     */
    public function fullName()
    {
        $name = $this->first_name . ' ' . $this->second_name . ' ' . $this->last_name . ' ' . $this->mothers_last_name . ' ' . $this->applicant_surname_husband;
        return Util::removeSpaces($name);
    }
}
