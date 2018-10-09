<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedTablesQuotaAid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->boolean('inbox_state')->default(false);
        });
        Schema::create('quota_aid_mortuary_tag', function (Blueprint $table) {
            $table->bigInteger('quota_aid_mortuary_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->unique(['quota_aid_mortuary_id', 'tag_id']);
            $table->dateTime('date');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
        Schema::table('wf_records', function (Blueprint $table) {
            $table->bigInteger('quota_aid_id')->nullable();
        });
        Schema::create('quota_aid_beneficiary_testimony', function (Blueprint $table) {
            $table->bigInteger('quota_aid_beneficiary_id')->unsigned();
            $table->bigInteger('testimony_id')->unsigned();
            $table->foreign('quota_aid_beneficiary_id')->references('id')->on('quota_aid_beneficiaries')->onDelete('cascade');
            $table->foreign('testimony_id')->references('id')->on('testimonies')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::table('quota_aid_beneficiaries', function (Blueprint $table) {
            $table->boolean('state')->default(false);
        });
        Schema::table('quota_aid_legal_guardians', function (Blueprint $table) {
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('date_authority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_aid_legal_guardians', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('date_authority');
        });
        Schema::table('quota_aid_beneficiaries', function (Blueprint $table) {
            $table->dropColumn('state');
        });
        Schema::dropIfExists('quota_aid_beneficiary_testimony');
        Schema::table('wf_records', function (Blueprint $table) {
            $table->dropColumn('quota_aid_id');
        });
        Schema::dropIfExists('quota_aid_mortuary_tag');
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->dropColumn('inbox_state');
        });
    }
}
