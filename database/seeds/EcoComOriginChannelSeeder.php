<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EcoComOriginChannelSeeder extends Seeder
{
    public function run()
    {
        $channels = [
            ['name' => 'Ventanilla a nivel nacional', 'shortened' => 'VENTANILLA', 'has_responsible_user' => true, 'created_at' => Carbon::now()],
            ['name' => 'Aplicación Móvil MUSERPOL-PVT', 'shortened' => 'APP MOVIL', 'has_responsible_user' => false, 'created_at' => Carbon::now()],
            ['name' => 'Punto Digital de Trámites', 'shortened' => 'PUNTO DIGITAL', 'has_responsible_user' => false, 'created_at' => Carbon::now()],
            ['name' => 'Replicación Semestral', 'shortened' => 'REPLICACION', 'has_responsible_user' => true, 'created_at' => Carbon::now()],
        ];

        DB::table('eco_com_origin_channel')->insert($channels);
        $this->command->info('¡El seeder de EcoComOriginChannel se ha ejecutado correctamente! con: '.json_encode($channels));
    }
}
