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
    }
}
