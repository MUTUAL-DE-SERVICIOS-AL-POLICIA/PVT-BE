<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Muserpol\Models\Contribution\ContributionType;

class RetFunContributionsLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ret_fun_procedures', function (Blueprint $table) {
            $table->date('start_date')->default(now());
            $table->integer('contributions_limit')->default(0);
            $table->dropColumn('is_enabled');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->integer('used_contributions_limit')->nullable();
        });

        Schema::create('ret_fun_procedures_hierarchies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ret_fun_procedure_id');
            $table->unsignedBigInteger('hierarchy_id');
            $table->boolean('apply_limit')->default(true);
            $table->timestamps();

            $table->foreign('ret_fun_procedure_id')
                ->references('id')->on('ret_fun_procedures')
                ->onDelete('cascade');
            $table->foreign('hierarchy_id')
                ->references('id')->on('hierarchies')
                ->onDelete('cascade');

            $table->unique(['ret_fun_procedure_id', 'hierarchy_id']); // evitar duplicados
        });

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
            $table->dropColumn('start_date');
            $table->dropColumn('contributions_limit');
        });

        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->dropColumn('used_contributions_limit');
        });

        Schema::dropIfExists('ret_fun_procedures_hierarchies');

        ContributionType::where('name', 'Periodo posterior a 30 años de servicios activo')->delete();
        ContributionType::where('name', 'Disponibilidad por Enfermedad con aporte')->delete();
        ContributionType::where('name', 'Disponibilidad por Enfermedad sin aporte')->delete();

        ContributionType::withTrashed()->where('name', 'Disponibilidad')->restore();
    }
}
