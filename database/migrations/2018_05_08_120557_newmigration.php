<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Newmigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('correlative')->unsigned()->nullable();
        });

        Schema::create('ret_fun_correlatives',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('wf_state_id')->unsigned();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->string('correlative')->unsigned();
            $table->foreign('wf_state_id')->references('id')->on('wf_states');
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
