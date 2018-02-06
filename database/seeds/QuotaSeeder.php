<?php

use Illuminate\Database\Seeder;

class QuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quota_aid_procedures')->insert([
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8', 'amount' => '70000', 'year' => '2018/02/02'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8', 'amount' => '70000', 'year' => '2018/02/02'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8', 'amount' => '60000', 'year' => '2018/02/02'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8', 'amount' => '50000', 'year' => '2018/02/02'], 
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8', 'amount' => '40000', 'year' => '2018/02/02'],
            
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9', 'amount' => '31635', 'year' => '2018/02/02'], 
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9', 'amount' => '31635', 'year' => '2018/02/02'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9', 'amount' => '31635', 'year' => '2018/02/02'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9', 'amount' => '31635', 'year' => '2018/02/02'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9', 'amount' => '31635', 'year' => '2018/02/02'],
            
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '10', 'amount' => '21210', 'year' => '2018/02/02'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '11', 'amount' => '11248', 'year' => '2018/02/02'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '12', 'amount' => '16872', 'year' => '2018/02/02'],

            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '10', 'amount' => '21210', 'year' => '2018/02/02'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '11', 'amount' => '11248', 'year' => '2018/02/02'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '12', 'amount' => '16872', 'year' => '2018/02/02'],

            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '10', 'amount' => '15466', 'year' => '2018/02/02'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '11', 'amount' => '8436', 'year' => '2018/02/02'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '12', 'amount' => '12303', 'year' => '2018/02/02'],

            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '10', 'amount' => '14060', 'year' => '2018/02/02'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '11', 'amount' => '7733', 'year' => '2018/02/02'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '12', 'amount' => '11248', 'year' => '2018/02/02'],

            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '10', 'amount' => '21210', 'year' => '2018/02/02'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '11', 'amount' => '11248', 'year' => '2018/02/02'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '12', 'amount' => '16872', 'year' => '2018/02/02'],
        ]);

    }
}
