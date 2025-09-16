<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EcoComOriginChannelSeeder extends Seeder
{
    public function run()
    {
        $channels = [
            ['name' => 'Ventanilla', 'created_at' => Carbon::now()],
            ['name' => 'Aplicación móvil', 'created_at' => Carbon::now()],
            ['name' => 'Punto Digital de Trámites', 'created_at' => Carbon::now()],
            ['name' => 'Replicación Semestral', 'created_at' => Carbon::now()],
        ];

        DB::table('eco_com_origin_channel')->insert($channels);
    }
}
