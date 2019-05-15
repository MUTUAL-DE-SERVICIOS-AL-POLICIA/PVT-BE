<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcedureModalityOnEcoComModalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP VIEW v_habitual');
        DB::statement('DROP VIEW v_inclusion');
        Schema::table('eco_com_modalities', function (Blueprint $table) {
            $table->unsignedBigInteger('procedure_modality_id')->nullable();
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities')->onDelete('cascade');
        });
        if (Schema::hasColumn('eco_com_modalities', 'eco_com_type_id')) {
            Schema::table('eco_com_modalities', function (Blueprint $table) {
                $table->dropColumn('eco_com_type_id');
            });
        }
        Schema::table('eco_com_rents', function (Blueprint $table) {
            $table->unsignedBigInteger('procedure_modality_id')->nullable();
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eco_com_rents', function (Blueprint $table) {
            $table->dropColumn(['procedure_modality_id']);
        });
        Schema::table('eco_com_modalities', function (Blueprint $table) {
            $table->dropColumn(['procedure_modality_id']);
        });
        DB::statement('CREATE OR REPLACE VIEW v_habitual as SELECT * FROM users');
        DB::statement('CREATE OR REPLACE VIEW v_inclusion as SELECT * FROM users');

    }
}
