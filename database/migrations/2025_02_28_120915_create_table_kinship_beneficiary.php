<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateTableKinshipBeneficiary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinship_beneficiaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('kinship_beneficiaries')->insert([
            ['name' => 'Tutor legal', 'created_at' => Carbon::now()],
            ['name' => 'Madre del menor', 'created_at' => Carbon::now()],
            ['name' => 'Padre del menor', 'created_at' => Carbon::now()]
        ]);

        Schema::table('ret_fun_advisor_beneficiary', function (Blueprint $table) {
            $table->unsignedBigInteger('kinship_beneficiary_id')->nullable();
            $table->foreign('kinship_beneficiary_id')->references('id')->on('kinship_beneficiaries');
        });

        Schema::table('quota_aid_advisor_beneficiary', function (Blueprint $table) {
            $table->unsignedBigInteger('kinship_beneficiary_id')->nullable();;
            $table->foreign('kinship_beneficiary_id')->references('id')->on('kinship_beneficiaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ret_fun_advisor_beneficiary', function (Blueprint $table) {
            $table->dropForeign(['kinship_beneficiary_id']);
            $table->dropColumn('kinship_beneficiary_id');
        });

        Schema::table('quota_aid_advisor_beneficiary', function (Blueprint $table) {
            $table->dropForeign(['kinship_beneficiary_id']);
            $table->dropColumn('kinship_beneficiary_id');
        });

        Schema::dropIfExists('kinship_beneficiaries');
    }
}
