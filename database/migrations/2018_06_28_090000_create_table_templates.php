<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('module_id')->unsigned()->nullable();
            $table->string('name');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->timestamps();
        });
        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_type_id')->unsigned()->nullable();
            $table->bigInteger('procedure_modality_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->text('body')->nullable();
            $table->foreign('template_type_id')->references('id')->on('template_types');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
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
        Schema::drop('templates');
        Schema::drop('template_types');
    }
}
