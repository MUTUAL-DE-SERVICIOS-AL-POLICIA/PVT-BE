<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->string('api_token')->unique()->nullable()->default(null);
            $table->string('firebase_token')->unique()->nullable()->default(null);
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
        Schema::dropIfExists('affiliate_tokens');
    }
}
