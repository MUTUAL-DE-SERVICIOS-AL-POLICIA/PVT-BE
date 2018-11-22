<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnsContributionProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contribution_processes', function (Blueprint $table) {
            $table->enum('type', ['N', 'A'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contribution_processes', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
