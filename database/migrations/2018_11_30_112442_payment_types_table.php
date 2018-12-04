<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function(Blueprint $table) {
            $table->bigIncrements('id');            
            $table->string('name');            
            $table->timestamps();
        });
        Schema::table('vouchers', function ($table) {
            $table->bigInteger('payment_type_id')->unsigned()->nullable();            
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('payment_type_id');
        });
        Schema::dropIfExists('payment_types');
    }
}
