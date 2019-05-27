<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('record_type_id');
            $table->unsignedBigInteger('recordable_id');
            $table->string('recordable_type');
            $table->text('message');
            $table->dateTime('date');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('record_type_id')->references('id')->on('record_types');
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
        Schema::dropIfExists('document_records');
    }
}
