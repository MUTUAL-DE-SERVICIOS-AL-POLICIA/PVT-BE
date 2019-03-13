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
        Schema::create('economic_complements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('eco_com_state_id');
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
            //calculate complement
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


            $table->decimal('aps_total_cc', 13, 2)->nullable();// componente de aps
            $table->decimal('aps_total_fsa', 13, 2)->nullable();// componente de aps
            $table->decimal('aps_total_fs', 13, 2)->nullable();// componente de aps

            $table->string('message')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
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
        //
    }
}
