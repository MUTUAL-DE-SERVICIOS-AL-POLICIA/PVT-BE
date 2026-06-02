<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAdditionalDatesNullableInEcoComProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_com_procedures', function (Blueprint $table) {
            $table->date('additional_start_date')->nullable()->change();
            $table->date('additional_end_date')->nullable()->change();
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
            $table->date('additional_start_date')->nullable(false)->change();
            $table->date('additional_end_date')->nullable(false)->change();
        });
    }
}
