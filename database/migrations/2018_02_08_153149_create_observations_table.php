<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
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

        Schema::create('ret_fun_observations', function(Blueprint $table) {   //observaciones de fondo de retiro
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('observation_type_id')->unsigned();
            $table->date('date');
            $table->longText('message');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');
            $table->foreign('observation_type_id')->references('id')->on('observation_types');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quota_aid_observations', function(Blueprint $table) {   //observaciones de Quota y Auxilio
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('quota_aid_mortuary_id')->unsigned();
            $table->bigInteger('observation_type_id')->unsigned();
            $table->date('date');
            $table->longText('message');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade');
            $table->foreign('observation_type_id')->references('id')->on('observation_types');
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
        Schema::drop('eco_com_observations');
        Schema::drop('ret_fun_observations');
        Schema::drop('quota_aid_observations');
    }
}
