<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Muserpol\Models\EconomicComplement\EcoComFixedPension;
use Muserpol\Models\EconomicComplement\EcoComRegulation;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class EcoComFixedPensionController extends Controller
{
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
        ]);
        
        // //Valida si existen tramites con estado pagado que esten utilizando el id de la tabla fija
        // $count = EconomicComplement::select('id')
        // ->leftJoin('eco_com_states', 'eco_com_states.id', '=', 'economic_complements.eco_com_state_id')
        // ->leftJoin('eco_com_state_types', 'eco_com_state_types.id', '=', 'eco_com_states.eco_com_state_type_id')
        // ->where('economic_complements.affiliate_id', $request->affiliate_id)
        // ->where('economic_complements.eco_com_fixed_pension_id', $request->id)
        // ->whereIn('eco_com_states.eco_com_state_type_id', [1, 6]) //solo hab limitado y pagado
        // ->where('economic_complements.eco_com_procedure_id', '>=', EcoComRegulation::where('is_enable', true)
        //     ->orderBy('id','desc')
        //     ->first()->replica_eco_com_procedure_id
        // )
        // ->count();     
    
        // if ($count > 0) {
        //     return response()->json([
        //         'status' => 'error',
        //         'msg' => 'Error',
        //         'errors' => ['No se puede modificar la RENTA FIJA por que se tiene trámites PAGADOS o HABILITADOS PARA PAGO']
        //     ], 422);
        // } 

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
        $fixed->save();
        return $fixed;
    }
}
