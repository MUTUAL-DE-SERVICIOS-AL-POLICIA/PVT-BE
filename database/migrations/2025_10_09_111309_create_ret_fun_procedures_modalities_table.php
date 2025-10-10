<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunProceduresModalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_procedures_modalities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ret_fun_procedure_id');
            $table->unsignedBigInteger('procedure_modality_id');
            $table->decimal('annual_percentage_yield', 13, 2);
            $table->timestamps();

            $table->foreign('ret_fun_procedure_id')
                ->references('id')->on('ret_fun_procedures')
                ->onDelete('restrict');
            $table->foreign('procedure_modality_id')
                ->references('id')->on('procedure_modalities')
                ->onDelete('restrict');

            $table->unique(['ret_fun_procedure_id', 'procedure_modality_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ret_fun_procedures_modalities');
    }
}
