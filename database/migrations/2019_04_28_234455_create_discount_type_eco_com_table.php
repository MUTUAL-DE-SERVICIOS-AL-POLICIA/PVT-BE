<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountTypeEcoComTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_type_economic_complement', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('discount_type_id')->unsigned()->nullable();
            $table->bigInteger('economic_complement_id')->unsigned();
            $table->unique(['discount_type_id', 'economic_complement_id']);
            $table->decimal('amount', 13, 2)->nullable();
            $table->string('message')->nullable();
            $table->date('date');
            $table->foreign('discount_type_id')->references('id')->on('discount_types')->onDelete('cascade');
            $table->foreign('economic_complement_id')->references('id')->on('economic_complements')->onDelete('cascade');
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
        Schema::dropIfExists('discount_type_economic_complement');
    }
}
