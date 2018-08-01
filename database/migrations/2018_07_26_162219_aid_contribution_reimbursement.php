<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AidContributionReimbursement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('aid_reimbursements', function(Blueprint $table) 
        // {
        //     $table->bigIncrements('id');
        //     $table->bigInteger('affiliate_id')->unsigned()->nullable();
        //     $table->bigInteger('user_id')->unsigned();
        //     $table->string('month_year');
        //     $table->decimal('quotable', 13, 2);
        //     $table->decimal('rent', 13, 2);
        //     $table->decimal('dignity_rent', 13, 2);
        //     $table->decimal('interest', 13, 2);
        //     $table->decimal('total', 13, 2);            
        //     $table->foreign('affiliate_id')->references('id')->on('affiliates');
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {    
        // Schema::dropIfExists('aid_reimbursements');
    }
}
