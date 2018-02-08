<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEconomicComplementTables extends Migration
{
   
    public function up()
    {
        Schema::create('eco_com_observations', function(Blueprint $table) {   //observaciones de complemento
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned(); //usuario
            $table->bigInteger('economic_omplement_id')->unsigned(); //id complemento
            $table->bigInteger('observation_type_id')->unsigned();  //tipo de observacion
            $table->date('date');       //fecha de observacion
            $table->longText('message'); // Dato comentario
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('economic_omplement_id')->references('id')->on('economic_complements')->onDelete('cascade');
            $table->foreign('observation_type_id')->references('id')->on('observation_types');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    
    public function down()
    {
        Schema::drop('eco_com_observations');
    }
}
