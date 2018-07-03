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

    const DISPONIBILIDAD = 'Disponibilidad';
    
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
    public function affiliate_records_pvt()
    {
        return $this->hasMany('Muserpol\Models\AffiliateRecord');
    }

    /**
     * methods
     */
    public function getDateEntry($size = 'short')
    {
        return Util::getDateFormat($this->date_entry, $size);
    }
    public function getDateDerelict($size = 'short')
    {
        return Util::getDateFormat($this->date_derelict, $size);
    }
    public function getDateEntryAvailability()
    {
        $availability = $this->getContributionsWithType(10);
        if (sizeOf($availability) > 0) {
            return Util::getDateFormat($availability[0]->start);
        }
        return '-';
    }
    public function fullName($style = "uppercase")
    {
        return Util::fullName($this, $style);
    }
    public function fullNameWithDegree($style = "uppercase")
    {
        return Util::removeSpaces(($this->degree->shortened ?? ''). ' '.Util::fullName($this, $style));
    }
    public function ciWithExt()
    {
        return Util::removeSpaces($this->identity_card . ' ' .$this->city_identity_card->first_shortened);
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
    /*contributions */
    public function getDatesContributions()
    {
        return $this->getContributionsWithType(1);
    }
    public function getDatesItemZeroWithContribution()
    {
        return $this->getContributionsWithType(2);
    }
    public function getDatesItemZeroWithoutContribution()
    {
        return $this->getContributionsWithType(3);
    }
    public function getDatesSecurityBattalionWithContribution()
    {
        return $this->getContributionsWithType(4);
    }
    public function getDatesSecurityBattalionWithoutContribution()
    {
        return $this->getContributionsWithType(5);
    }
    public function getDatesMay1976WithoutContribution()
    {
        return $this->getContributionsWithType(6);
    }
    public function getCertificationPeriodWithContribution()
    {
        return $this->getContributionsWithType(7);
    }
    public function getCertificationPeriodWithoutContribution()
    {
        return $this->getContributionsWithType(8);
    }
    public function getDatesNotWorked()
    {
        return $this->getContributionsWithType(9);
    }
    public function getDatesAvailability()
    {
        return $this->getContributionsWithType(10);
    }
    public function getDatesGlobal()
    {
        $date_start = $this->date_entry;
        $date_end = $this->date_derelict;
        $dates[] = (object)array(
            'start' => ($date_start < '1976-05-01') ? "1976-05-01" : $date_start,
            'end' => $date_end
        );
        return $dates;
    }
    public function getContributionsWithType($contribution_type_id)
    {
        $contribution_type = ContributionType::find($contribution_type_id);
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
            }
        return $dates;
    }
    public function getTotalContributionsAmount($name_contribution_type)
    {
        $contribution_type = ContributionType::where('name', '=', $name_contribution_type)->first();
        if(!$contribution_type)
        {
            return 'no existe el tipo de contribucion '.$name_contribution_type;
        }
        $contributions = $this->contributions()->where('contribution_type_id', '=', $contribution_type->id)->get();
        $total=0;
        foreach($contributions as $contribution){
            $total += $contribution->total;
        }
        return $total;
    }
    public function getTotalQuotes()
    {
        // $total_global_backed = Util::sumTotalContributions($this->getDatesGlobal());
        // $total_contributions_backed = Util::sumTotalContributions($this->getContributionsWithType('Período reconocido por comando'));
        // $total_item_zero_backed = Util::sumTotalContributions($this->getContributionsWithType('Período en item 0 Con Aporte'));
        // $total_availability_backed = Util::sumTotalContributions($this->getContributionsWithType('Disponibilidad'));
        // $total_security_battalion_backed = Util::sumTotalContributions($this->getContributionsWithType('Período de Batallón de Seguridad Física Con Aporte'));
        // $total_cas_backed = Util::sumTotalContributions($this->getContributionsWithType('Período Certificación Con Aporte'));
        // $total_no_records_backed = Util::sumTotalContributions($this->getContributionsWithType('Período no Trabajado'));
        // $total_quotes = ($total_global_backed ?? 0)
        //     - ($total_availability_backed ?? 0)
        //     - ($total_security_battalion_backed ?? 0)
        //     - ($total_cas_backed ?? 0)
        //     - ($total_no_records_backed ?? 0);
        
        // $c = ContributionType::find(1);
        $group_dates = [];
        $total_dates = Util::sumTotalContributions($this->getDatesGlobal());
        $dates = array(
            'id' => 0,
            'dates' => $this->getDatesGlobal(),
            'name' => "perii",
            'operator' => '**',
            'description' => "dsds",
            'years' => intval($total_dates / 12),
            'months' => $total_dates % 12,
        );
        $group_dates[] = $dates;
        foreach (ContributionType::orderBy('id')->get() as $c) {
            // if ($c->id != 1) {
                $contributionsWithType = $this->getContributionsWithType($c->id);
                if (sizeOf($contributionsWithType) > 0) {
                    if ($c->operator == '-') {
                        $sub_total_dates = Util::sumTotalContributions($contributionsWithType);
                        // $dates = array(
                        //     'id' => $c->id,
                        //     'dates' => $this->getContributionsWithType($c->id),
                        //     'name' => $c->name,
                        //     'operator' => $c->operator,
                        //     'description' => $c->shortened,
                        //     'years' => intval($sub_total_dates / 12),
                        //     'months' => $sub_total_dates % 12,
                        // );
                        // Log::info($total_dates ." " . $c->operator . " " . $sub_total_dates);
                        eval('$total_dates = ' . $total_dates . $c->operator . $sub_total_dates . ';');
                        $group_dates[] = $dates;
                    }
                }
            // }
        }
        $contributions = array(
            'contribution_types' => $group_dates,
            'years' => intval($total_dates / 12),
            'months' => $total_dates % 12
        );
        return $total_dates;
    }
    public function getTotalAverageSalaryQuotable()
    {
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
        // $availability = $this->getContributionsWithType(10);#disponibilidad

        // if (sizeOf($availability) > 0) {
            /* has availability */
            // $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();
            $contributions = $this->contributions()
                ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                // ->where("contribution_types.id", '=', 1)
                // ->where('contributions.month_year', '<=', $start_date_availability)
                ->where('contribution_types.operator','=', '+')
                ->orderBy('contributions.month_year', 'desc')
                ->take($number_contributions)
                ->get();
            $total_base_wage = $contributions->sum('base_wage');
            $total_seniority_bonus = $contributions->sum('seniority_bonus');
            $total_retirement_fund = $contributions->sum('retirement_fund');
            $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
            $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
        // } else {
                //si no tiene periodos en disponibilidad
            // $last_date_contribution = Carbon::parse(end($contributions)->end)->toDateString();
            // $contributions = $this->contributions()
                // ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                // ->where("contribution_types.id", '=', 1)
                // ->where('contributions.month_year', '<=', $last_date_contribution)
                // ->where('contribution_types.operator', '=', '+')
                // ->orderBy('contributions.month_year', 'desc')
                // ->take($number_contributions)
                // ->get();
        //     $total_base_wage = $contributions->sum('base_wage');
        //     $total_seniority_bonus = $contributions->sum('seniority_bonus');
        //     $total_retirement_fund = $contributions->sum('retirement_fund');
        //     $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
        //     $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
        // }
        $data = [
            'total_base_wage' => $total_base_wage,
            'total_seniority_bonus' => $total_seniority_bonus,
            'total_retirement_fund' => $total_retirement_fund,
            'sub_total_average_salary_quotable' => $sub_total_average_salary_quotable,
            'total_average_salary_quotable' => $total_average_salary_quotable,
        ];
        return $data;
    }
    // public function getLastDateContribution()
    // {
    //     $date = $this->contributions()->max('month_year');
    //     if ($date) {
    //         return $date;
    //     }
    //     Log::info('contributions not found');
    //     return 'error';
    // }
}
