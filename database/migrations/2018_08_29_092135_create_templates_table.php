<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('wf_state_id');
            $table->unsignedBigInteger('ret_fun_procedure_id');
            $table->text('template')->nullable();
            $table->foreign('wf_state_id')->references('id')->on('wf_states');
            $table->foreign('ret_fun_procedure_id')->references('id')->on('ret_fun_procedures');
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
        Schema::dropIfExists('ret_fun_templates');
    }
}
