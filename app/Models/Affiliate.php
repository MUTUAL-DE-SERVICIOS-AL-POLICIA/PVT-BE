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
use Muserpol\Models\AffiliateToken;
use Illuminate\Support\Facades\Storage;
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
    'date_last_contribution',
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
  public function getDateLastContributionAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('m/Y');
  }
  public function getDateEntryReinstatementAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('m/Y');
  }
  public function getDateDerelictReinstatementAttribute($value)
  {
    if (!$value) {
      return null;
    }
    return Carbon::parse($value)->format('m/Y');
  }
  public function getDateLastContributionReinstatementAttribute($value)
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

  public function contributionsInRange($reinstatement = false)
  {
    $dateEntry = $reinstatement
      ? $this->date_entry_reinstatement
      : $this->date_entry;

    $dateLastContribution = $reinstatement
      ? $this->date_last_contribution_reinstatement
      : $this->date_last_contribution;

    if (empty($dateEntry)) {
      return $this->contributions();
    }

    // '!' resetea los campos no especificados día, hora para evitar overflow de fechas ej. febrero → marzo
    $date_start = Carbon::createFromFormat('!m/Y', $dateEntry)->startOfMonth();
    if (empty($dateLastContribution)) {
      $date_end = Carbon::now()->startOfMonth();
    } else {
      $date_end = Carbon::createFromFormat('!m/Y', $dateLastContribution)->startOfMonth();
    }

    return $this->contributions()->whereBetween('month_year', [$date_start, $date_end]);
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
  public function financial_entity()
  {
    return $this->belongsTo(FinancialEntity::class);
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
  public function eco_com_fixed_pensions()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComFixedPension');
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
  public function getDateLastContributionDate($size = 'short')
  {
    return Util::getDateFormat($this->date_last_contribution, $size);
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
    $availability = $this->getContributionsWithTypes(12,13);
    if (sizeOf($availability) > 0) {
      //return Util::getDateFormat($availability[0]->start);
      return $availability[0]->start;
    }
    return '-';
  }
  public function getDateFinishAvailability()
  {
    $availability = $this->getContributionsWithTypes(12,13);
    if (sizeOf($availability) > 0) {
      //return Util::getDateFormat($availability[sizeOf($availability)-1]->end);
      return $availability[sizeOf($availability)-1]->end;
    }
    return '-';
  }
  public function getDateIntervalAvailability()
  {
      $total_dates= $this->getDatesTotalAvailability();
      $contributions = array(
        'years' => intval($total_dates / 12),
        'months' => $total_dates % 12
    );
    return $contributions;
  }
  /**
   * Obtiene el último salario base (gain) registrado dentro del rango de contribuciones.
   *
   * Busca los aportes del afiliado filtrando únicamente
   * las que tienen aportes positivos ("+"). Devuelve el valor mas reciente encontrado
   * en base al campo `month_year`.
   *
   * @param  bool  $reinstatement  Indica si se debe usar el rango de reinstatement (reincorporación) o el normal.
   * @return float|null            Retorna el último salario base o null si no existe.
   */
  public function getLastBaseWage(bool $reinstatement = false): ?float
  {
    $contribution = $this->contributionsInRange($reinstatement)
      ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
      ->where('contribution_types.operator', '=', '+')
      ->orderBy('contributions.month_year', 'desc')
      ->first();

    return $contribution ? (float) $contribution->gain : null;
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
    return $this->getContributionsWithType(12);
  }
  public function getDatesWithoutAvailability()
  {
    return $this->getContributionsWithType(13);
  }
  public function getDatesTotalAvailability()
  {
    return Util::sumTotalContributions($this->getDatesWithoutAvailability())+(Util::sumTotalContributions($this->getDatesAvailability()));
  }
  public function getDatesGlobal($reinstatement = false)
  {
    $start = $reinstatement ? $this->date_entry_reinstatement : $this->date_entry;
    $end = $reinstatement ? $this->date_last_contribution_reinstatement : $this->date_last_contribution;
    $date_start = Util::verifyMonthYearDate($start) ? Util::parseMonthYearDate($start) : $start;
    $date_end = Util::verifyMonthYearDate($end) ? Util::parseMonthYearDate($end) : $end;
    $dates[] = (object)array(
      'start' => $date_start,
      'end' => $date_end
    );
    return $dates;
  }
  public function getContributionsWithType($contribution_type_id, $reinstatement = false)
  {
    $contribution_type = ContributionType::find($contribution_type_id);
    $dates = [];
    if (!$contribution_type) return "error";
    $contributions = $this->contributionsInRange($reinstatement)->where('contribution_type_id', '=', $contribution_type->id)->orderBy('month_year', 'asc')->get();
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
  public function getContributionsWithTypeArray($contribution_type_ids, $reinstatement = false): array
  {
    $contribution_dates = [];

    if (empty($contribution_type_ids)) {
      return [];
    }

    $contributions = $this->contributionsInRange($reinstatement)
      ->whereIn('contribution_type_id', $contribution_type_ids)
      ->orderBy('month_year', 'asc')
      ->get();

    foreach ($contribution_type_ids as $contribution_type_id) {
      $dates = [];
      $contribution_type = $contributions->where('contribution_type_id', $contribution_type_id)->values();
      if ($contribution_type->isEmpty()) {
        continue;
      }
     
      $length = $contribution_type->count();
      $start = $contribution_type[0]->month_year;
      for ($i = 0; $i < $length - 1; $i++) {
        $currentDate = Carbon::parse($contribution_type[$i]->month_year);
        $nextDate = Carbon::parse($contribution_type[$i + 1]->month_year);

        // Si el siguiente mes no es consecutivo, cerramos el rango
        if ($currentDate->copy()->addMonth()->toDateString() != $nextDate->toDateString()) {
          $dates[] = (object) [
            'start' => $start,
            'end'   => $contribution_type[$i]->month_year,
          ];
          $start = $contribution_type[$i + 1]->month_year;
        }
      }
      // Último rango
      $dates[] = (object) [
        'start' => $start,
        'end'   => $contribution_type[$length - 1]->month_year,
      ];
      $contribution_dates[$contribution_type_id] = $dates;
    }

    return $contribution_dates;
  }
  public function getContributionsWithTypes($contribution_type_id_s,$contribution_type_id_e)
  {
    $contribution_type_start = ContributionType::find($contribution_type_id_s)->id;
    $contribution_type_end = ContributionType::find($contribution_type_id_e)->id;
    $contribution_types= [$contribution_type_start,$contribution_type_end];
    $dates = [];
    if (!$contribution_type_start && !$contribution_type_end) return "error";
    $contributions = $this->contributions()->whereIn('contribution_type_id',$contribution_types)->orderBy('month_year', 'asc')->get();
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
    $contributions = $this->getQuotaAidContributions2($quota_aid_id)['contributions_print'];
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
  public function getTotalContributionsAmount($name_contribution_type) // sin usar
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
  /**
   * Calcula el total de aportes validos.
   *
   * Este método obtiene todos los aportes desde la fecha de ingreso hasta 
   * fecha del ultimo aporte, y resta los aportes con clasificación negativa. 
   *
   * @param  bool  $reinstatement  Indica si se deben considerar el periodo de reincorporación. 
   * @return int  Retorna el número total de aportes validos.
   *
   */
  public function getTotalQuotes($reinstatement = false): int
  {
    $total_dates = Util::sumTotalContributions($this->getDatesGlobal($reinstatement));
    $contribution_types = ContributionType::where('operator', '-')->orderBy('id')->get();
    $contributionsWithType = $this->getContributionsWithTypeArray($contribution_types->pluck('id'), $reinstatement);

    foreach ($contributionsWithType as $c) {
      $sub_total_dates = Util::sumTotalContributions($c);
      $total_dates = $total_dates - $sub_total_dates;
    }
    return $total_dates;
  }
  public function getLastContributionAttribute(){ // sin usar
    return $this->contributions()->latest('month_year')->first();
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
    
    $number_contributions = 12;
    
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
        ->where('contribution_passives.month_year', '<=', $date_death)
        ->where('total', '>', 0)
        ->orderByDesc('contribution_passives.month_year')
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
  //--------cuota y auxilio mortuorio
  public function getQuotaAidContributions2($quota_aid_id)//estamos aqui
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);

    $date_min = $quota_aid->affiliate->getIntervalQualificationQuotaAid($quota_aid_id)['start_date_min'];
    $date_max = $quota_aid->affiliate->getIntervalQualificationQuotaAid($quota_aid_id)['end_date_max'];
    $min_limit = Carbon::parse($quota_aid->affiliate->getIntervalQualificationQuotaAid($quota_aid_id)['start_min_limit'])->format('Y-m-d');
    $max_limit = Carbon::parse($quota_aid->affiliate->getIntervalQualificationQuotaAid($quota_aid_id)['end_max_limit'])->format('Y-m-d');

    $number_contributions=12;
    $null_data = [
      'is_continuous' => false,
      'contributions_print'=>collect(),
      'contributions' => [],
    ];
    if( is_null($date_min) || is_null($date_max) ){
      return $null_data;
    }

    if ($quota_aid->isQuota()) {
      if ($quota_aid->getTypeMortuary() == 'Conyuge') {//Conyugue
        $contributions = $this->contributions()
          ->whereNotNull('contributions.contribution_type_mortuary_id')
          ->where('contributions.contribution_type_mortuary_id', 1)
          ->where('contributions.month_year', '>=', $min_limit)
          ->where('contributions.month_year', '<', $max_limit)
          ->orderBy('contributions.month_year','desc')
          ->take($number_contributions)
          ->get();
      } else {
        $contributions = $this->contributions()
          ->whereNotNull('contributions.contribution_type_mortuary_id')
          ->where('contributions.contribution_type_mortuary_id', 1)
          ->where('contributions.month_year', '>=', $min_limit)
          ->where('contributions.month_year', '<', $max_limit)
          ->orderBy('contributions.month_year')
          // ->take($number_contributions)
          ->get();
        //->toArray();
      }
    }

    if ($quota_aid->isAid()) {
      if($quota_aid->procedure_modality_id == 13){//titular
        $contributions = $this->aid_contributions()
        ->where('contribution_passives.affiliate_rent_class','ilike','%VEJEZ%')
        ->whereNotNull('contribution_passives.contribution_type_mortuary_id')
        ->where('contribution_passives.contribution_type_mortuary_id',1)
        ->where('contribution_passives.month_year','>=',$min_limit)
        ->where('contribution_passives.month_year','<',$max_limit)
        //->where('total', '>', 0)
        ->orderBy('contribution_passives.month_year')
        //->take($number_contributions)
        ->get();
        //->toArray();
      }
      if($quota_aid->procedure_modality_id == 14){//Conyugue
        $contributions = $this->aid_contributions()
        ->where('contribution_passives.affiliate_rent_class','ilike','%VEJEZ%')
        //->where('contribution_passives.month_year','>=',$min_limit)
        ->where('contribution_passives.month_year','<',$max_limit)
        ->where('total', '>', 0)
        ->orderBy('contribution_passives.month_year','desc')
        ->take($number_contributions)
        ->get()
        ->sortBy('month_year');
        //->toArray();
      }
      if($quota_aid->procedure_modality_id == 15){//vuda
        $contributions = $this->aid_contributions()
        ->where('contribution_passives.affiliate_rent_class','ilike','%VIUDEDAD%')
        ->whereNotNull('contribution_passives.contribution_type_mortuary_id')
        ->where('contribution_passives.contribution_type_mortuary_id',1)
        ->where('contribution_passives.month_year','>=',$min_limit)
        ->where('contribution_passives.month_year','<',$max_limit)
        // ->where('total', '>', 0)
        ->orderBy('contribution_passives.month_year')
        // ->take($number_contributions)
        ->get();
        //->toArray();
      }
    }
    $data = [
      'is_continuous' => true,
      'contributions_print' => $contributions,
      'contributions' => $contributions->toArray()
    ];
    return $data;
  }
  //--**SUMA LAS CONTRIBUCIONES CON SIGNO + **//
  public function getContributionsPlus($reinstatement = false)
  {
    if ($this->selectedContributions() > 0 || $this->contributions()->count() == 0) {
      return [];
    }
    $ret_fun = $this->retirement_funds()->where('code','not like','%A%')->orderBy('reception_date');
    $retirement_fund = $reinstatement ? $ret_fun->get()->last() : $ret_fun->first();
    $used_contributions_limit = $retirement_fund->used_contributions_limit;

    if ($retirement_fund->procedure_modality->procedure_type->id == ProcedureType::RET_FUN_DA) {
      $number_contributions = $this->getTotalQuotes($reinstatement);
    } else {
      $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
    }
    if(!$reinstatement) {
      $start_date = Util::parseMonthYearDate($retirement_fund->affiliate->date_entry);
      $end_date = !!$retirement_fund->affiliate->date_last_contribution ? Util::parseMonthYearDate($retirement_fund->affiliate->date_last_contribution) : Util::parseMonthYearDate($retirement_fund->affiliate->date_derelict);
    } else {
      $start_date = Util::parseMonthYearDate($retirement_fund->affiliate->date_entry_reinstatement);
      $end_date = !!$retirement_fund->affiliate->date_last_contribution_reinstatement ? Util::parseMonthYearDate($retirement_fund->affiliate->date_last_contribution_reinstatement) : Util::parseMonthYearDate($retirement_fund->affiliate->date_derelict_reinstatement);
    }

    $sumColumns = ['base_wage', 'seniority_bonus', 'total', 'retirement_fund', 'mortuary_quota', 'public_security_bonus', 'gain'];
    $cont = $this->contributions()
      ->select(array_merge(['affiliate_id', 'month_year'], $sumColumns))
      ->whereHas('contribution_type', function ($query) {
        $query->where('operator', 'like', '+');
      })
      ->whereBetween('contributions.month_year', [$start_date, $end_date])
      ->where('affiliate_id', $this->id)
      ->orderBy('month_year', 'asc')
      ->when($retirement_fund->used_contributions_limit > 0, function ($q) use ($retirement_fund) {
          return $q->limit($retirement_fund->used_contributions_limit);
      })
      ->get();
      
    //$last_contributions = $cont->slice($used_contributions_limit - $number_contributions)->values();
    $totalContributions = $cont->count();
    $last_contributions = $cont->slice(max(0, $totalContributions - $number_contributions))->values();

    $contributions_with_reimbursements = Contribution::sumReimbursement($last_contributions, $sumColumns);

    return $contributions_with_reimbursements->reverse()->values();
  }
  //--**OBTIENE LAS CONTRIBUCIONES DE DISPONIBILIDAD**--//
  public function getContributionsAvailability($with_reimbursements = true)
  {
    if ($this->selectedContributions() > 0 ||  $this->contributions()->count() == 0) {
      return [];
    }
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
                                                FROM contributions where contributions.affiliate_id = " . $this->id . " and contribution_type_id in (12,13))
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
                WHERE affiliate_id = " . $this->id . " and contributions.deleted_at is null and contribution_types.id in (12,13)
        ) as contributions_reimburements
            GROUP BY month_year, affiliate_id
            ORDER BY month_year DESC");
      return array_reverse($contributions);
    } else {
      return "error.... working........";
    }
  }
  //--**OBTIENE EL TOTAL DEL SALARIO COTIZABLE PARA FONDO**--//
  public function getTotalAverageSalaryQuotable($reinstatement = false)
  {
    $ret_fun = $this->retirement_funds()->where('code','not like','%A%')->orderBy('reception_date');
    $retirement_fund = $reinstatement ? $ret_fun->get()->last() : $ret_fun->first();
    if($retirement_fund->procedure_modality->procedure_type->id == ProcedureType::RET_FUN_DA){
      $number_contributions = $this->getTotalQuotes($reinstatement);
    }else{
      $number_contributions = $retirement_fund->ret_fun_procedure->contributions_number;
    }

    $contributions = self::getContributionsPlus($reinstatement);
    $total_base_wage = $contributions->sum('base_wage');
    $total_seniority_bonus = $contributions->sum('seniority_bonus');
    $total_aporte = $contributions->sum('total');
    $total_retirement_fund = $contributions->sum('retirement_fund');

    $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
    $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
    
    $data = [
      'contributions' => $contributions,
      'total_base_wage' => round($total_base_wage,2),
      'total_seniority_bonus' => round($total_seniority_bonus,2),
      'total_aporte' => round($total_aporte,2),
      'total_retirement_fund' => round($total_retirement_fund,2),
      'sub_total_average_salary_quotable' => round($sub_total_average_salary_quotable,2),
      'total_average_salary_quotable' => round($total_average_salary_quotable,2),
    ];
    return $data;
  }
  public function hasAvailability()
  {
    return sizeOf($this->getContributionsWithType(12)) > 0;
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
        affiliates.first_nam    //     return $this->hasOne(AffiliateDevice::class, 'affiliate_id', 'id', 'affiliate_devices');
        e as fecha_nacimiento,
        affiliates.nua as codigo_nua_cua";
    }

    // public function device() {
    //     return $this->hasOne(AffiliateDevice::class, 'affiliate_id', 'id', 'affiliate_devices');
    // }
    public function affiliate_token(){
      return $this->hasOne(AffiliateToken::class, 'affiliate_id', 'id', 'affiliate_tokens');
    }

  //obtener si el afiliado ha dejado de sol consecutivamente mas de 2 tramites de eco_com
  public function stop_eco_com_consecutively() {
    $stop_consecutively = false;
    $beforelast_procedure = EcoComProcedure::orderByDesc('year')->orderByDesc('normal_start_date')->limit(2)->pluck('id')[1];
    $count_beforelast_procedure = $this->economic_complements()->where('eco_com_procedure_id', $beforelast_procedure)->count();
    if($count_beforelast_procedure < 1){
      $latest_procedures = EcoComProcedure::orderByDesc('year')->orderByDesc('normal_start_date')->limit(2)->whereNotIn('id', EcoComProcedure::current_procedures()->pluck('id'))->pluck('id');
      $latest_procedures = $this->economic_complements()->whereIn('eco_com_procedure_id', $latest_procedures)->count();
      if ($latest_procedures < 1) {
        $stop_consecutively = true;
      }
    }
   return $stop_consecutively;
  }
   // Estado fallecido del affiliado
   public function getDeadAttribute()
   {
      return ($this->date_death != null || $this->reason_death != null || $this->death_certificate_number != null || $this->affiliate_state->name == "Fallecido");
   }

    public function getExpeditionCardAttribute()
    {
        $data = ' ';
        if ($this->city_identity_card && $this->city_identity_card->name != 'NINGUNO'){
          $data .= ' ' . $this->city_identity_card->name;
        } 
        return $data;
    }
    public function hasAvailabilityTime()
    {
      return (sizeOf($this->getContributionsWithType(12)) > 0) || (sizeOf($this->getContributionsWithType(13)) > 0);
    }

  public function hasDocumentScan(){
    $file_name = $this->id.'.PDF';
    $base_path = env('FTP_DIRECTORY');
    $file = false;
    if(Storage::disk('ftp')->has($base_path.'/'.$file_name)){
        $file = true;
    }else{
      $file_name = $this->id.'.pdf';
      if(Storage::disk('ftp')->has($base_path.'/'.$file_name)){
        $file = true;
      }
    }
    return $file;
  }

    public function getIntervalQualificationQuotaAid($quota_aid_id)
    {
      $ret_fun = QuotaAidMortuary::find($quota_aid_id);
      $affiliate = $ret_fun->affiliate;
      $date_qualification = [];
      $date_min = $date_max = $min_limit = $max_limit = null;

      if($ret_fun->isQuota()){
        $date_min = $affiliate->date_entry;
        if ($ret_fun->procedure_modality_id == 9 || $ret_fun->procedure_modality_id == 8) {
          $date_max = Carbon::parse(Util::parseBarDate($affiliate->date_death))->format('m/Y');
        } else {
          $date_max = Carbon::parse(Util::parseBarDate($affiliate->spouse[0]->date_death))->format('m/Y');
        }
        $min_limit = Util::parseMonthYearDate($date_min);
        $max_limit = Util::parseMonthYearDate($date_max);
      }else{
          if($ret_fun->procedure_modality_id == 15){//fallecimiento viuda
              $date_min = Carbon::parse(Util::parseBarDate($affiliate->date_death))->format('m/Y');
              $date_max = Carbon::parse(Util::parseBarDate($affiliate->spouse[0]->date_death))->format('m/Y');
              $min_limit = Util::parseMonthYearDate($date_min);
              $max_limit = Util::parseMonthYearDate($date_max);
          }elseif($ret_fun->procedure_modality_id == 14){// fallecimiento conyugue
            $contributions = $affiliate->aid_contributions()->where('total','>',0)->orderBy('month_year','desc')->limit(12)->get();
            $date_min =  $affiliate->date_entry;
            $date_max =  Carbon::parse(Util::parseBarDate($affiliate->spouse[0]->date_death))->format('m/Y');
            $min_limit = Util::parseMonthYearDate($date_min);
            $max_limit = Util::parseMonthYearDate($date_max);
          }else{
              $date_min = $affiliate->date_last_contribution;// fallecimiento titular
              $date_max = Carbon::parse(Util::parseBarDate($affiliate->date_death))->format('m/Y');
              $min_limit = Util::parseMonthYearDate($date_min);
              $max_limit = Util::parseMonthYearDate($date_max);
          }
      }
      $data = [
         'start_date_min' => $date_min,
         'end_date_max' => $date_max,
         'start_min_limit' => $min_limit,
         'end_max_limit' => $max_limit,
      ];
      return $data;
    }
}
