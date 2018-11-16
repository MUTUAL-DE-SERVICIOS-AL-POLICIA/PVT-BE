<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropContributionProcessTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quotables');
        Schema::dropIfExists('contribution_commitments');
        Schema::dropIfExists('aid_commitments');
        Schema::dropIfExists('contribution_processes');
        Schema::create('direct_contributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('contributor_type_id'); // (kinship_id)
            $table->unsignedBigInteger('procedure_modality_id');
            $table->unsignedBigInteger('procedure_state_id');
            $table->date('commitment_date');
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->date('start_contribution_date')->nullable();
            $table->date('date');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('contributor_type_id')->references('id')->on('kinships');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->foreign('procedure_state_id')->references('id')->on('procedure_states');
            $table->timestamps();
        });
        Schema::create('contribution_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wf_state_current_id');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('procedure_state_id');
            $table->date('date');
            $table->string('code');
            $table->boolean('inbox_state')->default(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wf_state_current_id')->references('id')->on('wf_states');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('procedure_state_id')->references('id')->on('procedure_states');
            $table->timestamps();
        });
        Schema::create('contribution_process_submitted_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contribution_process_id');
            $table->unsignedBigInteger('procedure_requirement_id');
            $table->date('reception_date');
            $table->date('comment')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->foreign('contribution_process_id')->references('id')->on('contribution_processes');
            $table->foreign('procedure_requirement_id')->references('id')->on('procedure_requirements');
            $table->timestamps();
        });
        Schema::create('quotables', function (Blueprint $table) {
            $table->primary(['contribution_process_id', 'quotable_id', 'quotable_type']);
            $table->unsignedBigInteger('contribution_process_id');
            $table->unsignedBigInteger('quotable_id');
            $table->string('quotable_type');
            $table->foreign('contribution_process_id')->references('id')->on('contribution_processes');
            $table->timestamps();
        });
        Schema::create('payables', function (Blueprint $table) {
            $table->primary(['voucher_id', 'payable_id', 'payable_type']);
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedBigInteger('payable_id');
            $table->string('payable_type');
            $table->foreign('voucher_id')->references('id')->on('vouchers');
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
        Schema::dropIfExists('payables');
        Schema::dropIfExists('quotables');
        Schema::dropIfExists('contribution_process_submitted_documents');
        Schema::dropIfExists('contribution_processes');
        Schema::dropIfExists('direct_contributions');
    }
}
