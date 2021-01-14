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
            $table->string('device_id')->unique();
            $table->boolean('enrolled')->default(false);
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
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
