<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferencialLimitToEcoComRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_com_rents', function (Blueprint $table) {
            $table->decimal('referencial_limit', 15, 2)->nullable();
            $table->decimal('minor', 15, 2)->nullable()->change();
            $table->decimal('higher', 15, 2)->nullable()->change();
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
            $table->dropColumn('referencial_limit');
            $table->decimal('minor', 15, 2)->nullable(false)->change();
            $table->decimal('higher', 15, 2)->nullable(false)->change();
        });
    }
}