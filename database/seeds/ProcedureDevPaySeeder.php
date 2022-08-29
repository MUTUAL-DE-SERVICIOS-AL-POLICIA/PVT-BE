<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;


class ProcedureDevPaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure_types = [
            ['module_id' => '3', 'name' => 'Devolución de Aportes','second_name' => 'Devolución de Aportes']
        ];
        foreach ($procedure_types as $procedure_type) {
            ProcedureType::firstOrCreate($procedure_type);
        }

        $procedure_modalities = [
            ['procedure_type_id' => '21', 'name' => 'Titular', 'shortened' => 'DA - TIT', 'is_valid' => true],
            ['procedure_type_id' => '21', 'name' => 'Fallecimiento', 'shortened' => 'DA - FALL', 'is_valid' => true],
        ];
        foreach ($procedure_modalities as $procedure_modality) {
            ProcedureModality::firstOrCreate($procedure_modality);
        }

        $procedure_requirements = [
            ['procedure_modality_id' => '62', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '3', 'number' => '3'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '346', 'number' => '3'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '8', 'number' => '4'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '323', 'number' => '4'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '361', 'number' => '4'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '98', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '99', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '100', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '101', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '186', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '187', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '188', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '189', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '214', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '215', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '341', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '224', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '225', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '343', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '342', 'number' => '0'],

            ['procedure_modality_id' => '63', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '347', 'number' => '3'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '346', 'number' => '3'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '9', 'number' => '4'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '11', 'number' => '5'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '25', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '80', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '16', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '82', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '8', 'number' => '7'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '323', 'number' => '7'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '361', 'number' => '7'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '90', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '62', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '341', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '166', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '167', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '186', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '187', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '188', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '215', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '343', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '342', 'number' => '0'],
        ];
        foreach ($procedure_requirements as $procedure_requirement) {
            ProcedureRequirement::firstOrCreate($procedure_requirement);
        }
    }
}
