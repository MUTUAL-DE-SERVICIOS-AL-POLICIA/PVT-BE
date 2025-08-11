<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Models\Hierarchy;
use Muserpol\Models\ProcedureModality;

class RetFunContributionsLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modificación a tablas existentes
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            $table->date('start_date')->default(now());
            $table->integer('contributions_limit')->default(0);
            $table->dropColumn('is_enabled');
            $table->dropColumn('limit_average');
            $table->dropColumn('annual_yield');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->integer('used_contributions_limit')->nullable();
            $table->decimal('annual_yield', 8, 2)->default(0);
        });

        // Creación de tabla y datos para guardar el numero limite de aportes por jerarquía
        Schema::create('ret_fun_procedures_hierarchies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ret_fun_procedure_id');
            $table->unsignedBigInteger('hierarchy_id');
            $table->boolean('apply_contributions_limit')->default(true);
            $table->decimal('average_salary_limit', 8, 2)->default(10800);
            $table->timestamps();

            $table->foreign('ret_fun_procedure_id')
                ->references('id')->on('ret_fun_procedures')
                ->onDelete('restrict');
            $table->foreign('hierarchy_id')
                ->references('id')->on('hierarchies')
                ->onDelete('restrict');

            $table->unique(['ret_fun_procedure_id', 'hierarchy_id']); // evitar duplicados
        });

        $hierarchies = Hierarchy::orderBy('id')->get();
        $hierarchiesSyncData = [];

        foreach ($hierarchies as $hierarchy) {
            $hierarchiesSyncData[$hierarchy->id] = ['apply_contributions_limit' => false];
        }
        $procedure = RetFunProcedure::find(1);
        $procedure->hierarchies()->sync($hierarchiesSyncData);

        // Creación de tabla y datos para guardar el porcentaje de rendimiento de aportes por modalidad
        Schema::create('ret_fun_procedures_modalities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ret_fun_procedure_id');
            $table->unsignedBigInteger('procedure_modality_id');
            $table->decimal('annual_percentage_yield', 3, 2);
            $table->timestamps();

            $table->foreign('ret_fun_procedure_id')
                ->references('id')->on('ret_fun_procedures')
                ->onDelete('restrict');
            $table->foreign('procedure_modality_id')
                ->references('id')->on('procedure_modalities')
                ->onDelete('restrict');

            $table->unique(['ret_fun_procedure_id', 'procedure_modality_id']); // evitar duplicados
        });

        $PG_PROCEDURE = 1; // Procedure type - Pago global de aportes
        $DA_PROCEDURE = 21; // Procedure type - Devolución de aportes

        $modalities = ProcedureModality::whereIn('procedure_type_id', [$PG_PROCEDURE, $DA_PROCEDURE])->get();
        $modalitiesSyncData = [];

        foreach ($modalities as $modality) {
            if($modality->procedure_type_id == $PG_PROCEDURE) {
                $modalitiesSyncData[$modality->id] = ['annual_percentage_yield' => 5];
                continue;
            }
            if($modality->procedure_type_id == $DA_PROCEDURE) {
                $modalitiesSyncData[$modality->id] = ['annual_percentage_yield' => 0];
                continue;
            }
        }

        $procedure->procedure_modalities()->sync($modalitiesSyncData);

        // Actualización de tipos de aportes
        $newValues = [
            ['name' => 'Periodo posterior a 30 años de servicios activo', 'operator' => '-', 'shortened' => '+30 años'],
            ['name' => 'Disponibilidad por Enfermedad con aporte', 'operator' => '+', 'shortened' => 'Disponibilidad Enfermedad Con Aporte'],
            ['name' => 'Disponibilidad por Enfermedad sin aporte', 'operator' => '-', 'shortened' => 'Disponibilidad Enfermedad Sin Aporte'],
        ];

        foreach ($newValues as $value) {
            ContributionType::create($value);
        }

        ContributionType::where('name', 'Disponibilidad')->delete();
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
            $table->decimal('annual_yield',13,2)->default(5);
            $table->dropColumn('start_date');
            $table->dropColumn('contributions_limit');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->dropColumn('used_contributions_limit');
            $table->dropColumn('annual_yield');
        });

        Schema::dropIfExists('ret_fun_procedures_hierarchies');
        Schema::dropIfExists('ret_fun_procedures_modalities');

        ContributionType::where('name', 'Periodo posterior a 30 años de servicios activo')->forceDelete();
        ContributionType::where('name', 'Disponibilidad por Enfermedad con aporte')->forceDelete();
        ContributionType::where('name', 'Disponibilidad por Enfermedad sin aporte')->forceDelete();

        ContributionType::withTrashed()->where('name', 'Disponibilidad')->restore();
    }
}
