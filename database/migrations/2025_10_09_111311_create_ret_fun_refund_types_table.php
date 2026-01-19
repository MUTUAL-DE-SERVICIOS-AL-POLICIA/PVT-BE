<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunRefundTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_refund_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contribution_type_id');
            $table->decimal('annual_percentage_yield', 13, 2);
            $table->timestamps();

            $table->foreign('contribution_type_id')
                ->references('id')->on('contribution_types')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ret_fun_refund_types');
    }
}
