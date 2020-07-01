<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAffiliateWithSigep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_entities', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::table('affiliates', function (Blueprint $table) {

            $table->unsignedBigInteger('account_number')->nullable();
            $table->unsignedBigInteger('financial_entity_id')->nullable();
            $table->enum('sigep_status', ['ACTIVO', 'ELABORADO', 'VALIDADO', 'SIN REGISTRO'])->nullable();
            $table->foreign('financial_entity_id')->references('id')->on('financial_entities');

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_entities');
    }
}
