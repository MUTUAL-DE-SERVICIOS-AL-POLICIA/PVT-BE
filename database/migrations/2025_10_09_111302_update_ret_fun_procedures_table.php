<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRetFunProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            $table->date('start_date')->default(now());
            $table->integer('contributions_limit')->default(0);
            $table->dropColumn('is_enabled');
            $table->dropColumn('limit_average');
            $table->dropColumn('annual_yield');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            // Eliminamos las columnas agregadas en la migración 'up'
            $table->dropColumn(['start_date', 'contributions_limit']);

            // Volvemos a crear las columnas eliminadas en 'up'
            $table->boolean('is_enabled')->default(true);
            $table->decimal('limit_average', 13, 2)->nullable();
            $table->decimal('annual_yield', 13, 2)->nullable();
        });
    }
}
