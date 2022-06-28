<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributionTypeQuotaAidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribution_type_quota_aids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('shortened')->nullable()->default(null);
            $table->longText('description')->nullable()->default(null);
            $table->string('operator');
            $table->string('display_name')->nullable();
            $table->integer('sequence');
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
        Schema::dropIfExists('contribution_type_quota_aids');
    }
}
