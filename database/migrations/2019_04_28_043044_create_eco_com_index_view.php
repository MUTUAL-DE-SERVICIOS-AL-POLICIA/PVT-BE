<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateEcoComIndexView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW view_eco_com_index AS
        select id, code, reception_date, total, affiliate_id, city_id, state, wf_current_state_id, eco_com_modality_id, eco_com_procedure_id, workflow_id from economic_complements where code not like '%A' and economic_complements.deleted_at is null order by split_part(code, '/',1)::integer desc"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW view_eco_com_index");
    }
}
