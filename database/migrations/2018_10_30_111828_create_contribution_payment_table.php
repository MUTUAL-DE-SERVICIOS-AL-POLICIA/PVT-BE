<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribution_processes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('wf_state_current_id');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('procedure_modality_id');
            $table->dateTime('date');
            $table->string('code');
            $table->boolean('inbox_state')->default(false);
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wf_state_current_id')->references('id')->on('wf_states');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->timestamps();
        });
        Schema::create('quotables', function (Blueprint $table) {
            $table->primary(['contribution_process_id', 'quotable_id', 'quotable_type']);
            $table->unsignedBigInteger('contribution_process_id');
            $table->unsignedBigInteger('quotable_id');
            $table->string('quotable_type');
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
        Schema::dropIfExists('quotables');
        Schema::dropIfExists('contribution_processes');
    }
}
