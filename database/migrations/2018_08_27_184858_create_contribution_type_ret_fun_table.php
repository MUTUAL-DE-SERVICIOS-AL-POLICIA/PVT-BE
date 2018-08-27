<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionTypeRetFunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribution_type_retirement_fund', function (Blueprint $table) {
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('contribution_type_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->text('message')->nullable();
            $table->dateTime('date');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('contribution_type_id')->references('id')->on('contribution_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contribution_type_retirement_fund');
    }
}
