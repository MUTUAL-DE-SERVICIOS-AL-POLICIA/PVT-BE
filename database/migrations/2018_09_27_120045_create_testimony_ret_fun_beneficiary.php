<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimonyRetFunBeneficiary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_beneficiary_testimony', function (Blueprint $table) {
            $table->bigInteger('ret_fun_beneficiary_id')->unsigned();
            $table->bigInteger('testimony_id')->unsigned();
            $table->foreign('ret_fun_beneficiary_id')->references('id')->on('ret_fun_beneficiaries');
            $table->foreign('testimony_id')->references('id')->on('testimonies');
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
