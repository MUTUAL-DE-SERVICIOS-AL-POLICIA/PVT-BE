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
        Schema::create('ret_fun_contribution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('contribution_id')->unsigned();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('contribution_id')->references('id')->on('contributions'); 
            $table->timestamps();
        });

        Schema::create('ret_fun_reimbursements', function (Blueprint $table) {
            $table->bigIncrements('id');     
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('reimbursement_id')->unsigned();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('reimbursement_id')->references('id')->on('reimbursements');
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
        Schema::drop('ret_fun_contribution');
        Schema::drop('ret_fun_reimbursements');  
    }
}
