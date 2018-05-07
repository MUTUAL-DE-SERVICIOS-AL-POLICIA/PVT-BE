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
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->bigInteger('ret_fun_state_id')->unsigned()->nullable();
            $table->foreign('ret_fun_state_id')->references('id')->on('ret_fun_states')->onDelete('cascade');
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
