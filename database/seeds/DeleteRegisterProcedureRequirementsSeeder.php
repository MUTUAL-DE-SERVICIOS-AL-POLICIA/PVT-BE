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
        ProcedureRequirement::where('procedure_modality_id', 13) // Modalidad fallecimiento de (la) titular para Auxilio Mortuorio
            ->whereIn('procedure_document_id', [14, 78])         // Eliminando los documentos
            ->delete();
    }
}
