<?php

use Illuminate\Database\Seeder;

class AportesRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_requirements')->insert([
            //Pasivo Titular
            ['procedure_modality_id' => '21', 'procedure_document_id' => '40', 'number' => '1'],
            ['procedure_modality_id' => '21', 'procedure_document_id' => '192', 'number' => '2'],
            ['procedure_modality_id' => '21', 'procedure_document_id' => '193', 'number' => '2'],
            ['procedure_modality_id' => '21', 'procedure_document_id' => '194', 'number' => '3'],
            //Pasivo Cónyuge
            ['procedure_modality_id' => '22', 'procedure_document_id' => '195', 'number' => '1'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '12', 'number' => '2'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '115', 'number' => '3'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '40', 'number' => '4'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '33', 'number' => '5'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '9', 'number' => '6'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '192', 'number' => '7'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '193', 'number' => '7'],
            ['procedure_modality_id' => '22', 'procedure_document_id' => '194', 'number' => '8'],
            //Activo Agregado Policial
            ['procedure_modality_id' => '18', 'procedure_document_id' => '195', 'number' => '1'],
            ['procedure_modality_id' => '18', 'procedure_document_id' => '196', 'number' => '2'],
            ['procedure_modality_id' => '18', 'procedure_document_id' => '197', 'number' => '2'],
            ['procedure_modality_id' => '18', 'procedure_document_id' => '40', 'number' => '3'],
            ['procedure_modality_id' => '18', 'procedure_document_id' => '198', 'number' => '4'],
            //Activo Baja Temporal
            ['procedure_modality_id' => '19', 'procedure_document_id' => '195', 'number' => '1'],
            ['procedure_modality_id' => '19', 'procedure_document_id' => '199', 'number' => '2'],
            ['procedure_modality_id' => '19', 'procedure_document_id' => '200', 'number' => '2'],
            ['procedure_modality_id' => '19', 'procedure_document_id' => '40', 'number' => '3'],
            ['procedure_modality_id' => '19', 'procedure_document_id' => '198', 'number' => '4']
          ]);
    }
}
