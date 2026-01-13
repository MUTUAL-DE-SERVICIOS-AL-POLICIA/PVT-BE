<?php

namespace Muserpol\Models\EconomicComplement;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Muserpol\Models\BaseWage;
use Muserpol\Models\ComplementaryFactor;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Helpers\Util;
use Log;
use Illuminate\Support\Facades\Crypt;
use Hashids\Hashids;
use DB;
use Muserpol\Helpers\ID;
use Muserpol\Http\Controllers\ContributionPassiveController;
use Muserpol\Models\Contribution\ContributionPassive;
use Auth;

class EconomicComplement extends Model
{
    // protected $table = 'economic_complements_1';
    protected $guarded = [];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }

    public function origin_channel()
    {
        return $this->belongsTo(EcoComOriginChannel::class, 'eco_com_origin_channel_id');
    }
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function eco_com_state()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComState');
    }
    public function eco_com_procedure()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComProcedure');
    }
    public function procedure_state()
    {
        return $this->belongsTo('Muserpol\Models\ProcedureState');
    }
    public function city()
    {
        return $this->belongsTo('Muserpol\Models\City');
    }
    public function category()
    {
        return $this->belongsTo('Muserpol\Models\Category');
    }
    public function degree()
    {
        return $this->belongsTo('Muserpol\Models\Degree');
    }
    public function base_wage()
    {
        return $this->belongsTo('Muserpol\Models\BaseWage');
    }
    public function complementary_factor()
    {
        return $this->belongsTo('Muserpol\Models\ComplementaryFactor');
    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_current_state_id', 'id');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    public function discount_types()
    {
        return $this->belongsToMany('Muserpol\Models\DiscountType')
        ->withPivot(['id', 'amount', 'date', 'deleted_at'])
        ->wherePivot('deleted_at', null)
        ->withTimestamps();
    }
    public function eco_com_fixed_pension()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComFixedPension');
    }
    public function eco_com_updated_pension()
    {
        return $this->hasOne('Muserpol\Models\EconomicComplement\EcoComUpdatedPension');
    }
    public function eco_com_rent()
    {
        return $this->belongsTo(EcoComRent::class);
    }
    public function encode()
    {
        $hashids = new Hashids('economic_complements', 10);
        return $hashids->encode($this->id);
    }
    public function decode($hash)
    {
        $hashids = new Hashids('economic_complements', 10);
        $id = $hashids->decode($hash);
        if ($id) {
            return $id[0];
        }
        return null;
    }
    public function getBasicInfoCode()
    {
        return array('hash' => $this->encode());
    }
    public function submitted_documents()
    {
        return $this->hasMany(EcoComSubmittedDocument::class);
    }
    public function eco_com_legal_guardian()
    {
        return $this->hasOne(EcoComLegalGuardian::class);
    }
    /**
     *!! TODO
     */
    public function eco_com_modality()
    {
        return $this->belongsTo('Muserpol\Models\EconomicComplement\EcoComModality');
    }
    public function eco_com_beneficiary()
    {
        return $this->hasOne('Muserpol\Models\EconomicComplement\EcoComBeneficiary');
    }
    public function eco_com_once_payment()
    {
        return $this->hasOne('Muserpol\Models\EconomicComplement\EcoComOncePayment');
    }
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable')->orderBy('id', 'desc');
    }
    public function observations()
    {
        return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->whereNull('observables.deleted_at')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
    }
    // public function procedure_modality()
    // {
    //     return $this->belongsTo('Muserpol\Models\ProcedureModality');
    // }
    public function getComplementaryFactor()
    {
        return $this->complementary_factor;
    }
    public function getTotalSemester($months_of_payment)
    {
        $months_of_payment = (int) $months_of_payment;
        if($months_of_payment == 0) //null
            $months_of_payment =  6;
            
        return $this->difference * $months_of_payment;
    }
    public function getOnlyTotalEcoCom()
    {
        return $this->total + $this->discount_types()->sum('amount');
    }
    public function qualify()
    {
        if ($this->eco_com_state->eco_com_state_type_id == 1 || $this->eco_com_state->eco_com_state_type_id == 6 || $this->eco_com_state_id == 23) {
            $eco_com_state = $this->eco_com_state;
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['No se puede realizar la amortización porque el trámite ' . $this->code . ' se encuentra en estado de ' . $eco_com_state->name],
            ], 422);
        }
        $eco_com_procedure = $this->eco_com_procedure;
        $eco_com_rent = $this->eco_com_fixed_pension->eco_com_rent
            ?? EcoComRent::where('degree_id', '=', $this->degree_id)
            ->where('procedure_modality_id', '=', ($this->isOrphanhood() ? 29 : $this->eco_com_modality->procedure_modality_id))
            ->whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
            ->where('semester', '=', $eco_com_procedure->semester)
            ->first();
        $base_wage = $this->eco_com_fixed_pension->base_wage
            ?? BaseWage::where('degree_id', $this->degree_id)->whereYear('month_year', '=', Carbon::parse($eco_com_procedure->year)->year)->first();
        
        $complementary_factor = ComplementaryFactor::where('hierarchy_id', '=', $base_wage->degree->hierarchy->id)
            ->whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
            ->where('semester', '=', $eco_com_procedure->semester)
            ->first();
        if (!$eco_com_rent) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['Verifique que existan los promedio para la gestion ' . $eco_com_procedure->fullName()],
            ], 422);
        }
        if (!$base_wage) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['Verifique que si existen los sueldos para la gestion ' . $eco_com_procedure->fullName()],
            ], 422);
        }
        if (!$complementary_factor) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['Verifique los datos de los factores de complementación de la gestion ' . $eco_com_procedure->fullName()],
            ], 422);
        }
        $indicator = $eco_com_rent->referential_limit;
        
        /**
         ** updating modality with components
         */
        //APS
        if ($this->affiliate->pension_entity_id <> 5) { // si es APS
            $component = 0;
            if ($this->aps_total_fsa > 0) {
                $component++;
            }
            if ($this->aps_total_cc  > 0) {
                $component++;
            }
            if ($this->aps_total_fs > 0) {
                $component++;
            }
            //vejez
            if ($this->isOldAge()) {
                if ($component == 1) {
                    $this->eco_com_modality_id = $this->total_rent >= $indicator ? 4 : 6;
                } else {
                    $this->eco_com_modality_id = $this->total_rent < $indicator ? 8 : 1;
                }
            }
            //Viudedad
            if ($this->isWidowhood()) {
                if ($component == 1) {
                    $this->eco_com_modality_id = $this->total_rent >= $indicator ? 5 : 7;
                } else {
                    $this->eco_com_modality_id = $this->total_rent < $indicator ? 9 : 2;
                }
            }

            //orfandad
            if ($this->isOrphanhood()) {
                if ($component == 1) {
                    $this->eco_com_modality_id = $this->total_rent >= $indicator ? 10 : 11;
                } else {
                    $this->eco_com_modality_id = $this->total_rent < $indicator ? 12 : 3;
                }
            }

        } else {
            //Senasir
            if ($this->isOldAge()) {
                $this->eco_com_modality_id = $this->total_rent < $indicator ? 8 : 1;
            } elseif ($this->isWidowhood()) {
                $this->eco_com_modality_id = $this->total_rent < $indicator ? 9 : 2;
            } elseif ($this->isOrphanhood()) {
                $this->eco_com_modality_id = $this->total_rent < $indicator ? 12 : 3;
            }
            
        }
        $this->save();
        /**
         ** /updating modality with components
         */
        $this->total_rent_calc = $this->total_rent;

        /**
         ** averages
         ** actualizacion de las rentas netas
         */

        if (in_array($this->eco_com_modality_id, [4, 5, 6, 7, 8, 9, 10, 11, 12])) {
            // solo se esta tomando las modalidades de vejez y viudedad
            if (in_array($this->eco_com_modality_id,  [6, 7, 8, 9, 11, 12])) {
                $this->total_rent_calc = $eco_com_rent->average;
            } else if ($this->total_rent < $eco_com_rent->average && in_array($this->eco_com_modality_id,  [4, 5, 10])) {
                $this->total_rent_calc = $eco_com_rent->average;
            }
        }
        $this->save();
        /**
         ** /averages
         */

        //para el caso de las viudas 80%
        $salary_reference = $this->isWidowhood()
            ? $base_wage->amount * (80 / 100)
            : $base_wage->amount;
        $seniority = $this->category->percentage * $salary_reference;
        $this->seniority = $seniority;
        $salary_quotable = $salary_reference + $seniority;
        $this->salary_quotable = $salary_quotable;
        $difference = $salary_quotable - $this->total_rent_calc;
        $this->difference = $difference;
        $months_of_payment = 6;
        if ($this->is_paid==true)
        {
            if (!empty($this->months_of_payment))
                $months_of_payment = $this->months_of_payment;
        }

        $total_amount_semester = $difference * $months_of_payment;
        $this->total_amount_semester = $total_amount_semester;
        
        $this->complementary_factor_id = $complementary_factor->id;
        $complementary_factor = $this->isWidowhood() 
            ? $complementary_factor->widowhood
            : $complementary_factor->old_age;
        $this->complementary_factor = $complementary_factor;
        $total = $total_amount_semester * round(floatval($complementary_factor) / 100, 3);

        //RESTANDO PRESTAMOS, CONTABILIDAD Y REPOSICION AL TOTAL PORCONCEPTO DE DEUDA
        $total  = $total - $this->discount_types()->sum('amount');
        
        $this->total = $total;
        $this->salary_reference = $salary_reference;
        $this->base_wage()->associate($base_wage);
        $this->eco_com_rent()->associate($eco_com_rent);
        $this->save();
        if ($this->total_rent <= $this->salary_quotable && $this->eco_com_state_id == 12) {
            $this->eco_com_state_id = 16;
        }
        $change_state = false;
        $change_state_process = false;
        $user_id = Auth::user()->id;
        // if ($this->discount_types->count() > 0) {
        //     if (round($this->total_amount_semester * round(floatval($this->complementary_factor) / 100, 3),2) ==  $this->discount_types()->sum('amount')) {
        //         $this->eco_com_state_id = 32;
        //         $change_state = true;
        //     }else{
        //         if ($this->eco_com_state_id == 32) {
        //             $this->eco_com_state_id = 16;
        //             $change_state_process = true;
        //         }
        //     }
        // }
        $this->save();
        if($change_state){
            //cambio de estado del aporte de En Proceso a Pagado en la tabla contribution_passives
             $payment_contribucion_passive_paid = DB::select("SELECT change_state_contribution_paid_eco_com($user_id,$this->id)");
        }
        if($change_state_process){
            //cambio de estado pagado a en proceso en la tabla contribution_passives
            $payment_contribucion_passive_process = DB::select("SELECT change_state_contribution_process_eco_com($user_id,$this->id)");
        }
        return response()->json([
                'status' => 'success',
            ], 200);
    }
    public function isOldAge()
    {
        return $this->eco_com_modality->procedure_modality_id == 29;
    }
    public function isWidowhood()
    {
        return $this->eco_com_modality->procedure_modality_id == 30;
    }
    public function isOrphanhood()
    {
        return $this->eco_com_modality->procedure_modality_id == 31;
    }
    public function isInclusion()
    {
        return $this->eco_com_reception_type_id == ID::ecoCom()->inclusion;
    }
    public function hasLegalGuardian()
    {
        return $this->eco_com_legal_guardian;
    }
    public function hasDiscountTypes()
    {
        return $this->discount_types->count() > 0;
    }
    public function hasDiscountType($id)
    {
        return !!$this->discount_types()->where('discount_type_id', $id)->first();
    }
    public function eco_com_reception_type()
    {
        return $this->belongsTo(EcoComReceptionType::class);
    }
    public function hasObservationType($id)
    {
        return !!$this->observations()->where('observation_type_id', '=', $id)->first();
    }
    public function scopeHasEcoComState($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        return $query->leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
            ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
            ->whereIn('eco_com_state_types.id', $collect);
    }

    public function scopeDirectPayment($query, $id)
    {
        if ($id == 1) {
            $collect = collect([1,2,3,4,5,6,7,8,10,11,12,13]);
            $query->where('affiliates.sigep_status', 'LIKE', 'ACTIVO')
                ->whereIn('affiliates.financial_entity_id',$collect)
                ->where('affiliates.account_number', '>', 0);
        } elseif ($id == 2) {
            $query->where('affiliates.sigep_status', 'NOT LIKE', 'ACTIVO')
                ->orwhere('affiliates.account_number', null);
        } 
        return $query;
    }

    public function scopeIndirectPayment($query)
    {          
        $query->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id') 
            ->where('affiliates.sigep_status', 'NOT LIKE', 'ACTIVO');
        return $query;
    }

    public function scopeNotHasEcoComState($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        return $query->leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
            ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
            ->whereNotIn('eco_com_state_types.id', $collect);
    }
    public function scopeEcoComProcedure($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        return $query->whereIn('eco_com_procedure_id', $collect);
    }
    public function scopeWorkflow($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        return $query->whereIn('workflow_id', $collect);
    }
    public function scopeWfState($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        return $query->whereIn('wf_current_state_id', $collect);
    }
    public function scopeInboxState($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        return $query->whereIn('inbox_state', $collect);
    }
    public function scopeCity($query)
    {
        return $query->leftJoin('cities as eco_com_city', 'eco_com_city.id', '=', 'economic_complements.city_id');
    }
    /*public function scopeUser($query)
    {
        return $query->leftJoin('users as eco_com_user', 'eco_com_user.id', '=', 'economic_complements.user_id');
    }*/
    public function scopeBeneficiary($query)
    {
        return $query->leftJoin('eco_com_applicants as beneficiary', 'beneficiary.economic_complement_id', '=', 'economic_complements.id')
            ->leftJoin('cities as beneficiary_city', 'beneficiary_city.id', '=', 'beneficiary.city_identity_card_id');
    }
    public function scopeHasObservationTypeAndCorrect($query, ...$ids)
    {
        $collect = collect([]);
        foreach ($ids as $i) {
            $collect->push($i);
        }
        $model = 'economic_complements';
        $table = 'economic_complements';
        return $query->whereExists(function ($query) use ($model, $table, $collect) {
            $query->select(DB::raw(1))
                ->from('observables')
                ->where('observables.observable_id', '=', $table . '.id')
                ->where('observables.observable_type', '=', $model)
                ->where('observables.enabled', false)
                ->whereIn('observables.observation_type_id', $collect);
        });
        return $query->leftJoin('observables', function ($join) use ($model, $table, $collect) {
            $join->on('observables.observable_id', '=', $table . '.id')
                ->where('observables.observable_type', '=', $model)
                ->where('observables.enabled', false)
                ->whereIn('observables.observation_type_id', $collect);
        });
        return $query->leftJoin('observables', 'observables.observable_id', '=', $this->id)
            ->where('observables.observable_type', 'economic_complements')
            ->where('observables.enabled', true);
        return $this->observations()->whereIn('id', collect(func_get_args($ids)))->where('enabled', true)->get()->count();
        // return collect(func_get_args($ids))->includes($this->)
    }
    public function hasObservationTypeAndNotCorrect(...$ids)
    {
        return $this->observations()->whereIn('id', collect(func_get_args($ids)))->where('enabled', false)->get()->count();
        // return collect(func_get_args($ids))->includes($this->)
    }
    // public function hasObservationTypeAndCorrect(...$ids)
    // {
    //     return !! $this->observations()->whereIn('id', collect(func_get_args($ids)))->where('enabled', true)->get()->count();
    //     // return collect(func_get_args($ids))->includes($this->)
    // }
    public function scopeInfo($query)
    {
        $query->leftJoin('cities as eco_com_city', 'eco_com_city.id', '=', 'economic_complements.city_id')
            ->leftJoin('degrees as eco_com_degree', 'economic_complements.degree_id', '=', 'eco_com_degree.id')
            ->leftJoin('categories as eco_com_category', 'economic_complements.category_id', '=', 'eco_com_category.id')
            ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
            ->leftJoin('procedure_modalities', 'eco_com_modalities.procedure_modality_id', '=', 'procedure_modalities.id')
            ->leftJoin('eco_com_reception_types', 'economic_complements.eco_com_reception_type_id', '=', 'eco_com_reception_types.id')
            ->leftJoin('eco_com_origin_channel', 'economic_complements.eco_com_origin_channel_id', '=', 'eco_com_origin_channel.id')
            ->leftJoin('discount_type_economic_complement as ecocomdiscount','ecocomdiscount.economic_complement_id','=','economic_complements.id')
            ->leftJoin('discount_types as discount','discount.id','=','ecocomdiscount.discount_type_id');
        
        //juntamos los records que hay en procedure_records
        $query->leftJoin('procedure_records as pr', function ($join) {
            $join->on('pr.recordable_id', '=', 'economic_complements.id')
                ->where('pr.recordable_type', '=', 'economic_complements')
                ->where(function ($q) {
                    $q->where('pr.message', 'like', '%creó el trámite%')
                      ->orWhere('pr.message', 'like', 'Se creó el trámite mediante aplicación móvil.');
                });
        });
        //unimos los records que hay en wf_records
        $query->leftJoin('wf_records as wr', function ($join) {
            $join->on('wr.recordable_id', '=', 'economic_complements.id')
                ->where('wr.recordable_type', '=', 'economic_complements')
                ->where('wr.message', 'like', '%Trámite creado mediante%');
        });
        $query->leftJoin('users as creator', 'creator.id', '=', DB::raw('COALESCE(pr.user_id, wr.user_id)'));

        return $query;
        }
    public function scopeInfoBasic($query)
    {
        return $query->leftJoin('cities as eco_com_city', 'eco_com_city.id', '=', 'economic_complements.city_id')
            ->leftJoin('degrees as eco_com_degree', 'economic_complements.degree_id', '=', 'eco_com_degree.id')
            ->leftJoin('categories as eco_com_category', 'economic_complements.category_id', '=', 'eco_com_category.id')
            ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
            ->leftJoin('procedure_modalities', 'eco_com_modalities.procedure_modality_id', '=', 'procedure_modalities.id')
            ->leftJoin('eco_com_reception_types', 'economic_complements.eco_com_reception_type_id', '=', 'eco_com_reception_types.id');
    }
    public function scopeInfoDelete($query) {
        return $query->leftJoin('cities as eco_com_city', 'eco_com_city.id', '=', 'economic_complements.city_id')
            ->leftJoin('degrees as eco_com_degree', 'economic_complements.degree_id', '=', 'eco_com_degree.id')
            ->leftJoin('categories as eco_com_category', 'economic_complements.category_id', '=', 'eco_com_category.id')
            ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
            ->leftJoin('procedure_modalities', 'eco_com_modalities.procedure_modality_id', '=', 'procedure_modalities.id')
            ->leftJoin('eco_com_reception_types', 'economic_complements.eco_com_reception_type_id', '=', 'eco_com_reception_types.id')
            ->leftJoin('eco_com_origin_channel', 'economic_complements.eco_com_origin_channel_id', '=', 'eco_com_origin_channel.id')
            ->leftJoin('discount_type_economic_complement as ecocomdiscount','ecocomdiscount.economic_complement_id','=','economic_complements.id')
            ->leftJoin('discount_types as discount','discount.id','=','ecocomdiscount.discount_type_id')
            ->leftJoin('procedure_records as eco_com_user','eco_com_user.recordable_id','=','economic_complements.id')
            ->leftJoin('users as creator', 'creator.id', '=', 'eco_com_user.user_id')
            ->where( function($query) {
                $query->where('eco_com_user.message','like','%eliminó%')
                ->orWhereNull('eco_com_user.id');
        }
            );
    }
    public function scopeOrder($query)
    {
        return $query->orderBy(DB::raw("regexp_replace(split_part(economic_complements.code, '/',3),'\D','','g')::integer"))
            ->orderBy(DB::raw("split_part(economic_complements.code, '/',2)"))
            ->orderBy(DB::raw("split_part(economic_complements.code, '/',1)::integer"));
    }
    public function scopeObservationType($query)
    {
        return $query->leftJoin('observables', 'economic_complements.id', 'observables.observable_id')->where('observables.observable_type', 'like', 'economic_complements')->leftJoin('observation_types', 'observables.observation_type_id', '=', 'observation_types.id');
    }
    public function getType()
    {
        $type = 'Derechohabiente';
        if ($this->isOldAge()) {
            $type = 'Titular';
        }
        return $type;
    }
    public function getTypeModality()
    {
        if ($this->isOldAge()) {
            $type = 'Vejez';
        }
        if ($this->isWidowhood()) {
            $type = 'Viuda(o)';
        }
        if ($this->isOrphanhood()) {
            $type = 'Huérfano Absoluto';
        }
        return $type;
    }
    public function calculateTotalRentAps()
    {
        $this->total_rent = $this->aps_total_death +  $this->aps_disability + $this->aps_total_cc + $this->aps_total_fsa + $this->aps_total_fs;
        $this->save();
    }
    public function notes()
    {
        return $this->morphMany('Muserpol\Models\Note', 'annotable');
    }
    public function procedure_records()
    {
        return $this->morphMany('Muserpol\Models\ProcedureRecord', 'recordable');
    }
    public static function basic_info_colums()
    {
        return "row_number() OVER () AS NRO,
        economic_complements.affiliate_id as NUP,
        economic_complements.code as eco_com_code,
        economic_complements.reception_date as fecha_recepcion,
        CASE WHEN economic_complements.eco_com_origin_channel_id IN (2, 3) THEN '' ELSE creator.username END as usuario,
        eco_com_origin_channel.name as modalidad_de_recepcion," .
            EconomicComplement::basic_info_beneficiary() . "," .
            EconomicComplement::basic_info_affiliates() . ",
        eco_com_city.name as regional,
        procedure_modalities.name as tipo_de_prestacion,
        eco_com_reception_types.name as reception_type,
        eco_com_category.name as categoria,
        eco_com_degree.name as grado,
        pension_entities.name," .
            EconomicComplement::basic_info_complements() . ",
        wf_states.first_shortened as ubicacion,
        eco_com_modalities.name as tipo_beneficiario,
        workflows.name as flujo";
    }
    public static function basic_info_beneficiary()
    {
        return "
        beneficiary.identity_card as ci_ben,
        beneficiary.first_name as primer_nombre_ben,
        beneficiary.second_name as segundo_nombre_ben,
        beneficiary.last_name as apellido_paterno_ben,
        beneficiary.mothers_last_name as apellido_materno_ben,
        beneficiary.surname_husband as apellido_de_casado_ben,
        beneficiary.birth_date as fecha_nac_ben,beneficiary.phone_number as telefonos_ben,
        beneficiary.cell_phone_number as celulares_ben,
        beneficiary.gender as genero_ben
        ";
    }
    public static function basic_info_complements()
    {
        return "
        economic_complements.sub_total_rent as total_ganado_renta_pensión_SENASIR,
        economic_complements.reimbursement as reintegro_SENASIR,
        economic_complements.dignity_pension  as renta_dignidad_SENASIR,
        economic_complements.aps_total_fsa as fraccion_saldo_acumulada_APS,
        economic_complements.aps_total_cc as fraccion_compensacion_cotizaciones_APS,
        economic_complements.aps_total_fs as fraccion_solidaria_vejez_APS,
        economic_complements.aps_disability as pension_de_invalidez,
        economic_complements.aps_total_death as pension_por_muerte,
        economic_complements.total_rent as total_renta,
        economic_complements.total_rent_calc as total_renta_neto,
        economic_complements.seniority as antiguedad,
        economic_complements.salary_reference as salario_referencial,
        economic_complements.salary_quotable as salario_cotizable,
        economic_complements.difference as diferencia,
        economic_complements.total_amount_semester as total_semestre,
        economic_complements.complementary_factor as factor_complementario,
        round(economic_complements.total_amount_semester * round(economic_complements.complementary_factor/100, 3), 2) as total_complemento,
        economic_complements.total as total_liquido_pagable";
        // " . EconomicComplement::basic_info_discount() . ",
    }

    public static function basic_info_discount()
    {
        return " 
        sum(DISTINCT case when (discount.shortened='Amortización por Préstamo en Mora') then  ecocomdiscount.amount else 0 end)  as Amortización_Préstamos_en_Mora,
        sum(DISTINCT case when (discount.shortened='Descuento por Préstamo Estacional') then  ecocomdiscount.amount else 0 end)  as Descuento_Préstamo_Estacional,
        sum(DISTINCT case when (discount.shortened='Amortización Reposición de Fondos') then  ecocomdiscount.amount else 0 end)  as Amortización_Reposición_de_Fondos,
        sum(DISTINCT case when (discount.shortened='Aporte Auxilio Mortuorio') then  ecocomdiscount.amount else 0 end)  as Amortización_Auxilio_Mortuorio,
        sum(DISTINCT case when (discount.shortened='Amortización Cuentas por Cobrar') then  ecocomdiscount.amount else 0 end)  as Amortización_Cuentas_por_cobrar,
        sum(DISTINCT case when (discount.shortened='Retención según juzgado') then  ecocomdiscount.amount else 0 end)  as Amortización_Retención_segun_juzgado";
    } 

    
    public static function basic_info_affiliates()
    {
        return "
        affiliates.identity_card as ci_causa,
        affiliates.first_name as primer_nombre_causahabiente,
        affiliates.second_name as segundo_nombre_causahabiente,
        affiliates.last_name as ap_paterno_causahabiente,
        affiliates.mothers_last_name as ap_materno_causahabiente,
        affiliates.surname_husband as ape_casada_causahabiente,
        affiliates.birth_date as fecha_nacimiento,
        affiliates.service_years as Anios_de_servicio,
        affiliates.service_months as Meses_de_servicio,
        affiliates.nua as codigo_nua_cua,
        affiliates.sigep_status as Estado_sigep,
        financial_entities.name as Entidad_financiera,
        affiliates.account_number as Numero_de_cuenta";
    }
    public static function basic_info_legal_guardian()
    {
        return "eco_com_legal_guardians.first_name as primer_nombre_apoderado, 
        eco_com_legal_guardians.second_name as segundo_nombre_apoderado, 
        eco_com_legal_guardians.last_name as ap_paterno_apoderado, 
        eco_com_legal_guardians.mothers_last_name as ap_materno_apoderado, 
        eco_com_legal_guardians.surname_husband as ape_casada_apoderado, 
        eco_com_legal_guardians.identity_card as ci_apoderado, 
        city_legal_guardian_identity_card.first_shortened as ci_exp_apoderado, 
        eco_com_legal_guardian_types.name as eco_com_legal_guardian_type_name";
    }
    public static function basic_info_spouse()
    {
        return "
        spouses.first_name as primer_nombre_apoderado, 
        spouses.second_name as segundo_nombre_apoderado, 
        spouses.last_name as ap_paterno_apoderado, 
        spouses.mothers_last_name as ap_materno_apoderado, 
        spouses.surname_husband as ape_casada_apoderado, 
        spouses.identity_card as ci_apoderado,
        city_spouse.name as eco_com_legal_guardian_type_name";
    }
    public static function basic_info_user()
    {
        return "user_created.username as usuario_que_creo, user_current.username as usuario_actual";
    }

    public function scopeApplicantinfo($query)
    {
        return $query->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
            ->leftJoin('cities as city_applicant_identity_card', 'eco_com_applicants.city_identity_card_id', '=', 'city_applicant_identity_card.id');
    }
    public function scopeLegalguardianInfo($query)
    {
        return $query->leftJoin('eco_com_legal_guardians', 'economic_complements.id', '=', 'eco_com_legal_guardians.economic_complement_id')
            ->leftJoin('cities as city_legal_guardian_identity_card', 'eco_com_legal_guardians.city_identity_card_id', '=', 'city_legal_guardian_identity_card.id')
            ->leftJoin('eco_com_legal_guardian_types', 'eco_com_legal_guardians.eco_com_legal_guardian_type_id', '=', 'eco_com_legal_guardian_types.id');
            // ->groupBy('eco_com_legal_guardians.id','city_legal_guardian_identity_card.id','eco_com_legal_guardian_types.id');
    }
    public function scopeSpouseInfo($query)
    {
        return $query->leftJoin('spouses', 'spouses.affiliate_id', '=', 'affiliates.id')
            ->leftJoin('cities as city_spouse', 'spouses.city_identity_card_id', '=', 'city_spouse.id');
        //->leftJoin('eco_com_legal_guardian_types', 'eco_com_legal_guardians.eco_com_legal_guardian_type_id', '=', 'eco_com_legal_guardian_types.id');
        // ->groupBy('eco_com_legal_guardians.id','city_legal_guardian_identity_card.id','eco_com_legal_guardian_types.id');
    }
    public function scopeAffiliateinfo($query)
    {
        return $query->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
            ->leftJoin('cities as affiliate_city', 'affiliates.city_identity_card_id', '=', 'affiliate_city.id')
            ->leftJoin('pension_entities', 'affiliates.pension_entity_id', '=', 'pension_entities.id')
            ->leftJoin('financial_entities', 'affiliates.financial_entity_id', '=', 'financial_entities.id');
    }
    public function scopeEcocomstates($query)
    {
        return $query->leftJoin('eco_com_states', 'economic_complements.eco_com_state_id', '=', 'eco_com_states.id')
            ->leftJoin('eco_com_state_types', 'eco_com_states.eco_com_state_type_id', '=', 'eco_com_state_types.id');
    }
    public function scopeWfstates($query)
    {
        return $query->leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
        ->leftJoin('workflows', 'economic_complements.workflow_id', '=', 'workflows.id');
    }
    public function scopeAffiliateTokens($query)
    {
        return $query->leftJoin('affiliate_tokens', 'economic_complements.affiliate_id', '=', 'affiliate_tokens.affiliate_id')
               ->leftJoin('affiliate_devices', 'affiliate_tokens.id', '=', 'affiliate_devices.affiliate_token_id');
    }
    public function scopeWfRecords($query)
    {
        return $query->leftJoin('wf_records', function($join) {
            $join->on('wf_records.recordable_id', '=', 'economic_complements.id')
                ->where('wf_records.recordable_type', '=', 'economic_complements');
        })->leftJoin('users', 'wf_records.user_id', '=', 'users.id');
    }
    public function scopeUpdatedPension($query)
    {
        return $query->leftJoin('eco_com_updated_pensions', function($join) {
            $join->on('eco_com_updated_pensions.economic_complement_id', '=', 'economic_complements.id');
        });
    }

    public function getEcoComBeneficiaryBank()
    {
        $beneficiary = $this->eco_com_beneficiary;
        if ($this->eco_com_legal_guardian) {
            if ($this->eco_com_legal_guardian->eco_com_legal_guardian_type_id == 2 || $this->eco_com_legal_guardian->eco_com_legal_guardian_type_id == 3) {
                $beneficiary = $this->eco_com_legal_guardian;
            }
        }
        if ($this->is_paid) {
            $spouse = $this->affiliate->spouse->first();
            if ($spouse) {
                $beneficiary = $spouse;
            }
        }
        return $beneficiary;
    }
    public function isLagging()
    {
        return $this->workflow_id == ID::workflow()->eco_com_lagging;
    }
    public function eco_com_review_procedures()
    {
        return $this->hasMany('Muserpol\Models\EconomicComplement\EcoComReviewProcedure');
    }
    public function hasFixedPension($data){

        if($data->eco_com_reception_type_id != ID::ecoCom()->inclusion){
            if($data->affiliate->pension_entity_id !=5){
                return $data->aps_total_fsa !== null || $data->aps_total_cc !== null || $data->aps_total_fs !== null || $data->aps_total_death !== null || $data->aps_disability != null;
            }else{
                return $data->sub_total_rent !== null || $data->reimbursement !== null || $data->dignity_pension !== null || $data->total_rent !== null || $data->aps_disability != null;
            }

        }else{
            return true;
        } 
    }

    public function updateEcoComWithFixedPension($fixed_pension_id = null)
    {
        if (!($this->eco_com_reception_type_id == ID::ecoCom()->inclusion)) {
            $fixed_pension = $fixed_pension_id 
                ? EcoComFixedPension::find($fixed_pension_id) 
                : EcoComFixedPension::where('affiliate_id', $this->affiliate_id)
                    ->latest('eco_com_procedure_id')
                    ->first();
            if (!!$fixed_pension) {
                $this->eco_com_fixed_pension_id = $fixed_pension->id;
                $this->aps_total_fsa = $fixed_pension->aps_total_fsa;    //APS          
                $this->aps_total_cc = $fixed_pension->aps_total_cc;      //APS
                $this->aps_total_fs = $fixed_pension->aps_total_fs;      //APS
                $this->aps_total_death = $fixed_pension->aps_total_death; //APS
                $this->aps_disability = $fixed_pension->aps_disability;  //APS //SENASIR

                $this->sub_total_rent = $fixed_pension->sub_total_rent;  //SENASIR
                $this->reimbursement = $fixed_pension->reimbursement;    //SENASIR
                $this->dignity_pension = $fixed_pension->dignity_pension; //SENASIR
                $this->total_rent = $fixed_pension->total_rent;          //SENASIR total_rent=sub_total_rent-descuentos planilla
                $this->rent_type = 'Automatico';

                $this->base_wage_id = $fixed_pension->base_wage_id;
                $this->eco_com_rent_id = $fixed_pension->eco_com_rent_id;

                $this->save();
            }
        }
    }
}