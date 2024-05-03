<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegulationIdToEcoComProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_com_procedures', function (Blueprint $table) {
            $table ->bigInteger('eco_com_regulation_id')->nullable();

            $table->foreign('eco_com_regulation_id')->references('id')->on('eco_com_regulations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eco_com_procedures', function (Blueprint $table) {
            $table->dropColumn('procedure_id');
        });
    }
}
