<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaAdressMortuory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function(Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('city_address_id')->unsigned()->nullable(); // identificador de la dirección y ciudad
            $table->string('zone')->nullable(); // zona
            $table->string('street')->nullable(); // calle
            $table->string('number_address')->nullable(); //numero de domicilio
            $table->foreign('city_address_id')->references('id')->on('cities'); //identificación del ci
          	$table->timestamps();
        });
      
        Schema::create('address_ret_fun_beneficiary', function(Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('ret_fun_beneficiary_id')->unsigned();
            $table->bigInteger('address_id')->unsigned();
            $table->foreign('ret_fun_beneficiary_id')->references('id')->on('ret_fun_beneficiaries'); 
            $table->foreign('address_id')->references('id')->on('addresses'); 
          	$table->timestamps();
        });

        Schema::create('address_quota_aid_beneficiaries', function(Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('quota_aid_beneficiary_id')->unsigned();
            $table->bigInteger('address_id')->unsigned();
            $table->foreign('quota_aid_beneficiary_id')->references('id')->on('quota_aid_beneficiaries'); 
            $table->foreign('address_id')->references('id')->on('addresses'); 
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
        Schema::drop('address_ret_fun_beneficiary');
        Schema::drop('addresses');
    }
}
