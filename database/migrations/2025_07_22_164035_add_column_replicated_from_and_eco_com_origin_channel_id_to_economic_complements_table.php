<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReplicatedFromAndEcoComOriginChannelIdToEconomicComplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('economic_complements', function (Blueprint $table) {
            $table->unsignedBigInteger('replicated_from_eco_com_id')->nullable()->unique();
            $table->unsignedInteger('eco_com_origin_channel_id')->nullable();

            $table->foreign('eco_com_origin_channel_id')
                  ->references('id')->on('eco_com_origin_channel');
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
            $table->dropForeign(['eco_com_origin_channel_id']);
            $table->dropColumn('eco_com_origin_channel_id');
            $table->dropColumn('replicated_from_eco_com_id');
        });
    }
}