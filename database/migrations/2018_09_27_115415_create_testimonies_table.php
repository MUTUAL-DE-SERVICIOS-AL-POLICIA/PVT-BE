<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestimoniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('affiliate_id');
            $table->string('document_type')->nullable();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->string('court')->nullable();
            $table->string('place')->nullable();
            $table->string('notary')->nullable();
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
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
        Schema::dropIfExists('testimonies');
    }
}
