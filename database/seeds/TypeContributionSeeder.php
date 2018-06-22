<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Contribution\ContributionType;

class TypeContributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // DB::table('contribution_types')->insert([
        //     ['name' => 'Servicio', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Disponibilidad', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Item 0', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Batallon de Seguridad Fisica', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'No Hay Regristro', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Registro Segun CAS', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        // ]);
        foreach (ContributionType::all() as $value) {
            $value->delete();
        }
        $statuses = [
            ['name' => 'Período reconocido por comando ', 'shortened' => '','description' => 'Años que son certificados en el CAS (Certificado de años de servicio) del  Comando General'],

            ['name' => 'Período en item 0 Con Aporte', 'shortened' => 'Los efectivos Policiales que se encuentren en comisión de servicio Ítem "0" agregados policiales en el exterior del país, o que hubieran sido suspendidos de sus funciones por procesos disciplinarios y/o penales, figurando en planilla de 
            haberes con Ítem "0" y otros, que aportan de manera voluntaria a la MUSERPOL, para poder acceder al beneficio de Fondo de Retiro Policial Solidario; a excepción de los que no figuran en listas de revista y planillas de haberes del Comando General de la Policía Boliviana'],

            ['name' => 'Período en item 0 Sin Aporte', 'shortened' => 'Los efectivos Policiales que se encuentren en comisión de servicio Ítem "0" agregados policiales en el exterior del país, o que hubieran sido suspendidos de sus funciones por procesos disciplinarios y/o penales, figurando en planilla de 
            haberes con Ítem "0" y otros, que no aportan de manera voluntaria a la MUSERPOL, para poder acceder al beneficio de Fondo de Retiro Policial Solidario; a excepción de los que no figuran en listas de revista y planillas de haberes del Comando General de la Policía Boliviana'],

            ['name' => 'Período de Batallón de Seguridad Física Con Aporte', 'shortened' => 'Los efectivos Policiales que se encuentren en Batallón de Seguridad Fisica, y que hayan realizado los aportes  para Muserpol, acreditado con Certificado de Haberes de Batallon de Seguridad Fisica.'],

            ['name' => 'Período de Batallón de Seguridad Física Sin Aporte', 'shortened' => 'Los efectivos Policiales que se encuentren en Batallón de Seguridad Fisica, y que no hayan realizado los aportes para Muserpol, acreditado con Certificado de Haberes de Batallon de Seguridad Fisica.'],

            ['name' => 'Periodos anteriores a Mayo de 1976 Sin Aporte', 'shortened' => 'La MUSERPOL reconoce la densidad de aportes efectuados a partir de mayo de 1976, al Ex Fondo Complementario de Seguridad Social de la Policía Nacional y a la extinta Mutual de Seguros del Policía "MUSEPOL"'],

            ['name' => 'Período Certificación Con Aporte', 'shortened' => 'Periodo que se certifica con los tomos de Planillas de Haberes del Área de Archivo que registran aportes'],

            ['name' => 'Período Certificación Sin Aporte', 'shortened' => 'Periodo que se certifica con los tomos de Planillas de Haberes del Área de Archivo que no registran aportes'],

            ['name' => 'Período no Trabajado', 'shortened' => 'Periodos que no figuran en el CAS del Comando'],

            ['name' => 'Disponibilidad', 'shortened' => 'Periodos que el funcionario del sector activo de la Policia Boliviana estuvo en situación de disponibilidad de la letra "A" o la letra "C"']
        ];
        foreach ($statuses as $status) {
            ContributionType::create($status);
        }        
    }
}
