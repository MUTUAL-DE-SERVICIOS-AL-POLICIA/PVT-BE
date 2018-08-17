<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressQuotaAidBenficiarieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_quota_aid_beneficiaries', function (Blueprint $table) {
            $table->bigInteger('address_id')->unsigned();
            $table->bigInteger('quota_aid_beneficiary_id')->unsigned();
            $table->foreign('quota_aid_beneficiary_id')->references('id')->on('quota_aid_beneficiaries');
            $table->foreign('address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_quota_aid_beneficiaries');
    }
}
