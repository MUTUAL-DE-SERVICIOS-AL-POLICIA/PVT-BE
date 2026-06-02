<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEconomicComplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('economic_complements', function (Blueprint $table) {
            $table->unsignedBigInteger('eco_com_rent_id')->nullable();

            $table->foreign('eco_com_rent_id')->references('id')->on('eco_com_rents');
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
            $table->dropForeign(['eco_com_rent_id']);

            $table->dropColumn('eco_com_rent_id');
        });
    }
}
