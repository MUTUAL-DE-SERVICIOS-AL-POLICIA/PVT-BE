<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsQuota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE quota_aid_mortuaries ALTER COLUMN quota_aid_procedure_id DROP NOT NULL;");
        DB::statement("ALTER TABLE quota_aid_mortuaries ALTER COLUMN procedure_modality_id SET NOT NULL;");
        DB::statement("ALTER TABLE public.contribution_passives DROP CONSTRAINT contribution_passives_affiliate_rent_class_check;");
        DB::statement("ALTER TABLE public.contribution_passives ADD CONSTRAINT contribution_passives_affiliate_rent_class_check
        CHECK (((affiliate_rent_class)::text = ANY (ARRAY[('VEJEZ'::character varying)::text,('VIUDEDAD'::character varying)::text,('VEJEZ/VIUDEDAD'::character varying)::text])));");
        DB::statement("update quota_aid_procedures set is_enabled = false where year = '2020-12-31';");

        DB::statement("update procedure_types set name = 'Pago del Beneficio Cuota Mortuoria' where id  = 3;");
        DB::statement("update procedure_modalities set name  = 'por riesgo común'  where id  = 9;");
        DB::statement("update procedure_modalities set name  = 'en cumplimiento de funciones'  where id  = 8");
        DB::statement("update procedure_types set name = 'Pago del Beneficio Auxilio Mortuorio' where id  = 4 ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
