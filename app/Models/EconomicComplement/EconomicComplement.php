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

class EconomicComplement extends Model
{
    // protected $table = 'economic_complements_1';
    protected $guarded = [];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('Muserpol\User');
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
        return $this->belongsToMany('Muserpol\Models\DiscountType')->withPivot(['amount','date','message'])->withTimestamps();
    }
    public function getBasicInfoCode()
    {
        // $code = $this->id . " " . ($this->affiliate->id ?? null) . "\n" . "Trámite Nro: " . $this->code . "\nModalidad: " . $this->eco_com_modality->name . "\Beneficiario: " . ($this->eco_com_beneficiary->fullName() ?? null);
    
        return array('code' => $this->code, 'hash' => null);
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
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable');
    }
    public function observations()
    {
        return $this->morphToMany('Muserpol\Models\ObservationType', 'observable')->whereNull('observables.deleted_at')->withPivot(['user_id', 'date', 'message', 'enabled', 'deleted_at'])->withTimestamps();
    }
    // public function procedure_modality()
    // {
    //     return $this->belongsTo('Muserpol\Models\ProcedureModality');
    // }
    public function qualify()
    {
        // // requalify
        // if ($economic_complement->total > 0 && ( $economic_complement->eco_com_state_id == 1 || $economic_complement->eco_com_state_id == 2 || $economic_complement->eco_com_state_id == 3 || $economic_complement->eco_com_state_id == 17 || $economic_complement->eco_com_state_id == 18 || $economic_complement->eco_com_state_id == 15 ) ) {
        //     $economic_complement->recalification_date = Carbon::now();
        //     $temp_eco_com = (array)json_decode($economic_complement);
        //     $old_eco_com = [];
        //     foreach ($temp_eco_com as $key => $value) {
        //         if ($key != 'old_eco_com') {
        //             $old_eco_com[$key] = $value;
        //         }
        //     }
        //     if (!$economic_complement->old_eco_com) {
        //         $economic_complement->old_eco_com=json_encode($old_eco_com);
        //     }
        //     $economic_complement->save();
        // }
        // // /requalify
        $eco_com_procedure = $this->eco_com_procedure;
        $eco_com_rent = EcoComRent::whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
            ->where('semester', '=', $eco_com_procedure->semester)
            ->get();
        $base_wage = BaseWage::whereYear('month_year', '=', Carbon::parse($eco_com_procedure->year)->year)->get();
        $complementary_factor = ComplementaryFactor::whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
            ->where('semester', '=', $eco_com_procedure->semester)
            ->get();
        if (!$eco_com_rent) {
            return 'Verifique que existan los promedio para la gestion ' . $eco_com_procedure->fullName();
        }
        if (!$base_wage) {
            return 'Verifique que si existen los sueldos para la gestion ' . $eco_com_procedure->fullName();
        }
        if (!$complementary_factor) {
            return 'Verifique los datos de los factores de complementacion de la gestion ' . $eco_com_procedure->fullName();
        }
        $indicator = $eco_com_procedure->indicator;
        /**
         ** updating modality with components
            */
        //APS
        if ($this->affiliate->pension_entity->type == 'APS') {
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
                if ($component == 1 && $this->total_rent >= $indicator) {
                    $this->eco_com_modality_id = 4;
                } elseif ($component == 1 && $this->total_rent < $indicator) {
                    $this->eco_com_modality_id = 6;
                } elseif ($component > 1 && $this->total_rent < $indicator) {
                    $this->eco_com_modality_id = 8;
                } else {
                    $this->eco_com_modality_id = 1;
                }
            }
            //Viudedad
            if ($this->isWidowhood()) {
                if ($component == 1 && $this->total_rent >= $indicator) {
                    $this->eco_com_modality_id = 5;
                } elseif ($component == 1 && $this->total_rent < $indicator) {
                    $this->eco_com_modality_id = 7;
                } elseif ($component > 1 && $this->total_rent < $indicator) {
                    $this->eco_com_modality_id = 9;
                } else {
                    $this->eco_com_modality_id = 2;
                }
            }
            //orfandad
            if ($this->isOrphanhood()) {
                if ($component == 1 && $this->total_rent >= $indicator) {
                    $this->eco_com_modality_id = 10;
                } elseif ($component == 1 && $this->total_rent < $indicator) {
                    $this->eco_com_modality_id = 11;
                } elseif ($component > 1 && $this->total_rent < $indicator) {
                    $this->eco_com_modality_id = 12;
                } else {
                    $this->eco_com_modality_id = 3;
                }
            }
        } else {
            //Senasir
            if ($this->isOldAge() && $this->total_rent < $indicator) {
                //vejez
                $this->eco_com_modality_id = 8;
            } elseif ($this->isWidowhood() && $this->total_rent < $indicator) {
                //Viudedad
                $this->eco_com_modality_id = 9;
            } elseif ($this->isOrphanhood() && $this->total_rent < $indicator) {
                //Orfandad
                $this->eco_com_modality_id = 12;
            } else {
                $this->eco_com_modality_id = $this->eco_com_modality->eco_com_type->id;
            }
        }
        // $economic_complement->aps_disability = floatval(str_replace(',', '', $aps_disability));
        // $economic_complement->total_rent = $total_rent;
        $this->save();
        /**
         ** /updating modality with components
            */
        // $this->total_rent_calc = $this->total_rent;

        /**
         ** averages
            ** actualizacion de las rentas netas
            */
        if ($this->eco_com_modality_id > 3 && $this->eco_com_modality_id < 10) { // no se esta tomando en cuenta a orfandad
            $eco_com_rent = EcoComRent::where('degree_id', '=', $this->degree_id)
                ->where('eco_com_type_id', '=', $this->eco_com_modality->eco_com_type->id)
                ->whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
                ->where('semester', '=', $eco_com_procedure->semester)
                ->first();
            // EXCEPTION WHEN TOTAL_RENT > AVERAGE IN MODALITIES 4 AND 5
            if ($this->total_rent > $eco_com_rent->average and ($this->eco_com_modality_id == 4 || $this->eco_com_modality_id == 5 || $this->eco_com_modality_id == 10)) { // se verifica si el total rent es mayor al promedio y que sea de un solo componente
                $this->total_rent_calc = $this->total_rent;
            } else {
                $this->total_rent_calc = $eco_com_rent->average;
            }
        } else if ($this->eco_com_modality_id >= 10) { // solo orfandad
            $eco_com_rent = EcoComRent::where('degree_id', '=', $this->degree_id)
                //!! porque se toma en cuenta vejez?????
                ->where('eco_com_type_id', '=', 1)
                ->whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
                ->where('semester', '=', $eco_com_procedure->semester)
                ->first();
            if ($this->total_rent > $eco_com_rent->average and $this->eco_com_modality_id == 10) {
                $this->total_rent_calc = $this->total_rent;
            } else {
                $this->total_rent_calc = $eco_com_rent->average;
            }
        }
        /**
         ** /averages
            */

        $base_wage = BaseWage::where('degree_id', $this->degree_id)->whereYear('month_year', '=', Carbon::parse($eco_com_procedure->year)->year)->first();

        //para el caso de las viudas 80%
        if ($this->isWidowhood()) {
            $base_wage_amount = $base_wage->amount * (80 / 100);
            $salary_reference = $base_wage_amount;
            $seniority = $this->category->percentage * $base_wage_amount;
        } else {
            $salary_reference = $base_wage->amount;
            $seniority = $this->category->percentage * $base_wage->amount;
        }

        $this->seniority = $seniority;
        $salary_quotable = $salary_reference + $seniority;
        $this->salary_quotable = $salary_quotable;
        $difference = $salary_quotable - $this->total_rent;
        $this->difference = $difference;
        $months_of_payment = 6;
        $total_amount_semester = $difference * $months_of_payment;
        $this->total_amount_semester = $total_amount_semester;
        // $economic_complement->sub_total_rent = floatval(str_replace(',', '', $sub_total_rent));
        // $economic_complement->reimbursement = floatval(str_replace(',', '', $reimbursement));
        // $economic_complement->dignity_pension = floatval(str_replace(',', '', $dignity_pension));
        $complementary_factor = ComplementaryFactor::where('hierarchy_id', '=', $base_wage->degree->hierarchy->id)
            ->whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
            ->where('semester', '=', $eco_com_procedure->semester)
            ->first();
        $this->complementary_factor_id = $complementary_factor->id;
        if ($this->isWidowhood()) {
            //viudedad
            $complementary_factor = $complementary_factor->widowhood;
        } else {
            //vejez
            $complementary_factor = $complementary_factor->old_age;
        }
        $this->complementary_factor = $complementary_factor;
        $total = $total_amount_semester * round(floatval($complementary_factor) / 100, 2);

        //RESTANDO PRESTAMOS, CONTABILIDAD Y REPOSICION AL TOTAL PORCONCEPTO DE DEUDA
        $total  = $total - $this->discount_types()->sum('amount');
        // }
        // if ($this->amount_loan > 0) {
        // }
        // if ($this->amount_accounting > 0) {
        //     $total  = $total - $this->amount_accounting;
        // }
        // if ($this->amount_replacement > 0) {
        //     $total  = $total - $this->amount_replacement;
        // }
        // if ($this->amount_credit > 0) {
        //     $total  = $total - $this->amount_credit;
        // }
        $this->total = $total;
        $this->base_wage_id = $base_wage->id;
        $this->salary_reference = $salary_reference;
        // if ($this->old_eco_com) {
        //     $old_total = json_decode($this->old_eco_com)->total;
        //     $this->total_repay =  floatval($total) - (floatval($old_total) + (floatval(json_decode($this->old_eco_com)->amount_loan) + floatval(json_decode($this->old_eco_com)->amount_replacement) + floatval(json_decode($this->old_eco_com)->amount_accounting) + floatval(json_decode($this->old_eco_com)->amount_credit)));
        //     $this->user_id = Auth::user()->id;
        //     $this->state = 'Edited';
        //     if (WorkflowState::where('role_id', '=', Util::getRol()->id)->first()) {
        //         $this->wf_current_state_id = WorkflowState::where('role_id', '=', Util::getRol()->id)->first()->id;
        //     } else {
        //         return redirect('economic_complement/' . $this->id)
        //             ->withErrors('Ocurrió un error verifique que los datos estén correctos.')
        //             ->withInput();
        //     }
        // }
        $this->save();
        return $this;
    }
    public function isOldAge()
    {
        return $this->eco_com_modality->eco_com_type_id == 1;
    }
    public function isWidowhood()
    {
        return $this->eco_com_modality->eco_com_type_id == 2;
    }
    public function isOrphanhood()
    {
        return $this->eco_com_modality->eco_com_type_id == 3;
    }
}