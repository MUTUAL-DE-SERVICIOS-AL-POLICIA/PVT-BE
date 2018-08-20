<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotaAidCorrelativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_aid_correlatives', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('wf_state_id')->unsigned()->nullable();
            $table->bigInteger('quota_aid_mortuary_id')->unsigned()->nullable();
            $table->string('code')->nullable();
            $table->date('date')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wf_state_id')->references('id')->on('wf_states');
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quota_aid_correlatives');
    }
}
