<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Carbon\Carbon;
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

    }

    /**
     * Ejecuta el proceso de replicación final.
     */
    public function executeReplication(Request $request)
    {
        // Lógica para ejecutar la replicación
    }
}