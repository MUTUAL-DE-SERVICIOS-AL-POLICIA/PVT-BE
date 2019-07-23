<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('document_type', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('issued');

            $table->timestamps();
        });

        Schema::table('procedure_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('document_type')->onDelete('cascade');
        });

       
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('procedure_documents', function (Blueprint $table) {
            $table->dropColumn(['document_id']);
        });
        Schema::dropIfExists('document_type');
    }
 }

