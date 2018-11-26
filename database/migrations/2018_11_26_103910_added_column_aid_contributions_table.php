<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnAidContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aid_contributions', function (Blueprint $table) {
            $table->decimal('mortuary_aid', 13, 2)->nullable();
        });
        Schema::table('contribution_processes', function (Blueprint $table) {
            $table->decimal('total', 13, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aid_contributions', function (Blueprint $table) {
            $table->dropColumn('mortuary_aid');
        });
        Schema::table('contribution_processes', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
}
