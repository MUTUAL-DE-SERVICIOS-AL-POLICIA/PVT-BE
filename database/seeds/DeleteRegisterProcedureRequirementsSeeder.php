<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;

class DeleteRegisterProcedureRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cuota Mortuoria
        ProcedureRequirement::where('procedure_modality_id', 8)  // Modalidad Fallecimiento del (la) titular en cumplimiento de funciones
            ->whereIn('procedure_document_id', [224, 225])
            ->delete();
        ProcedureRequirement::where('procedure_modality_id', 9)  // Modalidad Fallecimiento del (la) titular por riesgo común
            ->whereIn('procedure_document_id', [224, 225])
            ->delete();
        // Auxilio Mortuorio
        ProcedureRequirement::where('procedure_modality_id', 13) // Modalidad Fallecimiento del (la) titular
            ->whereIn('procedure_document_id', [224, 225])
            ->delete();
        ProcedureRequirement::where('procedure_modality_id', 15) // Modalidad Fallecimiento del (la) viudo(a)
            ->whereIn('procedure_document_id', [224, 225, 14, 78])
            ->delete();
        // Fondo de retiro
        ProcedureRequirement::where('procedure_modality_id', 4)  // Modalidad Fallecimiento
            ->whereIn('procedure_document_id', [119, 120])
            ->delete();
        // Devolución de Aportes
        ProcedureRequirement::where('procedure_modality_id', 62) // Modalidad Titular
            ->whereIn('procedure_document_id', [361, 8, 323])
            ->delete();
        ProcedureRequirement::where('procedure_modality_id', 63) // Modalidad Fallecimiento
            ->whereIn('procedure_document_id', [361, 8, 323])
            ->delete();

    }
}
