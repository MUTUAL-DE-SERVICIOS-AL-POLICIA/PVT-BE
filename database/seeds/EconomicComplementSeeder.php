<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureType;

class EconomicComplementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProcedureType::create([
            'module_id' => 2,
            'name' => 'Pago del Complemento Económico',
            'second_name' => 'Complemento Económico',
        ]);
        $statuses = [
            ['procedure_type_id' => 8, 'name' => 'Vejez', 'shortened' => 'VJZ', 'is_valid' => true],
            ['procedure_type_id' => 8, 'name' => 'Viudedad', 'shortened' => 'VDA', 'is_valid' => true],
            ['procedure_type_id' => 8, 'name' => 'Orfandad', 'shortened' => 'ORF', 'is_valid' => true],
        ];
        foreach ($statuses as $status) {
            ProcedureModality::create($status);
        }
    }
}
