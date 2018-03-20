<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunContributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_contributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('affiliate_id')->unsigned();
            $table->bigInteger('direct_contribution_id')->unsigned()->nullable();
            $table->bigInteger('degree_id')->unsigned();
            $table->bigInteger('unit_id')->unsigned();
            $table->bigInteger('breakdown_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->date('month_year');
            $table->string('item')->nullable();
            $table->enum('type',['Planilla', 'Directo']);
            $table->decimal('base_wage', 13, 2);
            $table->decimal('dignity_pension', 13, 2)->nullable();
            $table->decimal('seniority_bonus', 13, 2);
            $table->decimal('study_bonus', 13, 2);
            $table->decimal('position_bonus', 13, 2);
            $table->decimal('border_bonus', 13, 2);
            $table->decimal('east_bonus', 13, 2);
            $table->decimal('public_security_bonus', 13, 2)->nullable();
            $table->string('deceased')->nullable();
            $table->string('natality')->nullable();
            $table->string('lactation')->nullable();
            $table->string('prenatal')->nullable();
            $table->decimal('subsidy', 13, 2)->nullable();
            $table->decimal('gain', 13, 2);
            $table->decimal('payable_liquid', 13, 2);
            $table->decimal('quotable', 13, 2);
            $table->decimal('retirement_fund', 13, 2);
            $table->decimal('mortuary_quota', 13, 2);
            $table->decimal('mortuary_aid', 13, 2)->nullable();
            $table->decimal('subtotal', 13, 2)->nullable();
            $table->decimal('ipc', 13, 2)->nullable();
            $table->decimal('total', 13, 2);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('direct_contribution_id')->references('id')->on('direct_contributions');
            $table->foreign('degree_id')->references('id')->on('degrees');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('category_id')->references('id')->on('categories'); 
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds'); 
            $table->timestamps();
        });

        Schema::create('ret_fun_reimbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('affiliate_id')->unsigned();
            $table->bigInteger('direct_contribution_id')->unsigned()->nullable();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->date('month_year');
            $table->enum('type',['Planilla', 'Directo']);
            $table->decimal('base_wage', 13, 2);
            $table->decimal('seniority_bonus', 13, 2);
            $table->decimal('study_bonus', 13, 2);
            $table->decimal('position_bonus', 13, 2);
            $table->decimal('border_bonus', 13, 2);
            $table->decimal('east_bonus', 13, 2);
            $table->decimal('public_security_bonus', 13, 2)->nullable();
            $table->decimal('gain', 13, 2);
            $table->decimal('payable_liquid', 13, 2);
            $table->decimal('quotable', 13, 2);
            $table->decimal('retirement_fund', 13, 2);
            $table->decimal('mortuary_quota', 13, 2);
            $table->decimal('mortuary_aid', 13, 2);
            $table->decimal('subtotal', 13, 2)->nullable();
            $table->decimal('ipc', 13, 2)->nullable();
            $table->decimal('total', 13, 2);
            $table->string('months')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('direct_contribution_id')->references('id')->on('direct_contributions');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
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
        Schema::drop('ret_fun_contributions');
        Schema::drop('ret_fun_reimbursements');
    }
}
