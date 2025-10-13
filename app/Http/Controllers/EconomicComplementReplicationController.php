<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\EconomicComplement\EcoComModality;
use Muserpol\Models\EconomicComplement\EcoComFixedPension;
use Muserpol\Helpers\ID;
use Muserpol\Observers\EconomicComplementObserver;

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
                'message' => 'El Segundo Semestre ' . $currentYear . ' ya ha sido replicado.'
            ]);
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

        $modalities_map = EcoComModality::whereIn('id', $all_modalities)->orderBy('id')->pluck('shortened', 'id');
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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        $request->validate([
            'source_procedure_id' => 'required|exists:eco_com_procedures,id',
            'destination_procedure_id' => 'required|exists:eco_com_procedures,id',
        ]);

        $vejez_modalities_ids = EcoComModality::where('procedure_modality_id', 29)->pluck('id');
        $viudedad_modalities_ids = EcoComModality::where('procedure_modality_id', 30)->pluck('id');
        
        $total_replicated_count = 0;

        $destination_procedure = EcoComProcedure::find($request->destination_procedure_id);
        $code_semester_char = strtoupper($destination_procedure->semester[0]);
        $code_year = Carbon::parse($destination_procedure->year)->year;

        $last_complement = EconomicComplement::where('eco_com_procedure_id', $request->destination_procedure_id)
                                            ->orderBy(DB::raw("split_part(code, '/', 1)::integer"), 'desc')
                                            ->first();
        
        $last_code_number = $last_complement ? (int)explode('/', $last_complement->code)[0] : 0;
        
        try {
            DB::beginTransaction();

            EconomicComplement::where('eco_com_procedure_id', $request->source_procedure_id)
                ->with('eco_com_beneficiary.address')
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
                ->chunk(200, function ($candidates) use ($request, &$total_replicated_count, &$last_code_number, $code_semester_char, $code_year) {
                    foreach ($candidates as $candidate) {

                        EconomicComplement::observe(EconomicComplementObserver::class);

                        $last_code_number++;
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
                        $replicated->code = $last_code_number . '/' . $code_semester_char . '/' . $code_year;
                        $replicated->uuid = \Ramsey\Uuid\Uuid::uuid1()->toString();
                        $replicated->eco_com_reception_type_id = 1; // ID para "Habitual"
                        $replicated->save();

                        EconomicComplement::FlushEventListeners();
                        $this->updateEcoComWithFixedPension($replicated->id);

                        $original_beneficiary = $candidate->eco_com_beneficiary;
                        if ($original_beneficiary) {
                            $new_beneficiary = $original_beneficiary->replicate();
                            $new_beneficiary->economic_complement_id = $replicated->id;
                            $new_beneficiary->save();

                            if ($original_beneficiary->address && $original_beneficiary->address->isNotEmpty()) {
                                $new_beneficiary->address()->attach($original_beneficiary->address->pluck('id'));
                            }
                        }

                        $total_replicated_count++;
                    }
                });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en replicación: " . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error durante la replicación.'], 500);
        }

        return response()->json(['message' => '¡Replicación completada! Se replicaron exitosamente ' . $total_replicated_count . ' trámites']);
    }

    public function getReplicationHistory()
    {
        $history = EconomicComplement::whereNotNull('replicated_from_eco_com_id')
            ->join('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
            ->select(
                'economic_complements.eco_com_procedure_id',
                DB::raw("eco_com_procedures.semester || ' Semestre ' || EXTRACT(YEAR FROM eco_com_procedures.year) as procedure_name"),
                DB::raw('COUNT(economic_complements.id) as replicated_count'),
                DB::raw('MIN(economic_complements.created_at) as replication_date')
            )
            ->groupBy('economic_complements.eco_com_procedure_id', 'procedure_name')
            ->orderBy('replication_date', 'desc')
            ->get();

        return response()->json($history);
    }

    private function updateEcoComWithFixedPension($economic_complement_id)
    {
        $economic_complement = EconomicComplement::where('id',$economic_complement_id)->first();
        if(!!$economic_complement){
            if(!($economic_complement->eco_com_reception_type_id == ID::ecoCom()->inclusion)){
                $fixed_pension = EcoComFixedPension::where('affiliate_id', $economic_complement->affiliate_id)
                ->orderBy('created_at','desc')
                ->first();
                if(!!$fixed_pension){
                    $economic_complement->eco_com_fixed_pension_id = $fixed_pension->id; 
                    $economic_complement->aps_total_fsa = $fixed_pension->aps_total_fsa;    //APS          
                    $economic_complement->aps_total_cc = $fixed_pension->aps_total_cc;      //APS
                    $economic_complement->aps_total_fs = $fixed_pension->aps_total_fs;      //APS
                    $economic_complement->aps_total_death = $fixed_pension->aps_total_death;//APS
                    $economic_complement->aps_disability = $fixed_pension->aps_disability;  //APS //SENASIR

                    $economic_complement->sub_total_rent = $fixed_pension->sub_total_rent;  //SENASIR
                    $economic_complement->reimbursement = $fixed_pension->reimbursement;    //SENASIR
                    $economic_complement->dignity_pension = $fixed_pension->dignity_pension;//SENASIR
                    $economic_complement->total_rent = $fixed_pension->total_rent;          //SENASIR total_rent=sub_total_rent-descuentos planilla

                    $economic_complement->rent_type = 'Automatico';
                    $economic_complement->save();
                }
            }
        }
    }
}