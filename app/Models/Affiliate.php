<?php

namespace Muserpol\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Muserpol\Helpers\Util;
use Muserpol\Models\Contribution\ContributionType;
use Carbon\Carbon;
use Log;
use DB;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\EconomicComplement\Devolution;
use Muserpol\Models\QuotaAidMortuary\QuotaAidProcedure;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Hashids\Hashids;
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
    'phone_number',
    'cell_phone_number',
    'nua',
    'is_duedate_undefined',
    'due_date'
  ];
  public function getBirthDateAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('d/m/Y');
  }
  public function getDueDateAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('d/m/Y');
  }
  public function getDateDeathAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('d/m/Y');
  }
  public function getDateEntryAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('m/Y');
  }
  public function getDateDerelictAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('m/Y');
  }
  public function address()
  {
    return $this->morphToMany('\Muserpol\Models\Address', 'addressable')->withTimestamps();
  }
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
    return $this->hasMany('Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary');
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
  public function records()
    {
      return $this->morphMany(Record::class, 'recordable');
    }
  public function activities()
  {
      return $this->hasMany(Activities::class);
  }
  public function testimony()
  {
    return $this->hasMany('Muserpol\Models\Testimony');
  }
  public function observations()
  {
    return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
  }

  public function hasObservationType($id)
  {
    return !!$this->observations()->where('observation_type_id', '=', $id)->first();
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
  public function getBirthDate($size = 'short')
  {
    $birth_date = Util::verifyBarDate($this->birth_date) ? Util::parseBarDate($this->birth_date) : $this->birth_date;
    return Util::getDateFormat($birth_date, $size);
  }
  public function getDateDeath($size = 'short')
  {
    $date_death = Util::verifyBarDate($this->date_death) ? Util::parseBarDate($this->date_death) : $this->date_death;
    return Util::getDateFormat($date_death, $size);
  }
  public function getDateEntryAvailability()
  {
    $availability = $this->getContributionsWithType(10);
    if (sizeOf($availability) > 0) {
      return Util::getDateFormat($availability[0]->start);
    }
    return '-';
  }
  public function getLastBaseWage()
  {
    $contributions = $this->contributions()
      ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
      ->where('contribution_types.operator', '=', '+')
      ->orderBy('contributions.month_year', 'desc')
      ->get();
    if ($contributions->count()) {
      return $contributions->first()->gain;
    }
    return null;
  }
  public function fullName($style = "uppercase")
  {
    return Util::fullName($this, $style);
  }
  public function fullNameWithDegree($style = "uppercase")
  {
    return Util::removeSpaces(($this->degree->shortened ?? '') . ' ' . Util::fullName($this, $style));
  }
  public function ciWithExt()
  {
    return Util::removeSpaces($this->identity_card . ' ' . ($this->city_identity_card->first_shortened ?? ''));
  }
  public function calcAge($text = false, $date_death = true)
  {
    if ($text) {
      return $date_death ? Util::calculateAge($this->birth_date, $this->date_death) : Util::calculateAge($this->birth_date, $date_death);
    }
    return $date_death ? Util::calculateAgeYears($this->birth_date, $this->date_death) : Util::calculateAgeYears($this->birth_date, $date_death);
  }
  public function getCivilStatus()
  {
    return Util::getCivilStatus($this->civil_status, $this->gender);
  }
  public function tags()
  {
    return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
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
    $date_start = Util::verifyMonthYearDate($this->date_entry) ? Util::parseMonthYearDate($this->date_entry) : $this->date_entry;
    $date_end = Util::verifyMonthYearDate($this->date_derelict) ? Util::parseMonthYearDate($this->date_derelict) : $this->date_derelict;
    $dates[] = (object)array(
      // 'start' => ($date_start < '1976-05-01' && ) ? "1976-05-01" : $date_start,
      'start' => $date_start,
      'end' => $date_end
    );
    return $dates;
  }
  public function getContributionsWithType($contribution_type_id)
  {
    $contribution_type = ContributionType::find($contribution_type_id);
    $dates = [];
    if (!$contribution_type) return "error";
    $contributions = $this->contributions()->where('contribution_type_id', '=', $contribution_type->id)->orderBy('month_year', 'asc')->get();
    if ($length = $contributions->count()) {
      $start = $contributions[0]->month_year;
      for ($i = 0; $i < $length - 1; $i++) {
        if ($i <= $length - 1) {
          if (Carbon::parse($contributions[$i]->month_year)->addMonth()->toDateString() == Carbon::parse($contributions[$i + 1]->month_year)->toDateString()) { } else {
            $dates[] = (object)array('start' => $start, 'end' => $contributions[$i]->month_year);
            $start = $contributions[$i + 1]->month_year;
          }
        }
      }
      $dates[] = (object)array('start' => $start, 'end' => $contributions[$i]->month_year);
    }
    return $dates;
  }
  public function getContributionsWithTypeQuotaAid($quota_aid_id = null)
  {
    $dates = [];
    $contributions = $this->getQuotaAidContributions($quota_aid_id)['contributions'];
    if ($length = sizeof($contributions)) {
      $start = $contributions[0]['month_year'];
      for ($i = 0; $i < $length - 1; $i++) {
        if ($i <= $length - 1) {
          if (Carbon::parse($contributions[$i]['month_year'])->addMonth()->toDateString() == Carbon::parse($contributions[$i + 1]['month_year'])->toDateString()) { } else {
            $dates[] = (object)array('start' => $start, 'end' => $contributions[$i]['month_year']);
            $start = $contributions[$i + 1]['month_year'];
          }
        }
      }
      $dates[] = (object)array('start' => $start, 'end' => $contributions[$i]['month_year']);
    }

    return $dates;
  }
  public function getTotalContributionsAmount($name_contribution_type)
  {
    $contribution_type = ContributionType::where('name', '=', $name_contribution_type)->first();
    if (!$contribution_type) {
      return 'no existe el tipo de contribucion ' . $name_contribution_type;
    }
    $contributions = $this->contributions()->where('contribution_type_id', '=', $contribution_type->id)->get();
    $total = 0;
    foreach ($contributions as $contribution) {
      $total += $contribution->total;
    }
    return $total;
  }
  public function getTotalQuotes()
  {
    // $total_global_backed = Util::sumTotalContributions($this->getDatesGlobal());
    // $total_contributions_backed = Util::sumTotalContributions($this->getContributionsWithType('Servicio Activo'));
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
  public function globalPayRetFun()
  {
    $current_procedure = Util::getRetFunCurrentProcedure();
    $number_contributions = $current_procedure->contributions_number;
    return $this->getTotalQuotes() < $number_contributions;
  }

  public function getQuotaAidContributions($quota_aid_id)
  {
    $null_data = [
      'is_continuous' => false,
      'contributions' => []
    ];
    if (!$date_death  = optional(optional($this->quota_aid_mortuaries()->where('code', 'not like', '%A')->where('id', $quota_aid_id)->orderBy('id', 'DESC')->first())->getDeceased())->date_death) {
      return $null_data;
    }
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    
    $number_contributions = QuotaAidProcedure::where('is_enabled', 'true')->where('id', $quota_aid->quota_aid_procedure_id)->first()->months;
    
    $date_death = Carbon::parse(Util::verifyBarDate($date_death)  ? Util::parseBarDate($date_death) : $date_death)->subMonth();
    if ($this->hasQuota()) {
      $contributions = $this->contributions()
        ->where('contributions.month_year', '<=', $date_death)
        ->where('total', '>', 0)
        ->orderByDesc('contributions.month_year')
        ->take($number_contributions)
        ->get()
        ->toArray();
    }
    if ($this->hasAid()) {
      $contributions = $this->aid_contributions()
        ->where('aid_contributions.month_year', '<=', $date_death)
        ->where('total', '>', 0)
        ->orderByDesc('aid_contributions.month_year')
        ->take($number_contributions)
        ->get()
        ->toArray();
    }
    $contributions = array_reverse($contributions);
    if (sizeof($contributions) == $number_contributions) {
      // checking continuity
      $is_continuous = true;
      $first_date = Carbon::parse($contributions[0]['month_year'])->subMonth();
      foreach ($contributions as $c) {
        if (Carbon::parse($c['month_year'])->toDateString() != Carbon::parse($first_date)->addMonth()->toDateString()) {
          $is_continuous = false;
          break;
        }
        $first_date = $c['month_year'];
      }
      $data = [
        'is_continuous' => $is_continuous,
        'contributions' => $contributions
      ];
      return $data;
    }
    return $null_data;
  }
  public function getContributionsPlus($with_reimbursements = true)
  {

    if ($this->selectedContributions() > 0 || $this->contributions()->count() == 0) {
      return [];
    }
    $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
    if ($with_reimbursements) {
      $contributions = DB::select("
            SELECT
                contributions_reimbursements.month_year,
                contributions_reimbursements.affiliate_id,
                sum(contributions_reimbursements.base_wage) as base_wage,
                sum(contributions_reimbursements.seniority_bonus) as seniority_bonus,
                sum(contributions_reimbursements.total) as total,
                sum(contributions_reimbursements.retirement_fund) as retirement_fund
                FROM(
                SELECT
                    reimbursements.id,
                reimbursements.affiliate_id,
                reimbursements.degree_id,
                reimbursements.unit_id,
                reimbursements.breakdown_id,
                reimbursements.month_year,
                reimbursements.type,
                reimbursements.base_wage,
                reimbursements.seniority_bonus,
                reimbursements.study_bonus,
                reimbursements.position_bonus,
                reimbursements.border_bonus,
                reimbursements.east_bonus,
                reimbursements.public_security_bonus,
                reimbursements.gain,
                reimbursements.payable_liquid,
                reimbursements.quotable,
                reimbursements.retirement_fund,
                reimbursements.mortuary_quota,
                reimbursements.subtotal,
                reimbursements.total
                    FROM reimbursements
                    WHERE affiliate_id = " . $this->id . "
		    and reimbursements.deleted_at is null
                    and month_year in (SELECT contributions.month_year
                                        FROM contributions
                                        LEFT JOIN contribution_types ON contributions.contribution_type_id = contribution_types.id
                                        WHERE contributions.affiliate_id = " . $this->id . " and  contributions.deleted_at is null and contribution_types.operator LIKE '+')
                    UNION ALL
                    SELECT
                    contributions.id,
                contributions.affiliate_id,
                contributions.degree_id,
                contributions.unit_id,
                contributions.breakdown_id,
                contributions.month_year,
                contributions.type,
                contributions.base_wage,
                contributions.seniority_bonus,
                contributions.study_bonus,
                contributions.position_bonus,
                contributions.border_bonus,
                contributions.east_bonus,
                contributions.public_security_bonus,
                contributions.gain,
                contributions.payable_liquid,
                contributions.quotable,
                contributions.retirement_fund,
                contributions.mortuary_quota,
                contributions.subtotal,
                contributions.total
                    FROM contributions
                    LEFT JOIN contribution_types ON contributions.contribution_type_id = contribution_types.id
                    WHERE affiliate_id = " . $this->id . " and  contributions.deleted_at is null and contribution_types.operator LIKE '+'
            ) as contributions_reimbursements
                GROUP BY contributions_reimbursements.month_year, contributions_reimbursements.affiliate_id
                ORDER BY month_year DESC
                LIMIT " . $number_contributions . "");
      return array_reverse($contributions);
    } else {
      $contributions = $this->contributions()
        ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
        // ->where("contribution_types.id", '=', 1)
        // ->where('contributions.month_year', '<=', $start_date_availability)
        ->where('contribution_types.operator', '=', '+')
        ->orderBy('contributions.month_year', 'desc')
        ->take($number_contributions)
        ->get();
      return $contributions;
      /* TODO verificar reverse order*/
    }
  }
  public function getContributionsAvailability($with_reimbursements = true)
  {
    if ($this->selectedContributions() > 0 ||  $this->contributions()->count() == 0) {
      return [];
    }
    $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
    if ($with_reimbursements) {
      $contributions = DB::select("
        SELECT
            contributions_reimburements.month_year,
            contributions_reimburements.affiliate_id,
            sum(contributions_reimburements.base_wage) as base_wage,
            sum(contributions_reimburements.seniority_bonus) as seniority_bonus,
            sum(contributions_reimburements.total) as total,
            sum(contributions_reimburements.retirement_fund) as retirement_fund
            FROM(
            SELECT
                reimbursements.id,
            reimbursements.affiliate_id,
            reimbursements.degree_id,
            reimbursements.unit_id,
            reimbursements.breakdown_id,
            reimbursements.month_year,
            reimbursements.type,
            reimbursements.base_wage,
            reimbursements.seniority_bonus,
            reimbursements.study_bonus,
            reimbursements.position_bonus,
            reimbursements.border_bonus,
            reimbursements.east_bonus,
            reimbursements.public_security_bonus,
            reimbursements.gain,
            reimbursements.payable_liquid,
            reimbursements.quotable,
            reimbursements.retirement_fund,
            reimbursements.mortuary_quota,
            reimbursements.subtotal,
            reimbursements.total
                FROM reimbursements
                WHERE affiliate_id = " . $this->id . " and reimbursements.deleted_at is null  and month_year in (SELECT contributions.month_year
                                                FROM contributions where contributions.affiliate_id = " . $this->id . " and contribution_type_id = 10)
                UNION ALL
                SELECT
                contributions.id,
            contributions.affiliate_id,
            contributions.degree_id,
            contributions.unit_id,
            contributions.breakdown_id,
            contributions.month_year,
            contributions.type,
            contributions.base_wage,
            contributions.seniority_bonus,
            contributions.study_bonus,
            contributions.position_bonus,
            contributions.border_bonus,
            contributions.east_bonus,
            contributions.public_security_bonus,
            contributions.gain,
            contributions.payable_liquid,
            contributions.quotable,
            contributions.retirement_fund,
            contributions.mortuary_quota,
            contributions.subtotal,
            contributions.total
                FROM contributions
                LEFT JOIN contribution_types ON contributions.contribution_type_id = contribution_types.id
                WHERE affiliate_id = " . $this->id . " and contributions.deleted_at is null and contribution_types.id = 10
        ) as contributions_reimburements
            GROUP BY month_year, affiliate_id
            ORDER BY month_year DESC");
      return array_reverse($contributions);
    } else {
      return "error.... working........";
    }
  }
  public function getTotalAverageSalaryQuotable($with_reimbursements = true)
  {
    $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
    // $availability = $this->getContributionsWithType(10);#disponibilidad

    // if (sizeOf($availability) > 0) {
    /* has availability */
    // $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();

    if ($with_reimbursements) {
      $contributions = self::getContributionsPlus();
      $total_base_wage = array_sum(array_column($contributions, 'base_wage'));
      $total_seniority_bonus = array_sum(array_column($contributions, 'seniority_bonus'));
      $total_aporte = array_sum(array_column($contributions, 'total'));
      $total_retirement_fund = array_sum(array_column($contributions, 'retirement_fund'));
    } else {
      $contributions = self::getContributionsPlus(false);
      $total_base_wage =  $contributions->sum('base_wage');
      $total_seniority_bonus = $contributions->sum('seniority_bonus');
      $total_aporte = $contributions->sum('total');
      $total_retirement_fund = $contributions->sum('retirement_fund');
    }

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
      'contributions' => $contributions,
      'total_base_wage' => $total_base_wage,
      'total_seniority_bonus' => $total_seniority_bonus,
      'total_aporte' => $total_aporte,
      'total_retirement_fund' => $total_retirement_fund,
      'sub_total_average_salary_quotable' => $sub_total_average_salary_quotable,
      'total_average_salary_quotable' => $total_average_salary_quotable,
    ];
    return $data;
  }
  public function hasAvailability()
  {
    return sizeOf($this->getContributionsWithType(10)) > 0;
  }
  public function selectedContributions()
  {
    return $this->contributions()->where('contribution_type_id', '=', null)->get()->count();
  }
  public function hasAid()
  {
    return $this->mortuary_aids()->count() > 0;
  }
  public function hasQuota()
  {
    return $this->mortuary_quotas()->count() > 0;
  }
  public function mortuary_quotas()
  {
    return $this->quota_aid_mortuaries()
      ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
      ->leftJoin('procedure_types', 'procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
      ->where('procedure_types.id', 3)
      ->where('quota_aid_mortuaries.procedure_state_id', '<>', 3)
      ->select('quota_aid_mortuaries.*')
      ->get();
  }
  public function mortuary_aids()
  {
    return $this->quota_aid_mortuaries()
      ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
      ->leftJoin('procedure_types', 'procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
      ->where('procedure_types.id', 4)
      ->where('quota_aid_mortuaries.procedure_state_id', '<>', 3)
      ->select('quota_aid_mortuaries.*')
      ->get();
  }
  // public function getLastDateContribution()
  // {
  //     $date = $this->contributions()->max('month_year');
  //     if ($date) {
  //         return $date;
  //     }
  //     return 'error';
  // }
  public static function updatePersonalInfo($affiliate_id, $object)
  {
    $affiliate = Affiliate::find($affiliate_id);
    $affiliate->identity_card = $object['identity_card'];
    $affiliate->city_identity_card_id = $object['city_identity_card_id'];
    $affiliate->first_name = $object['first_name'];
    $affiliate->second_name = $object['second_name'];
    $affiliate->last_name = $object['last_name'];
    $affiliate->mothers_last_name = $object['mothers_last_name'];
    $affiliate->gender = $object['gender'];
    if ($object['gender'] == 'F') {
      $affiliate->surname_husband = $object['surname_husband'];
    }
    $affiliate->phone_number = implode(',', $object['phone_number']);
    $affiliate->cell_phone_number = implode(',', $object['cell_phone_number']);
    $affiliate->birth_date = Util::verifyBarDate($object['birth_date']) ? Util::parseBarDate($object['birth_date']) : $object['birth_date'];
    $affiliate->save();
  }
  public static function updateSpouse($affiliate_id, $object)
  {
    $affiliate = Affiliate::find($affiliate_id);
    Spouse::updatePersonalInfo($affiliate_id, $object);
  }

  /**
   * Economic Complements
   */
  public function economic_complements()
  {
    return $this->hasMany('Muserpol\Models\EconomicComplement\EconomicComplement');
  }

  public function hasEcoComProcessActive()
  {
    return !!$this->eco_com_processes()->where('status', true)->get()->count();
  }
  public function hasEconomicComplementWithProcedure($eco_com_procedure_id)
  {
    return !!$this->economic_complements()->where('eco_com_procedure_id', $eco_com_procedure_id)->get()->count();
  }
  public function canCreateEcoComProcedure($eco_com_procedure_id)
  {
    /**
     *!! TODO
     *!! verificar si se puede crear Trámite en esa fecha
     ** mmmm date_derelict < start date procedure
     */
    // return rand(0,1) == 1;
    return true;
  }
  public function devolutions()
  {
    return $this->hasMany(Devolution::class);
  }
  public function encode()
  {
      $hashids = new Hashids('affiliates', 10);
      return $hashids->encode($this->id);
  }
  public function decode($hash)
  {
      $hashids = new Hashids('affiliates', 10);
      $id = $hashids->decode($hash);
      if ($id) {
          return $id[0];
      }
      return null;
  }
  public function scopeAffiliateinfo($query)
  {
    return $query->leftJoin('cities as affiliate_city', 'affiliates.city_identity_card_id', '=', 'affiliate_city.id')
        ->leftJoin('pension_entities', 'affiliates.pension_entity_id', '=', 'pension_entities.id');
  }
  public function scopeObservationType($query)
  {
    return $query->leftJoin('observables', 'affiliates.id', 'observables.observable_id')
    ->where('observables.observable_type', 'like', 'affiliates')
    ->leftJoin('observation_types', 'observables.observation_type_id', '=', 'observation_types.id');
  }
  public static function basic_info_colums()
    {
        return "
        row_number() OVER () AS NRO,
        affiliates.id as NUP,
        affiliates.identity_card as ci_causa,
        affiliate_city.first_shortened as exp_causa,
        concat_ws(' ', affiliates.identity_card,affiliate_city.first_shortened) as ci_completo_causa,
        affiliates.first_name as primer_nombre_causahabiente,
        affiliates.second_name as segundo_nombre_causahabiente,
        affiliates.last_name as ap_paterno_causahabiente,
        affiliates.mothers_last_name as ap_materno_causahabiente,
        affiliates.surname_husband as ape_casada_causahabiente,
        affiliates.birth_date as fecha_nacimiento,
        affiliates.nua as codigo_nua_cua";
    }

    public function device() {
        return $this->hasOne(AffiliateDevice::class, 'affiliate_id', 'id', 'affiliate_devices');
    }
  //obtener si el afiliado ha dejado de sol consecutivamente mas de 2 tramites de eco_com
  public function stop_eco_com_consecutively() {
    $eco_com_procedures = $this->economic_complements()->select('eco_com_procedures.id','year','semester')->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')->orderBy('eco_com_procedures.year')->orderBy('eco_com_procedures.semester')->pluck('eco_com_procedures.id');
    $all_procedures = EcoComProcedure::orderBy('year','asc')->orderBy('semester','asc')->pluck('id');
    $count_consecutives=0;

    $position_eco_last = $eco_com_procedures->count()-1;
    $position_eco = 0;
    $find_firts_eco =$eco_com_procedures->first();

    $position_procedure_last = $all_procedures->count()-1;
    $position_procedure = 0;
    $firts_eco =false;

    while ($position_eco <= $position_eco_last) {
      if($all_procedures[$position_procedure] == $find_firts_eco)
        $firts_eco = true;

      if($firts_eco== true){
        if($eco_com_procedures[$position_eco] == $all_procedures[$position_procedure]){
          $position_eco++;
          if($count_consecutives<=2)
            $count_consecutives = 0;
        }else{
          $count_consecutives++;
        }
      }
      $position_procedure =  $position_procedure+1;
    }

    return $count_consecutives<=2? false:true;
  }
}
