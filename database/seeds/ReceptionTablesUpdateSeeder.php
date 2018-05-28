<?php

use Illuminate\Database\Seeder;

class ReceptionTablesUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ret_fun_states')->insert([
            ['name' =>  'En proceso'],
            ['name' =>  'Pendiente'],
            ['name' =>  'Eliminado']
        ]);

         //Suppletory documents  
         DB::table('procedure_documents')->insert([
            ['name' => 'Certificado de ingreso a disponibilidad a las letras “C” y “A”.'],//42
            ['name' => 'Certificado de no ingreso a disponibilidad a las letras “C” y “A”.'],//43
            ['name' => 'Certificado de estado civil.'],//44
            ['name' => 'Certificación de Baja'],//45
            ['name' => 'Resolución y/o memorándum de baja definitiva emitida por el Comando General de la Policía Boliviana (original).']//46
        ]);

        DB::table('procedure_requirements')->insert([
            ['procedure_modality_id' => '4', 'procedure_document_id' => '42', 'number' => '6'],        	
            ['procedure_modality_id' => '4', 'procedure_document_id' => '43', 'number' => '6'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '44', 'number' => '9'],   	
            ['procedure_modality_id' => '5', 'procedure_document_id' => '45', 'number' => '5'],      	
            ['procedure_modality_id' => '7', 'procedure_document_id' => '45', 'number' => '5'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '46', 'number' => '5'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '46', 'number' => '5'],        	
            ['procedure_modality_id' => '7', 'procedure_document_id' => '46', 'number' => '5'],        	
            ['procedure_modality_id' => '1', 'procedure_document_id' => '44', 'number' => '8'],
            ['procedure_modality_id' => '1', 'procedure_document_id' => '41', 'number' => '12'],
            ['procedure_modality_id' => '2', 'procedure_document_id' => '20', 'number' => '6'],
            ['procedure_modality_id' => '2', 'procedure_document_id' => '46', 'number' => '6'],
        ]);

        //Updating 
        // $requirements_to_update = App\ProcedureRequirements::where('procedure_modality_id','1')->get();
        // $i = 0;
        // $update_table = [
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        //     ['procedure_document_id' => '', 'number' => ''],
        // ];
        // foreach($requirements_to_update as $requirement){
        //     $requirement
        // }

    }
}
