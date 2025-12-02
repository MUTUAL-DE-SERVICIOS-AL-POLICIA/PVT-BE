<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEcoComFixedPensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_com_fixed_pensions', function (Blueprint $table) {
            $table->unsignedBigInteger('eco_com_rent_id')->nullable();
            $table->unsignedBigInteger('base_wage_id')->nullable();

            $table->foreign('eco_com_rent_id')->references('id')->on('eco_com_rents');
            $table->foreign('base_wage_id')->references('id')->on('base_wages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eco_com_fixed_pensions', function (Blueprint $table) {
            $table->dropForeign(['eco_com_rent_id']);
            $table->dropForeign(['base_wage_id']);

            $table->dropColumn(['eco_com_rent_id', 'base_wage_id']);
        });
    }
}
