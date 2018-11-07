<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->primary(['tag_id', 'taggable_id', 'taggable_type']);
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            $table->dateTime('date');
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
        Schema::dropIfExists('taggables');
    }
}
