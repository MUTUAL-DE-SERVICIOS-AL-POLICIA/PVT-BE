<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EcoComModality;

class EconomicComplementReplicationController extends Controller
{
    public function canCreateReplication(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $destinationProcedure = EcoComProcedure::whereYear('year', $currentYear)
                                                ->where('semester', 'Segundo')
                                                ->first();
        if (!$destinationProcedure) {
            return response()->json([
                'can_replicate' => false,
                'message' => 'Aún no se ha creado el procedimiento para el Segundo Semestre ' . $currentYear . '.'
            ], 404); // Not Found
        }
        $isAlreadyReplicated = EconomicComplement::where('eco_com_procedure_id', $destinationProcedure->id)
                                                ->whereNotNull('replicated_from_eco_com_id')
                                                ->exists();

        if ($isAlreadyReplicated) {
            return response()->json([
                'can_replicate' => false,
                'message' => 'El procedimiento del Segundo Semestre ' . $currentYear . ' ya ha sido replicado.'
            ], 409);
        }
        $sourceProcedure = EcoComProcedure::whereYear('year', $currentYear)
                                          ->where('semester', 'Primer')
                                          ->first();
        if (!$sourceProcedure) {
            return response()->json([
                'can_replicate' => false,
                'message' => 'No se encontró el procedimiento origen (Primer Semestre ' . $currentYear . ') para la replicación.'
            ], 404);
        }
        logger('Procedimiento origen encontrado: ' . $sourceProcedure->fullName());
        logger('Procedimiento destino encontrado: ' . $destinationProcedure->fullName());
        return response()->json([
            'can_replicate' => true,
            'source_procedure' => $sourceProcedure,
            'destination_procedure' => $destinationProcedure
        ]);
    }

    /**
     * Prepara el lote de replicación para la revisión del usuario.
     */
    /**
     * Cuenta los trámites elegibles para replicación, agrupados por modalidad.
     */
    public function prepareReplication(Request $request)
    {

        $request->validate([
            'source_procedure_id' => 'required|exists:eco_com_procedures,id',
        ]);

        $vejez_modalities_ids = EcoComModality::where('procedure_modality_id', 29)->pluck('id');
        $viudedad_modalities_ids = EcoComModality::where('procedure_modality_id', 30)->pluck('id');
        $all_modalities = $vejez_modalities_ids->concat($viudedad_modalities_ids);
        $total_origin_counts = EconomicComplement::where('eco_com_procedure_id', $request->source_procedure_id)
            ->whereIn('eco_com_modality_id', $all_modalities)
            ->select('eco_com_modality_id', DB::raw('count(*) as total_count'))
            ->groupBy('eco_com_modality_id')
            ->get()
            ->keyBy('eco_com_modality_id');
        $to_replicate_counts = EconomicComplement::where('eco_com_procedure_id', $request->source_procedure_id)
            ->whereIn('eco_com_modality_id', $all_modalities)
            ->where(function ($query) use ($vejez_modalities_ids, $viudedad_modalities_ids) {
                $query->orWhere(function ($subQuery) use ($vejez_modalities_ids) {
                    $subQuery->whereIn('eco_com_modality_id', $vejez_modalities_ids)
                            ->whereHas('affiliate', function($q) { $q->whereNull('date_death'); });
                });
                $query->orWhere(function ($subQuery) use ($viudedad_modalities_ids) {
                    $subQuery->whereIn('eco_com_modality_id', $viudedad_modalities_ids)
                            ->whereHas('affiliate.spouse', function($q) { $q->whereNull('date_death'); });
                });
            })
            ->select('eco_com_modality_id', DB::raw('count(*) as total_count'))
            ->groupBy('eco_com_modality_id')
            ->get()
            ->keyBy('eco_com_modality_id');

        $modalities_map = EcoComModality::whereIn('id', $all_modalities)->orderBy('id')->pluck('name', 'id');
        $breakdown = [];
        $total_origin_all = 0;
        $total_to_replicate_all = 0;

        foreach ($modalities_map as $id => $name) {
            $origin_count = $total_origin_counts->get($id)->total_count ?? 0;
            $replicate_count = $to_replicate_counts->get($id)->total_count ?? 0;
            
            $breakdown[] = [
                'modality_name' => $name,
                'origin_count' => $origin_count,
                'replicate_count' => $replicate_count,
            ];
            $total_origin_all += $origin_count;
            $total_to_replicate_all += $replicate_count;
        }
        return response()->json([
            'message' => "Cálculo de trámites elegibles completado.",
            'total_origin' => $total_origin_all,
            'total_to_replicate' => $total_to_replicate_all,
            'breakdown' => $breakdown
        ]);
    }

