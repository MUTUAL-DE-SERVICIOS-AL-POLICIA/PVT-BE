<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetFunRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('retirement_fund_id');
            $table->unsignedBigInteger('ret_fun_refund_type_id');
            $table->decimal('subtotal', 13, 2);
            $table->decimal('yield', 13, 2);
            $table->decimal('total', 13, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('retirement_fund_id')
                ->references('id')->on('retirement_funds')
                ->onDelete('restrict');
            $table->foreign('ret_fun_refund_type_id')
                ->references('id')->on('ret_fun_refund_types')
                ->onDelete('restrict');

            $table->unique(['retirement_fund_id', 'ret_fun_refund_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ret_fun_refunds');
    }
}
