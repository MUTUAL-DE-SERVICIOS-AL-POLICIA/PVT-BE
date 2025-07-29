<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\EconomicComplement\EcoComModality;

class EconomicComplementReplicationController extends Controller
{
    /**
     * Verifica si existe un procedimiento destino (2do semestre) listo para recibir una replicación.
     * Devuelve los procedimientos origen y destino si la replicación es viable.
     */
    public function canCreateReplication(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $destinationProcedure = EcoComProcedure::whereYear('year', $currentYear)
                                               ->where('semester', 'Segundo')
                                               ->first();
        // 2. Si no existe el procedimiento destino, no se puede iniciar la replicación.
        if (!$destinationProcedure) {
            return response()->json([
                'can_replicate' => false,
                'message' => 'Aún no se ha creado el procedimiento para el Segundo Semestre ' . $currentYear . '.'
            ], 404); // Not Found
        }

        // 3. Comprobar si este procedimiento destino ya tiene trámites replicados.
        $isAlreadyReplicated = EconomicComplement::where('eco_com_procedure_id', $destinationProcedure->id)
                                                 ->whereNotNull('replicated_from_eco_com_id')
                                                 ->exists();

        if ($isAlreadyReplicated) {
            return response()->json([
                'can_replicate' => false,
                'message' => 'El procedimiento del Segundo Semestre ' . $currentYear . ' ya ha sido replicado.'
            ], 409); // Conflict
        }

        // 4. Si el destino existe y no ha sido replicado, buscar el procedimiento origen (1er semestre).
        $sourceProcedure = EcoComProcedure::whereYear('year', $currentYear)
                                          ->where('semester', 'Primer')
                                          ->first();
        if (!$sourceProcedure) {
            return response()->json([
                'can_replicate' => false,
                'message' => 'No se encontró el procedimiento origen (Primer Semestre ' . $currentYear . ') para la replicación.'
            ], 404); // Not Found
        }
        logger('Procedimiento origen encontrado: ' . $sourceProcedure->fullName());
        logger('Procedimiento destino encontrado: ' . $destinationProcedure->fullName());
        // 5. ¡Todo listo! Devolvemos los datos para que la interfaz se construya.
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
        // 1. Validar la entrada
        $request->validate([
            'source_procedure_id' => 'required|exists:eco_com_procedures,id',
        ]);

        // 2. Obtener dinámicamente los IDs de las modalidades
        $vejez_modalities_ids = EcoComModality::where('procedure_modality_id', 29)->pluck('id');
        $viudedad_modalities_ids = EcoComModality::where('procedure_modality_id', 30)->pluck('id');
        $all_modalities = $vejez_modalities_ids->concat($viudedad_modalities_ids);

        // 3. Calcular el TOTAL de trámites por modalidad en el semestre origen (sin filtros)
        $total_origin_counts = EconomicComplement::where('eco_com_procedure_id', $request->source_procedure_id)
            ->whereIn('eco_com_modality_id', $all_modalities)
            ->select('eco_com_modality_id', DB::raw('count(*) as total_count'))
            ->groupBy('eco_com_modality_id')
            ->get()
            ->keyBy('eco_com_modality_id');

        // 4. Calcular el TOTAL de trámites A REPLICAR (con filtros de fallecidos)
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

        // 5. Combinar los resultados
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

        // 6. Devolver la respuesta final
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
        // Lógica para ejecutar la replicación
    }
}