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
            $table ->bigInteger('regulation_id')->nullable();

            $table->foreign('regulation_id')->references('id')->on('regulations');
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
