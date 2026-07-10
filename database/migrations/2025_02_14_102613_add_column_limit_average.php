<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLimitAverage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            $table->decimal('limit_average', 13, 2)->nullable();
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->decimal('used_limit_average', 13, 2)->default(0, 00)->nullable();
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
            $table->dropColumn('limit_average');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->dropColumn('used_limit_average');
        });
    }
}