    /**
     * Ejecuta el proceso de replicación final.
     */
    public function executeReplication(Request $request)
    {
        $request->validate([
            'source_procedure_id' => 'required|exists:eco_com_procedures,id',
            'destination_procedure_id' => 'required|exists:eco_com_procedures,id',
        ]);
        $vejez_modalities_ids = EcoComModality::where('procedure_modality_id', 29)->pluck('id');
        $viudedad_modalities_ids = EcoComModality::where('procedure_modality_id', 30)->pluck('id');
        $candidates = EconomicComplement::where('eco_com_procedure_id', $request->source_procedure_id)
            ->where(function ($query) use ($vejez_modalities_ids, $viudedad_modalities_ids) {
                $query->orWhere(function ($subQuery) use ($vejez_modalities_ids) {
                    $subQuery->whereIn('eco_com_modality_id', $vejez_modalities_ids)
                            ->whereHas('affiliate', function($q) { $q->whereNull('date_death'); });
                });
                $query->orWhere(function ($subQuery) use ($viudedad_modalities_ids) {
                    $subQuery->whereIn('eco_com_modality_id', $viudedad_modalities_ids)
                            ->whereHas('affiliate.spouse', function($q) { $q->whereNull('date_death'); });
                });
            })
            ->take(6)->get();

        if (!$candidates) {
            return response()->json(['message' => 'No se encontraron trámites elegibles para la prueba.'], 404);
        }

        try {
            $count = DB::transaction(function () use ($request, $candidates) {
                $count = 0;
                foreach ($candidates as $candidate) {
                logger('Replicando trámite para el afiliado: ' . $candidate->affiliate_id);
                $replicated = new EconomicComplement;
                $replicated->affiliate_id = $candidate->affiliate_id;
                $replicated->eco_com_modality_id = $candidate->eco_com_modality_id;
                $replicated->degree_id = $candidate->degree_id;
                $replicated->category_id = $candidate->category_id;
                $replicated->city_id = $candidate->city_id;

                $replicated->user_id = auth()->id();
                $replicated->eco_com_procedure_id = $request->destination_procedure_id;
                $replicated->replicated_from_eco_com_id = $candidate->id;
                $replicated->eco_com_origin_channel_id = 4; // Canal "Replicación"
                $replicated->eco_com_state_id = 16; // "En proceso de revisión"
                $replicated->workflow_id = $candidate->workflow_id;
                $replicated->wf_current_state_id = 1; // "recepción"
                $replicated->reception_date = Carbon::now();
                $replicated->code = Util::getLastCodeEconomicComplement($request->destination_procedure_id);
                $replicated->uuid = \Ramsey\Uuid\Uuid::uuid1()->toString();
                $replicated->eco_com_reception_type_id = 1; // ID para "Habitual"

                $replicated->eco_com_fixed_pension_id = $candidate->eco_com_fixed_pension_id;
                $replicated->aps_total_fsa = $candidate->aps_total_fsa;    //APS
                $replicated->aps_total_cc = $candidate->aps_total_cc;      //APS
                $replicated->aps_total_fs = $candidate->aps_total_fs;      //APS
                $replicated->aps_total_death = $candidate->aps_total_death;//APS
                $replicated->aps_disability = $candidate->aps_disability;

                $replicated->sub_total_rent = $candidate->sub_total_rent;  //SENASIR
                $replicated->reimbursement = $candidate->reimbursement;    //SENASIR
                $replicated->dignity_pension = $candidate->dignity_pension;//SENASIR
                $replicated->total_rent = $candidate->total_rent;          //SENASIR total_rent=sub_total_rent-descuentos planilla

                $replicated->rent_type = $candidate->rent_type;
                $replicated->save();
                $count++;
            }
            return $count;
            });
        } catch (\Exception $e) {
            Log::error("Error en replicación de prueba: " . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error durante la replicación de prueba.'], 500);
        }

        return response()->json(['message' => '¡Replicación de prueba completada! Se replicó exitosamente', 'count' => $count . ' trámites']);
    }
}