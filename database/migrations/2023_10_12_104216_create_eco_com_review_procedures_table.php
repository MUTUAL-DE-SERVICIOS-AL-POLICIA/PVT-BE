<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcoComReviewProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_com_review_procedures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('review_procedure_id');
            $table->unsignedBigInteger('economic_complement_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_valid');
            $table->foreign('review_procedure_id')->references('id')->on('review_procedures');
            $table->foreign('economic_complement_id')->references('id')->on('economic_complements');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('eco_com_review_procedures');
    }
}
