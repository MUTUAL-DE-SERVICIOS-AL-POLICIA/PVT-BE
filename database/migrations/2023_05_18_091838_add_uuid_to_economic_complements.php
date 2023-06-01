<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToEconomicComplements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('economic_complements', function (Blueprint $table) {
            //
            $table->uuid('uuid')->unique()->nullable()->comment('Identificador Unico Universal');
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
            $table->dropColumn('uuid');
        });
    }
}
