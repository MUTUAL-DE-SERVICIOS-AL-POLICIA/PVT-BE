<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsWfRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wf_records', function (Blueprint $table) {
            $table->unsignedBigInteger('old_wf_state_id')->nullable();
            $table->foreign('old_wf_state_id')->references('id')->on('wf_states');
            $table->unsignedBigInteger('old_user_id')->nullable();
            $table->foreign('old_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wf_records', function (Blueprint $table) {
            $table->dropColumn(['old_wf_state_id']);
            $table->dropColumn(['old_user_id']);
        });
    }
}
