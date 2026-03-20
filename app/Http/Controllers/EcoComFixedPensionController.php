<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Muserpol\Models\EconomicComplement\EcoComFixedPension;
use Muserpol\Models\EconomicComplement\EcoComRegulation;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\Affiliate;
use Muserpol\Models\BaseWage;
use Muserpol\Models\EconomicComplement\EcoComRent;

class EcoComFixedPensionController extends Controller
{
    public function store(Request $request)
    {
        $regulation = EcoComRegulation::where('is_enable', true)->first();

        $exists = EcoComFixedPension::where('affiliate_id', $request->affiliate_id)
            ->where('eco_com_regulation_id', $regulation->id)
            ->where('eco_com_procedure_id', $request->eco_com_procedure_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['Ya existe un registro con ese periodo de renta.']
            ], 422);
        }    

    // Validar los datos de entrada
        $request->validate([
            'eco_com_procedure_id' => 'required|numeric',
            'affiliate_id' => 'required|numeric',
            'rent_type' => 'required|in:Manual,Replica',
            'aps_total_cc' => 'nullable|numeric',
            'aps_total_fsa' => 'nullable|numeric',
            'aps_total_fs' => 'nullable|numeric',
            'aps_disability' => 'nullable|numeric',
            'aps_total_death' => 'nullable|numeric',
            'sub_total_rent' => 'nullable|numeric',
            'reimbursement' => 'nullable|numeric',
            'dignity_pension' => 'nullable|numeric',
            'total_rent' => 'nullable|numeric',
            'eco_com_rent_id' => 'required|numeric',
            'base_wage_id' => 'required|numeric',
        ]);

        // Crear un nuevo registro
        $fixed = new EcoComFixedPension();
        $fixed->user_id = Auth::user()->id;
        $fixed->affiliate_id = $request->affiliate_id;
        $fixed->eco_com_procedure_id = $request->eco_com_procedure_id;
        $fixed->eco_com_regulation()->associate(EcoComRegulation::where('is_enable', true)->first());
        // Campos para gestora
        $fixed->aps_total_fsa = $request->aps_total_fsa;
        $fixed->aps_total_cc = $request->aps_total_cc;
        $fixed->aps_total_fs = $request->aps_total_fs;
        $fixed->aps_disability = $request->aps_disability;
        $fixed->aps_total_death = $request->aps_total_death;
        // Campos para senasir
        $fixed->sub_total_rent = $request->sub_total_rent;
        $fixed->total_rent = $request->total_rent;
        $fixed->reimbursement = $request->reimbursement;
        $fixed->dignity_pension = $request->dignity_pension;

        $fixed->rent_type = 'Manual';

        $fixed->eco_com_rent_id = $request->eco_com_rent_id;
        $fixed->base_wage_id = $request->base_wage_id;

        $fixed->save();
        return $fixed;
    }

    // Mostrar el formulario de edición
    public function edit($id)
    {
        $pension = EcoComFixedPension::findOrFail($id);
        return view('eco_com_fixed_pensions.edit', compact('pension'));
    }

    // Actualizar el registro
    public function updateFixed(Request $request, $id)
    {
        // Validar los datos de entrada
        $request->validate([
            'rent_type' => 'required|in:Manual,Replica',
            'aps_total_cc' => 'nullable|numeric',
            'aps_total_fsa' => 'nullable|numeric',
            'aps_total_fs' => 'nullable|numeric',
            'aps_disability' => 'nullable|numeric',
            'aps_total_death' => 'nullable|numeric',
            'sub_total_rent' => 'nullable|numeric',
            'reimbursement' => 'nullable|numeric',
            'dignity_pension' => 'nullable|numeric',
            'total_rent' => 'nullable|numeric',
            'eco_com_rent_id' => 'required|numeric',
            'base_wage_id' => 'required|numeric',
        ]);
        
        //Valida si existen tramites del afiliado con estado pagado que esten utilizando el id de la tabla fija
        $count = EconomicComplement::select('id')
        ->leftJoin('eco_com_states', 'eco_com_states.id', '=', 'economic_complements.eco_com_state_id')
        ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
        ->where('economic_complements.affiliate_id', $request->affiliate_id)
        ->where('economic_complements.eco_com_fixed_pension_id', $request->id)
        ->whereIn('eco_com_states.eco_com_state_type_id', [1, 6]) //solo hab limitado y pagado
        ->where('economic_complements.eco_com_procedure_id', '>=', EcoComRegulation::where('is_enable', true)
            ->orderBy('id','desc')
            ->first()->replica_eco_com_procedure_id
        )
        ->count();     
    
        if ($count > 0) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => ['No se puede modificar la RENTA FIJA por que se tiene trámites PAGADOS o HABILITADOS PARA PAGO']
            ], 422);
        } 

        // Buscar el registro por ID
        $fixed = EcoComFixedPension::findOrFail($id);

        // Actualizar los valores
        $fixed->user_id = Auth::user()->id;
        $fixed->eco_com_procedure_id = $request->eco_com_procedure_id;

        $fixed->aps_total_fsa = $request->aps_total_fsa;
        $fixed->aps_total_cc = $request->aps_total_cc;
        $fixed->aps_total_fs = $request->aps_total_fs;
        $fixed->aps_disability = $request->aps_disability;
        $fixed->aps_total_death = $request->aps_total_death;

        $fixed->sub_total_rent = $request->sub_total_rent;
        $fixed->total_rent = $request->total_rent;
        $fixed->reimbursement = $request->reimbursement;
        $fixed->dignity_pension = $request->dignity_pension;

        $fixed->rent_type = 'Manual';

        $fixed->eco_com_rent_id = $request->eco_com_rent_id;
        $fixed->base_wage_id = $request->base_wage_id;

        $fixed->save();
        return $fixed;
    }

    public function create($affiliate_id) {
        $affiliate = Affiliate::find($affiliate_id);
        $base_wages = BaseWage::where('degree_id', $affiliate->degree_id)->latest('month_year')->get();

        $last_eco_com = $affiliate->economic_complements()->orderByDesc('id')->get()->first();
        if ($last_eco_com) {
            $procedure_modality_id = $last_eco_com->eco_com_modality->procedure_modality_id;
        }
        //si mes orfandad debe utilizar parametro de vejez
        if($procedure_modality_id == 31){
            $procedure_modality_id = 29;            
        }

        $eco_com_rents = EcoComRent::where('degree_id', $affiliate->degree_id)
            ->where('procedure_modality_id', $procedure_modality_id)->latest('year')->latest('semester')->get();

        return response()->json([
                'base_wages' => $base_wages,
                'eco_com_rents' => $eco_com_rents,
                'type' => $last_eco_com->eco_com_modality->procedure_modality->name,
            ], 200);
    }
}