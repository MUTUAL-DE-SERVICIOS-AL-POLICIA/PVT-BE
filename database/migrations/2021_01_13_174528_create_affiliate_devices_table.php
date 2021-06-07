<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_devices', function (Blueprint $table) {
            $table->bigInteger('affiliate_id');
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->string('device_id')->unique()->nullable();
            $table->boolean('enrolled')->default(false);
            $table->json('liveness_actions')->nullable()->default(null);
            $table->boolean('verified')->default(false);
            $table->bigInteger('eco_com_procedure_id')->nullable()->default(null);
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('eco_com_procedure_id')->references('id')->on('eco_com_procedures')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_devices');
    }
}
