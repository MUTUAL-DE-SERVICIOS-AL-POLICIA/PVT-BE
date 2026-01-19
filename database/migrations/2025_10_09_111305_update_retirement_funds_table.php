<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRetirementFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->decimal('sum_contributions', 13, 2)->nullable();
            $table->decimal('yield', 13, 2)->nullable();
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
        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->dropColumn(['sum_contributions', 'yield', 'used_contributions_limit']);
        });
    }
}
