<?php

use Illuminate\Database\Seeder;

class QuotaAidProcedureDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_requirements')->insert([


            ['procedure_modality_id' => '8', 'procedure_document_id' => '25', 'number' => '10'],
            ['procedure_modality_id' => '8', 'procedure_document_id' => '51', 'number' => '10'],
            ['procedure_modality_id' => '8', 'procedure_document_id' => '26', 'number' => '12'],
            ['procedure_modality_id' => '8', 'procedure_document_id' => '27', 'number' => '12'],            
            ['procedure_modality_id' => '8', 'procedure_document_id' => '51', 'number' => '10'],

            
            //Documentacion presentada
            ['procedure_modality_id' => '13', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '40', 'number' => '3'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '9', 'number' => '5'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '10', 'number' => '6'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '11', 'number' => '7'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '12', 'number' => '8'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '13', 'number' => '8'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '14', 'number' => '8'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '15', 'number' => '9'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '16', 'number' => '10'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '25', 'number' => '10'],
            ['procedure_modality_id' => '13', 'procedure_document_id' => '17', 'number' => '10'],
            //Documentos adicionales
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '52', 'number' => '11'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '53', 'number' => '12'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '54', 'number' => '13'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '55', 'number' => '14'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '56', 'number' => '15'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '57', 'number' => '16'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '58', 'number' => '17'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '59', 'number' => '18'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '60', 'number' => '19'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '61', 'number' => '20'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '62', 'number' => '21'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '63', 'number' => '22'],
            // ['procedure_modality_id' => '13', 'procedure_document_id' => '64', 'number' => '23'],
    
            //Documentacion presentada
            ['procedure_modality_id' => '14', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '40', 'number' => '3'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '28', 'number' => '5'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '29', 'number' => '6'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '30', 'number' => '7'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '12', 'number' => '8'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '13', 'number' => '8'],
            ['procedure_modality_id' => '14', 'procedure_document_id' => '14', 'number' => '8'],
            //Documentos adicionales
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '52', 'number' => '9'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '53', 'number' => '10'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '54', 'number' => '11'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '55', 'number' => '12'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '56', 'number' => '13'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '57', 'number' => '14'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '58', 'number' => '15'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '59', 'number' => '16'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '60', 'number' => '17'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '61', 'number' => '18'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '62', 'number' => '19'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '63', 'number' => '20'],
            // ['procedure_modality_id' => '14', 'procedure_document_id' => '64', 'number' => '21'],
    
            //Documentacion presentada
            ['procedure_modality_id' => '15', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '33', 'number' => '3'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '31', 'number' => '4'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '32', 'number' => '5'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '10', 'number' => '6'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '11', 'number' => '7'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '12', 'number' => '8'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '15', 'number' => '9'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '16', 'number' => '10'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '25', 'number' => '10'],
            ['procedure_modality_id' => '15', 'procedure_document_id' => '17', 'number' => '10']
            //Documentos adicionales
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '52', 'number' => '11'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '53', 'number' => '12'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '54', 'number' => '13'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '55', 'number' => '14'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '56', 'number' => '15'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '57', 'number' => '16'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '58', 'number' => '17'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '59', 'number' => '18'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '60', 'number' => '19'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '61', 'number' => '20'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '62', 'number' => '21'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '63', 'number' => '22'],
            // ['procedure_modality_id' => '15', 'procedure_document_id' => '64', 'number' => '23']
          ]);


        
            
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '52', 'number' => '13'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '53', 'number' => '14'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '54', 'number' => '15'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '55', 'number' => '16'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '56', 'number' => '17'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '57', 'number' => '18'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '58', 'number' => '19'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '59', 'number' => '20'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '60', 'number' => '21'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '61', 'number' => '22'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '62', 'number' => '23'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '63', 'number' => '24'],
            // ['procedure_modality_id' => '8', 'procedure_document_id' => '64', 'number' => '25'],

            
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '52', 'number' => '12'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '53', 'number' => '13'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '54', 'number' => '14'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '55', 'number' => '15'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '56', 'number' => '16'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '57', 'number' => '17'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '58', 'number' => '18'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '59', 'number' => '19'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '60', 'number' => '20'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '61', 'number' => '21'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '62', 'number' => '22'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '63', 'number' => '23'],
            // ['procedure_modality_id' => '9', 'procedure_document_id' => '64', 'number' => '24'],
      
    }
}
