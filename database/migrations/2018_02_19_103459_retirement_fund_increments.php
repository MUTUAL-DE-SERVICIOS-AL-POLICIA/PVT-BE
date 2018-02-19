<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetirementFundIncrements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_increments', function (Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();                       
            $table->bigInteger('retirement_fund_id')->unsigned();            
            $table->bigInteger('number')->unsigned(); //numero correlativo            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');
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
        Schema::drop('ret_fun_increments');
    }
}
