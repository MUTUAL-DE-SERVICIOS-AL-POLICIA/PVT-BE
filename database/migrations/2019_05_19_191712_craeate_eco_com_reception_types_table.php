<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CraeateEcoComReceptionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_com_reception_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('economic_complements', function (Blueprint $table) {
            $table->unsignedBigInteger('eco_com_reception_type_id')->nullable();
            $table->foreign('eco_com_reception_type_id')->references('id')->on('eco_com_reception_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('economic_complements', function (Blueprint $table) {
            $table->dropColumn(['eco_com_reception_type_id']);
        });
        Schema::dropIfExists('eco_com_reception_types');
    }
}
