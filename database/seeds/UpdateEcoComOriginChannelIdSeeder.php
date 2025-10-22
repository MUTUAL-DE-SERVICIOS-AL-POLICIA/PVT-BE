<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\ProcedureRecord;

class UpdateEcoComOriginChannelIdSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Iniciando la actualización de canales de origen...');
        $this->command->info('Estableciendo el canal de origen por defecto a todos los trámites...');
        DB::table('economic_complements')->update(['eco_com_origin_channel_id' => 1]);

        $this->command->info('Identificando trámites creados desde la App...');
        $app_created_ids = ProcedureRecord::where('message', 'like', '%Se creó el trámite mediante aplicación móvil%')
                                            ->where('recordable_type', 'like', '%economic_complements%')
                                            ->pluck('recordable_id');

        if ($app_created_ids->isNotEmpty()) {
            $this->command->info('Actualizando ' . $app_created_ids->count() . ' trámites a canal App.');
            DB::table('economic_complements')
                ->whereIn('id', $app_created_ids)
                ->update(['eco_com_origin_channel_id' => 2]);
        } else {
            $this->command->info('No se encontraron trámites creados desde la App.');
        }

        $this->command->info('¡Actualización de canales de origen completada!');
    }
}
