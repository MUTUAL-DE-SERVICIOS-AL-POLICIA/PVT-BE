<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunRefundAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_refund_amounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ret_fun_beneficiary_id');
            $table->unsignedBigInteger('ret_fun_refund_id');
            $table->integer('percentage', false, true)->max(100);
            $table->decimal('amount', 13, 2);

            $table->foreign('ret_fun_beneficiary_id')
                ->references('id')->on('ret_fun_beneficiaries')
                ->onDelete('restrict');
            $table->foreign('ret_fun_refund_id')
                ->references('id')->on('ret_fun_refunds')
                ->onDelete('restrict');

            $table->unique(['ret_fun_beneficiary_id', 'ret_fun_refund_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ret_fun_refund_amounts');
    }
}
