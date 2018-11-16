<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedure_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->unsignedBigInteger('procedure_state_id')->nullable();
            $table->foreign('procedure_state_id')->references('id')->on('procedure_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->dropColumn('procedure_state_id');
        });
        Schema::dropIfExists('procedure_states');
    }
}
