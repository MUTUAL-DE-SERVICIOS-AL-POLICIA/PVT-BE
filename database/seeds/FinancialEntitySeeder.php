<?php

use Illuminate\Database\Seeder;
use Muserpol\FinancialEntity;

class FinancialEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $financial_entities = [
            ['name' => 'SIN CUENTA'],
            ['name' => 'BANCO UNION S.A.'],
            ['name' => 'BANCO SOLIDARIO S.A.'],
            ['name' => 'BANCO NACIONAL DE BOLIVIA S.A.'],
            ['name' => 'BANCO BISA S.A.'],
            ['name' => 'BANCO DE CREDITO DE BOLIVIA S.A.'],
            ['name' => 'BANCO ECONOMICO S.A.'],
            ['name' => 'BANCO FASSIL S.A.'],
            ['name' => 'BANCO FORTALEZA S.A.'],
            ['name' => 'BANCO GANADERO S.A.'],
            ['name' => 'BANCO MERCANTIL SANTA CRUZ S.A.'],
            ['name' => 'BANCO NACIONAL DE BOLIVIA S.A.'],
            ['name' => 'BANCO SOLIDARIO S.A.'],
            ['name' => 'BANCO UNION S.A.']
        ];
        foreach ($financial_entities as $financial_entity) {
            FinancialEntity::firstOrCreate($financial_entity);
        }
    }
}
