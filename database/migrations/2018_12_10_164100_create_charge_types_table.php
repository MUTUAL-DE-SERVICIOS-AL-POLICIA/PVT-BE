<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('module_id');            
            $table->foreign('module_id')->references('id')->on('modules');
            $table->string('name');
            $table->decimal('amount', 13, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charge_types');
    }
}
