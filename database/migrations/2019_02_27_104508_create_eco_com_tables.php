<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcoComTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::create('eco_com_procedures', function(Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->bigInteger('user_id')->unsigned();
        //     $table->date('year');
        //     $table->enum('semester', ['Primer', 'Segundo']);
        //     $table->date('normal_start_date');
        //     $table->date('normal_end_date');
        //     $table->date('lagging_start_date');
        //     $table->date('lagging_end_date');
        //     $table->date('additional_start_date');
        //     $table->date('additional_end_date');
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->unique(['year','semester']);
        //     $table->timestamps();
        // });
        // Schema::create('eco_com_state_types', function(Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('name');
        // });
        // Schema::create('eco_com_states', function(Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->bigInteger('eco_com_state_type_id')->unsigned();
        //     $table->string('name');
        //     $table->foreign('eco_com_state_type_id')->references('id')->on('eco_com_state_types');
        // });
        Schema::create('eco_com_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('procedure_modality_id');
            $table->unsignedBigInteger('eco_com_procedure_start_id');
            $table->unsignedBigInteger('pension_entity_id');
            $table->boolean('status')->default(true);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->foreign('pension_entity_id')->references('id')->on('pension_entities');
            $table->foreign('eco_com_procedure_start_id')->references('id')->on('eco_com_procedures');
            $table->timestamps();
        });

        Schema::create('eco_com_submitted_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('eco_com_process_id')->unsigned();
            $table->bigInteger('procedure_requirement_id')->unsigned();
            $table->boolean('is_valid')->default(false);
            $table->date('reception_date');
            $table->text('comment')->nullable();
            $table->foreign('eco_com_process_id')->references('id')->on('eco_com_processes')->onDelete('cascade');
            $table->foreign('procedure_requirement_id')->references('id')->on('procedure_requirements');
            $table->unique(['eco_com_process_id', 'procedure_requirement_id']);
            $table->timestamps();
        });

        Schema::create('economic_complements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('eco_com_process_id');
            $table->unsignedBigInteger('eco_com_state_id');
            $table->unsignedBigInteger('procedure_state_id');
            $table->unsignedBigInteger('eco_com_procedure_id');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('wf_state_current_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('degree_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('base_wage_id');
            $table->unsignedBigInteger('complementary_factor_id');

            $table->string('code')->unique();
            $table->date('reception_date');
            $table->boolean('inbox_state')->default(false);

            $table->decimal('sub_total_rent', 13, 2)->nullable();
            $table->decimal('dignity_pension', 13, 2)->nullable();
            $table->decimal('total_rent', 13, 2)->nullable();
            $table->decimal('total_rent_calc', 13, 2)->nullable();
            $table->decimal('salary_reference', 13, 2)->nullable();
            $table->decimal('seniority', 13, 2)->nullable();
            $table->decimal('salary_quotable', 13, 2)->nullable();
            $table->decimal('difference', 13, 2)->nullable();
            $table->decimal('total_amount_semester', 13, 2)->nullable();
            $table->decimal('complementary_factor', 13, 2)->nullable();
            $table->decimal('reimbursement', 13, 2)->nullable();
            $table->decimal('sub_total', 13, 2)->nullable();
            $table->decimal('total', 13, 2)->nullable();

            $table->decimal('aps_total_cc', 13, 2)->nullable();
            $table->decimal('aps_total_fsa', 13, 2)->nullable();
            $table->decimal('aps_total_fs', 13, 2)->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('eco_com_process_id')->references('id')->on('eco_com_processes');
            $table->foreign('eco_com_state_id')->references('id')->on('eco_com_states');
            $table->foreign('procedure_state_id')->references('id')->on('procedure_states');
            $table->foreign('eco_com_procedure_id')->references('id')->on('eco_com_procedures');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('wf_state_current_id')->references('id')->on('wf_states');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('degree_id')->references('id')->on('degrees');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('base_wage_id')->references('id')->on('base_wages');
            $table->foreign('complementary_factor_id')->references('id')->on('complementary_factors');
            $table->timestamps();
        });
        // Schema::create('eco_com_beneficiaries', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedBigInteger('economic_complement_id');
        //     $table->unsignedBigInteger('city_identity_card_id')->nullable();
        //     $table->string('identity_card')->nullable();
        //     $table->string('last_name')->nullable();
        //     $table->string('mothers_last_name')->nullable();
        //     $table->string('first_name')->nullable();
        //     $table->string('second_name')->nullable();
        //     $table->string('surname_husband')->nullable();
        //     $table->date('birth_date')->nullable();
        //     $table->bigInteger('nua')->nullable();
        //     $table->enum('gender', ['M', 'F'])->default('M');
        //     $table->enum('civil_status', ['C', 'S', 'V', 'D'])->nullable();
        //     $table->string('phone_number')->nullable();
        //     $table->string('cell_phone_number')->nullable();
        //     $table->foreign('economic_complement_id')->references('id')->on('economic_complements')->onDelete('cascade');
        //     $table->foreign('city_identity_card_id')->references('id')->on('cities');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('eco_com_legal_guardians', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedBigInteger('economic_complement_id');
        //     $table->unsignedBigInteger('city_identity_card_id')->nullable();
        //     $table->string('identity_card')->nullable();
        //     $table->string('last_name')->nullable();
        //     $table->string('mothers_last_name')->nullable();
        //     $table->string('first_name')->nullable();
        //     $table->string('second_name')->nullable();
        //     $table->string('surname_husband')->nullable();
        //     $table->string('phone_number')->nullable();
        //     $table->string('cell_phone_number')->nullable();
        //     $table->foreign('economic_complement_id')->references('id')->on('economic_complements')->onDelete('cascade');
        //     $table->foreign('city_identity_card_id')->references('id')->on('cities');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('economic_complements');
        Schema::drop('eco_com_submitted_documents');
        Schema::drop('eco_com_processes');
    }
}
