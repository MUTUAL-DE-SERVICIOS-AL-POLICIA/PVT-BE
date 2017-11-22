<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetirementFundTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fund_modality_types', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('ret_fund_modalities', function(Blueprint $table) {
            $table->bigInteger('ret_fun_modality_type_id')->unsigned()->nullable();
            $table->foreign('ret_fun_modality_type_id')->references('id')->on('ret_fun_modality_types');
        });

        Schema::create('retirement_funds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id')->unsigned();
            $table->bigInteger('ret_fun_modality_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->string('code')->unique();

            $table->string('resolution_code')->nullable();
            $table->date('resolution_date')->nullable();
            $table->string('legal_assessment_code')->nullable();
            $table->date('legal_assessment_date')->nullable();

            $table->enum('type', ['Pago', 'Anticipo']);

            $table->string('accounting_code')->nullable();
            $table->date('accounting_response_date')->nullable();
            $table->string('loan_code')->nullable();
            $table->date('loan_response_date')->nullable();

            $table->date('reception_date')->nullable();
            $table->date('qualification_date')->nullable();
            $table->date('check_file_date')->nullable();
           
            $table->decimal('average_quotable', 13, 2);
            $table->integer('quotations');  
/**/
            $table->decimal('total_quotable', 13, 2);

            $table->decimal('total_additional_quotable', 13, 2);
            $table->decimal('subtotal', 13, 2);
            $table->decimal('performance', 13, 2);
            $table->decimal('total', 13, 2);
            $table->string('comment');
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('ret_fun_modality_id')->references('id')->on('ret_fun_modalities');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_requirement_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps(); 
        });
        Schema::create('ret_fun_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ret_fun_modality_id')->unsigned();
            $table->bigInteger('ret_fun_requirement_type_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('shortened');
            $table->foreign('ret_fun_modality_id')->references('id')->on('ret_fun_modalities');
            $table->foreign('ret_fun_requirement_type_id')->references('id')->on('ret_fun_requirement_types'); 
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_submitted_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('ret_fun_requirement_id')->unsigned();
            $table->date('reception_date');
            $table->boolean('status')->default(0);
            $table->string('comment')->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');
            $table->foreign('ret_fun_requirement_id')->references('id')->on('ret_fun_requirements');
            $table->unique(['retirement_fund_id', 'ret_fun_requirement_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_antecedent_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('shortened');
            $table->timestamps();
        });

        Schema::create('ret_fun_antecedents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('ret_fun_antecedent_file_id')->unsigned();
            $table->boolean('status')->default(0);
            $table->date('reception_date')->nullable();
            $table->string('code')->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');
            $table->foreign('ret_fun_antecedent_file_id')->references('id')->on('ret_fun_antecedent_files');
            $table->unique(['retirement_fund_id', 'ret_fun_antecedent_file_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_applicants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable();
            $table->string('identity_card');
            $table->string('last_name')->nullable();
            $table->string('mothers_last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('surname_husband')->nullable();
            $table->string('kinship')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->enum('civil_status', ['C', 'S', 'V', 'D'])->nullable();
            $table->string('phone_number')->nullable();
            $table->string('cell_phone_number')->nullable();
            $table->string('home_address')->nullable();
            $table->string('work_address')->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');
            $table->foreign('city_identity_card_id')->references('id')->on('cities');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('ret_fun_legal_guardians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable();
            $table->string('identity_card')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mothers_last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('surname_husband')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('cell_phone_number')->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('city_identity_card_id')->references('id')->on('cities');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('address', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id')->unsigned();
            $table->bigInteger('city_address_id')->unsigned()->nullable();
            $table->string('zone')->nullable();
            $table->string('street')->nullable();
            $table->string('number_address')->nullable();
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('city_address_id')->references('id')->on('cities');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('interval_type', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('interval_type_ret_fun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('interval_type_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('interval_type_id')->references('id')->on('interval_type');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('ret_fun_advanced_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->date('date')->nullable();
            $table->decimal('total', 13, 2)->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
