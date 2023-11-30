<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnIsPaidSpouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('economic_complements', function (Blueprint $table) {
            $table->renameColumn('is_paid_spouse', 'is_paid');
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
            $table->renameColumn('is_paid', 'is_paid_spouse');
        });
    }
}
