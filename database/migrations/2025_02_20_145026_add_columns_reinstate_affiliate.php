<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsReinstateAffiliate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->date('date_entry_reinstatement')->nullable();
            $table->date('date_derelict_reinstatement')->nullable();
            $table->date('date_last_contribution_reinstatement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('date_entry_reinstatement');
            $table->dropColumn('date_derelict_reinstatement');
            $table->dropColumn('date_last_contribution_reinstatement');
        });
    }
}
