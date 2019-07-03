<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('documents', function (Blueprint $table) { // Cuantia
            $table->increments('id');
           $table->string('name');
           $table->string('type')->nullable();
           $table->string('issued');
           $table->timestamps();

        });

        Schema::table('procedure_documents', function (Blueprint $table) {
            $table->bigInteger('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
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
        
        Schema::table('procedure_documents', function (Blueprint $table) {
            $table->bigInteger('document_id')->nullable();
            
        });
        Schema::dropIfExists('documents');
    }
}
