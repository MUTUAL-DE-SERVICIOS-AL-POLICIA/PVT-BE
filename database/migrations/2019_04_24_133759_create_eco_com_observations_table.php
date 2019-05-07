<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcoComObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observables', function (Blueprint $table) {
            // $table->primary(['observation_type_id', 'observable_id', 'observable_type']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('observation_type_id');
            $table->unsignedBigInteger('observable_id');
            $table->string('observable_type');
            $table->string('message')->nullable();
            $table->dateTime('date');
            $table->boolean('enabled')->default(false);
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('observables');
    }
}
