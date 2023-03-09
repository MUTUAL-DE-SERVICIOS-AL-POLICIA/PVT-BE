<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnQuotaAidMortuaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->integer('number_qualified_contributions')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->dropColumn('number_qualified_contributions');
        });
    }
}
