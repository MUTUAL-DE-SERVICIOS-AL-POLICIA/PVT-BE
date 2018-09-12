<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnsRetFunLegalGuardians extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ret_fun_legal_guardians', function (Blueprint $table) {
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('date_authority')->nullable();
        });
        Schema::table('ret_fun_advisors', function (Blueprint $table) {
            $table->enum('gender', ['M', 'F'])->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['M', 'F'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ret_fun_legal_guardians', function (Blueprint $table) {
            $table->dropColumn(['gender', 'date_authority']);
        });
        Schema::table('ret_fun_advisors', function (Blueprint $table) {
            $table->dropColumn(['gender']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender']);
        });
    }
}
