<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdatedPensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_com_updated_pensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('economic_complement_id');
            $table->enum('rent_type', ['Manual', 'Automatico']);
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
            $table->foreign('economic_complement_id')->references('id')->on('economic_complements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eco_com_updated_pensions');
    }
}
