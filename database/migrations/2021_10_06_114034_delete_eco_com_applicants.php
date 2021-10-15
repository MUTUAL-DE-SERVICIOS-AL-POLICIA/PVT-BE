<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteEcoComApplicants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_com_applicants', function (Blueprint $table) {
            $table->dropColumn(['official']);
            $table->dropColumn(['book']);
            $table->dropColumn(['departure']);
            $table->dropColumn(['marriage_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eco_com_applicants', function (Blueprint $table) {
            $table->string('official',350)->nullable();
            $table->string('book',350)->nullable();
            $table->string('departure',350)->nullable();
            $table->date('marriage_date')->nullable();
        });
    }
}
