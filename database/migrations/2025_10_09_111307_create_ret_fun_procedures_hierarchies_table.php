<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunProceduresHierarchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_procedures_hierarchies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ret_fun_procedure_id');
            $table->unsignedBigInteger('hierarchy_id');
            $table->boolean('apply_contributions_limit')->default(true);
            $table->decimal('average_salary_limit', 13, 2)->default(10800);
            $table->timestamps();

            $table->foreign('ret_fun_procedure_id')
                ->references('id')->on('ret_fun_procedures')
                ->onDelete('restrict');
            $table->foreign('hierarchy_id')
                ->references('id')->on('hierarchies')
                ->onDelete('restrict');

            $table->unique(['ret_fun_procedure_id', 'hierarchy_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ret_fun_procedures_hierarchies');
    }
}
