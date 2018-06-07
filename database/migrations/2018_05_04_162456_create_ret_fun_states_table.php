<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps(); 
            $table->softDeletes();
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->bigInteger('ret_fun_state_id')->unsigned()->nullable();
            $table->foreign('ret_fun_state_id')->references('id')->on('ret_fun_states')->onDelete('cascade');
        });

        //ret_fun_observations_enabled
        Schema::table('ret_fun_observations', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(false);
        });

        
        Schema::table('roles', function (Blueprint $table) {
            $table->string('correlative')->unsigned()->nullable();
        });

        Schema::create('ret_fun_correlatives',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('wf_state_id')->unsigned();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->string('code')->unsigned();
            $table->foreign('wf_state_id')->references('id')->on('wf_states');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('ret_fun_beneficiaries', function (Blueprint $table) {
            $table->boolean('state')->default(false);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ret_fun_states');
    }
}
