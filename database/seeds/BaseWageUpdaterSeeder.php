<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\BaseWage;
use Illuminate\Support\Facades\DB;

class BaseWageUpdaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Paso 1: Actualizando economic_complements.base_wage_id de registros despues de 2021 a registros 2020...');
        $update_query = "
            UPDATE economic_complements ec
            SET base_wage_id = bw_2020.id
            FROM
                base_wages bw_old,
                base_wages bw_2020
            WHERE
                ec.base_wage_id = bw_old.id
                AND EXTRACT(YEAR FROM bw_old.month_year) >= 2021
                AND bw_old.degree_id = bw_2020.degree_id
                AND EXTRACT(YEAR FROM bw_2020.month_year) = 2020;
        ";

        try {
            $affected_rows = DB::update($update_query);
            $this->command->info("Actualización completa. {$affected_rows} registros de economic_complements fueron actualizados.");
        } catch (\Exception $e) {
            $this->command->error("Ocurrió un error durante el paso de actualización: " . $e->getMessage());
            return;
        }

        $this->command->info('Paso 2: Eliminando registros de BaseWage desde 2021 en adelante...');
        $deleted_count = BaseWage::whereYear('month_year', '>=', 2021)->delete();
        $this->command->info("Eliminación completa. Se eliminaron {$deleted_count} registros de BaseWage.");

        $this->command->info('Paso 3: Actualizando base_wage_id en eco_com_fixed_pensions...');
        $this->command->info('actualizando  eco_com_fixed_pensions.');

        $update_query = "
            UPDATE
                eco_com_fixed_pensions ecfp
            SET
                base_wage_id = latest_ecs.base_wage_id
            FROM (
                SELECT DISTINCT ON (affiliate_id)
                    affiliate_id,
                    base_wage_id
                FROM
                    economic_complements
                WHERE
                    base_wage_id IS NOT NULL
                ORDER BY
                    affiliate_id, id DESC
            ) AS latest_ecs
            WHERE
                ecfp.affiliate_id = latest_ecs.affiliate_id;
        ";

        try {
            $affected_rows = DB::update($update_query);
            $this->command->info("Actualización completa. {$affected_rows} registros de eco_com_fixed_pensions fueron actualizados.");
        } catch (\Exception $e) {
            $this->command->error("ha ocurrido un error durante la actualización: " . $e->getMessage());
        }
    }
}