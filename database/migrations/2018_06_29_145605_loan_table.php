<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_loans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->bigInteger('affiliate_id')->unsigned();
            $table->bigInteger('affiliate_guarantor_id')->unsigned();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->string('code');
            $table->date('date');
            $table->decimal('amount');
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('affiliate_guarantor_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');            
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('info_loans');
    }
}
