<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetFunEma2026 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // **Modificación a tablas existentes**
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            $table->date('start_date')->default(now());
            $table->integer('contributions_limit')->default(0);
            $table->dropColumn('is_enabled');
            $table->dropColumn('limit_average');
            $table->dropColumn('annual_yield');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->decimal('sum_contributions', 13, 2)->nullable();
            $table->decimal('yield', 13, 2)->nullable();
            $table->integer('used_contributions_limit')->nullable();
        });

        // Creación de tabla y datos para guardar el numero limite de aportes por jerarquía
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

            $table->unique(['ret_fun_procedure_id', 'hierarchy_id']); // evitar duplicados
        });

        // Creación de tabla y datos para guardar el porcentaje de rendimiento de aportes por modalidad
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

            $table->unique(['ret_fun_procedure_id', 'procedure_modality_id']); // evitar duplicados
        });

        // Nuevas tablas para guardar devoluciones de aportes
        Schema::create('ret_fun_refund_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contribution_type_id');
            $table->decimal('annual_percentage_yield', 13, 2);
            $table->timestamps();

            $table->foreign('contribution_type_id')
                ->references('id')->on('contribution_types')
                ->onDelete('restrict');
        });

        Schema::create('ret_fun_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('retirement_fund_id');
            $table->unsignedBigInteger('ret_fun_refund_type_id');
            $table->decimal('subtotal', 13, 2);
            $table->decimal('yield', 13, 2);
            $table->decimal('total', 13, 2);
            $table->timestamps();

            $table->foreign('retirement_fund_id')
                ->references('id')->on('retirement_funds')
                ->onDelete('restrict');
            $table->foreign('ret_fun_refund_type_id')
                ->references('id')->on('ret_fun_refund_types')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(true);
            $table->float('limit_average')->default(10800);
            $table->decimal('annual_yield', 13, 2)->default(5);
            $table->dropColumn('start_date');
            $table->dropColumn('contributions_limit');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->dropColumn('used_contributions_limit');
            $table->dropColumn('yield');
        });

        Schema::dropIfExists('ret_fun_procedures_hierarchies');
        Schema::dropIfExists('ret_fun_procedures_modalities');
    }
}
