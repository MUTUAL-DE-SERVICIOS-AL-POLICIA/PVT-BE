<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Records extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('affiliate_records_pvt', function (Blueprint $table) {// historial del afiliado como ya existe la tabla affilate_record le puse pvt(plataforma virtual de trámites hdps)
                $table->bigIncrements('id');
                $table->bigInteger('user_id')->unsigned();
                $table->bigInteger('affiliate_id')->unsigned();
                $table->string('message')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('affiliate_id')->references('id')->on('affiliates');
                $table->timestamps();
        });
        Schema::create('ret_fun_records', function (Blueprint $table) {// historial para los trámites de fondo de retiro
                $table->bigIncrements('id');
                $table->bigInteger('user_id')->unsigned();
                $table->bigInteger('ret_fun_id')->unsigned();
                $table->string('message')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('ret_fun_id')->references('id')->on('retirement_funds');
                $table->timestamps();
        });
        Schema::create('sequences_records', function (Blueprint $table) {// historial para las secuencias
                $table->bigIncrements('id');
                $table->bigInteger('user_id')->unsigned();
                $table->bigInteger('wf_state_id')->unsigned();
                $table->string('message')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('wf_state_id')->references('id')->on('wf_states');
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
