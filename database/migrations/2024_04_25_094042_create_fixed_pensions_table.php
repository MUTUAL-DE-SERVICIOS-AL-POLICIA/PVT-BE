<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixedPensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_pensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('affiliate_id');
            $table->bigInteger('regulation_id');
            $table->bigInteger('eco_com_procedure_id');
            $table->enum('rent_type', ['Manual', 'Replica']);
            $table->decimal('aps_total_cc', 13, 2)->nullable();
            $table->decimal('aps_total_fsa', 13, 2)->nullable();
            $table->decimal('aps_total_fs', 13, 2)->nullable();
            $table->decimal('aps_disability', 13, 2)->nullable();
            $table->decimal('aps_total_death', 13, 2)->nullable();
            $table->decimal('sub_total_rent', 13, 2)->nullable();
            $table->decimal('reimbursement', 13, 2)->nullable();
            $table->decimal('dignity_pension', 13, 2)->nullable();
            $table->decimal('total_rent', 13, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('regulation_id')->references('id')->on('regulations');
            $table->foreign('eco_com_procedure_id')->references('id')->on('eco_com_procedures');

            $table->unique(['affiliate_id', 'regulation_id', 'eco_com_procedure_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_pensions');
    }
}
