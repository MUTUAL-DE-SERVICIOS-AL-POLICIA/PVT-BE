<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;

class AditionalDocumentRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure_requirements = [
            ['procedure_modality_id' => '1', 'procedure_document_id' => '5', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '88', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '374', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '375', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '376', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '377', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '378', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '379', 'number' => '0'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '380', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '5', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '88', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '374', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '375', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '376', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '377', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '378', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '379', 'number' => '0'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '380', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '5', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '19', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '83', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '88', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '374', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '375', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '376', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '377', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '378', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '379', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '380', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '381', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '382', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '383', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '384', 'number' => '0'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '45', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '19', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '83', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '381', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '382', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '383', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '384', 'number' => '0'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '45', 'number' => '0'],
        ];

        foreach($procedure_requirements as $procedure_requirement) {
            ProcedureRequirement::firstOrCreate($procedure_requirement);
        }
    }
}
