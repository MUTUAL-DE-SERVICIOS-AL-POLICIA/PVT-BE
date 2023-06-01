<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnContributionPassives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contribution_passives', function (Blueprint $table) {
            $table->unsignedBigInteger('contribution_type_mortuary_id')->nullable();
            $table->foreign('contribution_type_mortuary_id')->references('id')->on('contribution_type_quota_aids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contribution_passives', function (Blueprint $table) {
            $table->dropColumn('contribution_type_mortuary_id');
        });
    }
}
