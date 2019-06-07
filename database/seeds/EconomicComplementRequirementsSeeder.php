<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;

class EconomicComplementRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['procedure_modality_id' => 29, 'procedure_document_id' => 226, 'number' => 1],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 2, 'number' => 2],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 3, 'number' => 3],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 229, 'number' => 4],
            
            ['procedure_modality_id' => 29, 'procedure_document_id' => 231, 'number' => 5],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 234, 'number' => 6],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 193, 'number' => 6],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 242, 'number' => 6],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 245, 'number' => 6],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 248, 'number' => 6],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 237, 'number' => 7],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 269, 'number' => 8],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 249, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 250, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 267, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 257, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 256, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 259, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 258, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 260, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 224, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 225, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 261, 'number' => 0],
            ['procedure_modality_id' => 29, 'procedure_document_id' => 240, 'number' => 0],

            ['procedure_modality_id' => 30, 'procedure_document_id' => 226, 'number' => 1],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 2, 'number' => 2],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 40, 'number' => 3],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 124, 'number' => 3],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 33, 'number' => 4],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 9, 'number' => 5],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 229, 'number' => 6],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 240, 'number' => 6],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 231, 'number' => 7],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 270, 'number' => 8],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 193, 'number' => 8],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 242, 'number' => 8],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 245, 'number' => 8],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 248, 'number' => 8],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 115, 'number' => 9],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 262, 'number' => 9],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 13, 'number' => 9],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 237, 'number' => 10],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 269, 'number' => 11],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 249, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 250, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 267, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 257, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 256, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 259, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 258, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 260, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 167, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 166, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 261, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 265, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 12, 'number' => 0],
            ['procedure_modality_id' => 30, 'procedure_document_id' => 44, 'number' => 0],

            ['procedure_modality_id' => 31, 'procedure_document_id' => 226, 'number' => 1],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 2, 'number' => 2],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 227, 'number' => 3],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 268, 'number' => 3],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 228, 'number' => 4],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 130, 'number' => 5],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 229, 'number' => 6],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 240, 'number' => 6],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 231, 'number' => 7],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 234, 'number' => 8],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 193, 'number' => 8],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 242, 'number' => 8],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 245, 'number' => 8],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 248, 'number' => 8],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 238, 'number' => 9],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 263, 'number' => 10],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 239, 'number' => 11],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 264, 'number' => 11],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 237, 'number' => 12],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 269, 'number' => 13],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 249, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 250, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 267, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 257, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 256, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 259, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 258, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 260, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 167, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 166, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 261, 'number' => 0],
            ['procedure_modality_id' => 31, 'procedure_document_id' => 265, 'number' => 0],

        ];
        foreach ($statuses as $status) {
            ProcedureRequirement::create($status);
        }
    }
}
