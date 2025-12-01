referential_limit<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEcoComRentSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("===== INICIO DE ACTUALIZACIONES CON ECO_COM_RENTS =====");

        DB::beginTransaction();

        try {

            /* -------------------------------------------------------------
                1. Actualizar referential_limit desde eco_com_procedures columna indicador
            --------------------------------------------------------------*/
            $this->command->info("1) Actualizar referential_limit desde eco_com_procedures columna indicador.");

            $sql1 = "
                UPDATE eco_com_rents ecr
                SET referential_limit = ecp.indicator
                FROM eco_com_procedures ecp
                WHERE ecr.semester = ecp.semester
                AND ecr.year = ecp.year
            ";

            $affected1 = DB::update($sql1);

            $this->logResult(
                "Actualiza el campo referential_limit según eco_com_procedures",
                $affected1
            );

            /* -------------------------------------------------------------
                2. Actualizar eco_com_rent_id en economic_complements para trámites hasta 2/2023
            --------------------------------------------------------------*/
            $this->command->info("2) Actualizar eco_com_rent_id en economic_complements para trámites hasta 2/2023.");

            $sql2 = "
                WITH datos AS (
                    SELECT
                        ec.id as ec_id,
                        ec.code, ec.affiliate_id,
                        ec.eco_com_reception_type_id,
                        ec.eco_com_modality_id,
                        ecm.procedure_modality_id,
                        ecp.id as eco_com_procedure_id,
                        ecr.id as eco_com_rent_id,
                        ecr.average,
                        ecr.referential_limit,
                        ecr.degree_id,
                        ecr.procedure_modality_id,
                        ec.eco_com_fixed_pension_id
                    FROM economic_complements ec
                    LEFT JOIN eco_com_procedures ecp
                        ON ec.eco_com_procedure_id = ecp.id
                    LEFT JOIN eco_com_modalities ecm
                        ON ec.eco_com_modality_id = ecm.id
                    LEFT JOIN eco_com_rents ecr
                        ON ecr.year = ecp.year
                        AND ecr.semester = ecp.semester
                        AND ec.degree_id = ecr.degree_id
                        AND (
                            CASE 
                                WHEN ecm.procedure_modality_id = 31 THEN 29
                                ELSE ecm.procedure_modality_id
                            END
                        ) = ecr.procedure_modality_id
                    WHERE ec.code NOT ILIKE '%A%'
                      AND ec.eco_com_procedure_id < 26
                )
                UPDATE economic_complements ec
                SET eco_com_rent_id = datos.eco_com_rent_id
                FROM datos
                WHERE ec.id = datos.ec_id
            ";

            $affected2 = DB::update($sql2);

            $this->logResult(
                "Asigna el eco_com_rent_id correspondiente según modalidad, grado, año y semestre",
                $affected2
            );

            /* -------------------------------------------------------------
                3. Actualizar eco_com_rent_id en la tabla eco_com_fixed_pensions (fija)
            --------------------------------------------------------------*/
            $this->command->info("3) Actualizar eco_com_rent_id en la tabla eco_com_fixed_pensions (fija)");

            $sql3 = "
                WITH datos AS (
                    SELECT DISTINCT ON (ec.affiliate_id, ecm.procedure_modality_id)
                        ec.id AS ec_id,
                        ec.code,
                        ec.affiliate_id,
                        ec.eco_com_fixed_pension_id,
                        ecm.procedure_modality_id,
                        ecp.id AS eco_com_procedure_id,
                        ecr.id AS eco_com_rent_id,
                        ecr.average,
                        ecr.referential_limit,
                        ecr.degree_id AS rent_degree_id,
                        ecr.procedure_modality_id AS rent_modality_id,
                        ecf.id AS ecf_id,
                        ecf.eco_com_procedure_id AS fp_procedure_id
                    FROM economic_complements ec
                    JOIN eco_com_procedures ecp
                        ON ec.eco_com_procedure_id = ecp.id
                    JOIN eco_com_modalities ecm
                        ON ec.eco_com_modality_id = ecm.id
                    JOIN eco_com_fixed_pensions ecf
                        ON ec.eco_com_fixed_pension_id = ecf.id
                       AND ec.affiliate_id = ecf.affiliate_id
                    JOIN eco_com_rents ecr
                        ON ecr.year = ecp.year
                       AND ecr.semester = ecp.semester
                       AND ecr.degree_id = ec.degree_id
                       AND (
                            CASE 
                                WHEN ecm.procedure_modality_id = 31 THEN 29
                                ELSE ecm.procedure_modality_id
                            END
                        ) = ecr.procedure_modality_id
                    WHERE ec.code NOT ILIKE '%A%'
                      AND ec.eco_com_procedure_id >= 26
                    ORDER BY ec.affiliate_id, ecm.procedure_modality_id, ec.eco_com_procedure_id
                )
                UPDATE eco_com_fixed_pensions ecf
                SET eco_com_rent_id = datos.eco_com_rent_id
                FROM datos
                WHERE ecf.id = datos.ecf_id
            ";

            $affected3 = DB::update($sql3);

            $this->logResult(
                "Asigna rent a la pensión fija considerando las el afiliado y las modalidades",
                $affected3
            );

            /* -------------------------------------------------------------
                4. Actualizar eco_com_rent_id en trámites posteriores a 2/2023 de economic_complemnents
            --------------------------------------------------------------*/
            $this->command->info("4) Actualizar eco_com_rent_id en trámites posteriores a 2/2023 de economic_complemnents");

            $sql4 = "
                WITH res AS (
                    SELECT 
                        ec.id,
                        ecf.eco_com_rent_id
                    FROM economic_complements ec
                    JOIN eco_com_fixed_pensions ecf
                        ON ec.eco_com_fixed_pension_id = ecf.id
                    WHERE ec.code NOT ILIKE '%A%'
                      AND ec.eco_com_fixed_pension_id IS NOT NULL
                )
                UPDATE economic_complements ec
                SET eco_com_rent_id = res.eco_com_rent_id
                FROM res
                WHERE ec.id = res.id
            ";

            $affected4 = DB::update($sql4);

            $this->logResult(
                "Obtiene eco_com_rent_id desde fixed_pensions",
                $affected4
            );


            DB::commit();
            $this->command->info("===== FIN =====");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("ERROR: " . $e->getMessage());
        }
    }


    // -----------------------------------------
    // Función para mostrar resultados descriptivos
    // -----------------------------------------
    private function logResult($afectados, $descripcion)
    {
        $this->command->info("----------------------------------------");
        $this->command->info("Descripción: $descripcion");
        $this->command->info("Registros afectados: $afectados");
        $this->command->info("----------------------------------------");
    }
}

