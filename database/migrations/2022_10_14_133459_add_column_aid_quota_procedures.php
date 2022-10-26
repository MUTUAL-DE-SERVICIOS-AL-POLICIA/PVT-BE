<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAidQuotaProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quota_aid_procedures', function (Blueprint $table) {
            $table->integer('months_min')->nullable();
            $table->integer('months_max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_aid_procedures', function (Blueprint $table) {
            $table->dropColumn('months_min');
            $table->dropColumn('months_max');
        });
    }
}
