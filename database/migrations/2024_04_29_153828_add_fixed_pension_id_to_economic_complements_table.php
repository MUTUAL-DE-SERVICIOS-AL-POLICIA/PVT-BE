<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFixedPensionIdToEconomicComplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('economic_complements', function (Blueprint $table) {
            $table ->bigInteger('eco_com_fixed_pension_id')->nullable();

            $table->foreign('eco_com_fixed_pension_id')->references('id')->on('eco_com_fixed_pensions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('economic_complements', function (Blueprint $table) {
            $table->dropColumn('eco_com_fixed_pension_id');
        });
    }
}
