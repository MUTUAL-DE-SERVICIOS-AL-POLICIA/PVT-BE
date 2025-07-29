<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateColumnRfProcedures extends Migration
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
            $table->integer('contributions_limit')->default(360);
            $table->dropColumn('is_enabled');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->integer('used_contributions_limit')->nullable();
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
            $table->boolean('is_enabled')->default(true);
            $table->dropColumn('start_date');
            $table->dropColumn('contributions_limit');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->dropColumn('used_contributions_limit');
        });
    }
}
