<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Muserpol\Helpers\Util;
use Muserpol\Models\Contribution\ContributionType;
use Carbon\Carbon;
use Log;

class Affiliate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'affiliate_state_id',
        'city_identity_card_id',
        'city_birth_id',
        'degree_id',
        'unit_id',
        'category_id',
        'pension_entity_id',
        'identity_card',
        'registration',
        'type',
        'last_name',
        'mothers_last_name',
        'first_name',
        'second_name',
        'surname_husband',
        'civil_status',
        'gender',
        'birth_date',
        'date_entry',
        'date_death',
        'reason_death',
        'date_derelict',
        'reason_derelict',
        'change_date',
        'phone_number',
        'cell_phone_number',
        'afp',
        'nua',
        'item'
    ];

    public function spouse()
    {
        return $this->hasMany('Muserpol\Models\Spouse');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city_identity_card()
    {
        return $this->belongsTo(City::class, 'city_identity_card_id', 'id');
    }

    public function city_birth()
    {
        return $this->belongsTo(City::class, 'city_birth_id', 'id');
    }

    public function contributions()
    {
        return $this->hasMany('Muserpol\Models\Contribution\Contribution'::class);
    }

    public function reimbursements()
    {
        return $this->hasMany('Muserpol\Models\Contribution\Reimbursement'::class);
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }
    public function affiliate_state()
    {
        return $this->belongsTo(AffiliateState::class);
    }
    public function pension_entity()
    {
        return $this->belongsTo(PensionEntity::class);
    }
    
    public function retirement_funds()
    {
        return $this->hasMany('Muserpol\Models\RetirementFund\RetirementFund');
    }

    public function affiliate_folders()
    {
        return $this->hasMany('Muserpol\Models\AffiliateFolder');
    }
    public function quota_aid_mortuaries()
    {
        return $this->hasMany('Muserpol\Models\QuotaAidMortuaries\QuotaAidMortuary');
    }

    public function aid_commitments()
    {
        return $this->hasMany('Muserpol\Models\Contribution\AidCommitment');
    }
    public function aid_contributions()
    {
        return $this->hasMany('Muserpol\Models\Contribution\AidContribution');
    }

    /**
     * methods
     */
    public function getDateEntry($size = 'short')
    {
        return Util::getDateFormat($this->date_entry, $size);
    }
    public function getDateEntryDisponibilidad()
    {
        return 'Coming soon ';
    }
    public function fullName()
    {
        $name = $this->first_name.' '.$this->second_name.' '.$this->last_name.' '.$this->mothers_last_name.' '.$this->applicant_surname_husband;
        return Util::removeSpaces($name);
    }

    public function calcAge($text = false, $date_death = true)
    {
        if ($text) {
            return $date_death ? Util::calculateAge($this->birth_date, $this->date_death) : Util::calculateAge($this->birth_date, $date_death) ;
        }
        return $date_death ? Util::calculateAgeYears($this->birth_date, $this->date_death) : Util::calculateAgeYears($this->birth_date, $date_death);
    }
    public function getCivilStatus()
    {
        return Util::getCivilStatus($this->civil_status, $this->gender);
    }
    public function getDatesContributions()
    {
        return $this->getContributionsWithType('Servicio');
    }
    public function getDatesAvailability()
    {
        return $this->getContributionsWithType('Disponibilidad');
    }
    public function getDatesItemZero()
    {
        return $this->getContributionsWithType('Item 0');
    }
    public function getDatesSecurityBattalion()
    {
        return $this->getContributionsWithType('Batallon de Seguridad Fisica');
    }
    public function getDatesNoRecords()
    {
        return $this->getContributionsWithType('No Hay Registro');
    }
    public function getDatesCas()
    {
        return $this->getContributionsWithType('Registro Segun CAS');
    }
    public function getContributionsWithType($name_contribution_type)
    {
        $contribution_type = ContributionType::where('name', '=', $name_contribution_type)->first();
        $dates=[];
        if (!$contribution_type) return "error";
        $contributions = $this->contributions()->where('contribution_type_id', '=', $contribution_type->id)->orderBy('month_year', 'asc')->get();
            if ($length = $contributions->count()) {
                $start = $contributions[0]->month_year;
                for ($i=0; $i < $length - 1; $i++) {
                    if ( $i <= $length -1 ) {
                        if (Carbon::parse($contributions[$i]->month_year)->addMonth()->toDateString() == Carbon::parse($contributions[$i+1]->month_year)->toDateString()) {
                        }else{
                            $dates[] = (object)array('start' => $start, 'end' => $contributions[$i]->month_year);
                            $start = $contributions[$i+1]->month_year;
                        }
                    }
                }
                $dates[] = (object) array('start' => $start, 'end' => $contributions[$i]->month_year);
                // dd($contributions->pluck('month_year'),$dates);
                // dd($dates);
            }
        return $dates;
    }
}
