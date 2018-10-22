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
        Schema::table('quota_aid_procedures', function (Blueprint $table) {
            $table->integer('months')->nullable();
            $table->boolean('is_enabled')->default(true);
        });
        Schema::create('discount_type_quota_aid_mortuary', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('discount_type_id')->unsigned()->nullable();
            $table->bigInteger('quota_aid_mortuary_id')->unsigned()->nullable();
            $table->unique(['discount_type_id', 'quota_aid_mortuary_id']);
            $table->decimal('amount', 13, 2)->nullable();
            $table->date('date')->nullable();
            $table->string('code')->nullable();
            $table->string('note_code')->nullable();
            $table->date('note_code_date')->nullable();
            $table->foreign('discount_type_id')->references('id')->on('discount_types')->onDelete('cascade');
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::table('info_loans', function (Blueprint $table) {
            $table->bigInteger('quota_aid_mortuary_id')->unsigned()->nullable();
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade');
        });
        Schema::table('quota_aid_correlatives', function (Blueprint $table) {
            $table->bigInteger('procedure_type_id')->unsigned()->nullable();
            $table->foreign('procedure_type_id')->references('id')->on('procedure_types');
        });
        Schema::create('contribution_voucher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contribution_id')->unsigned();
            $table->bigInteger('voucher_id')->unsigned();
            $table->foreign('contribution_id')->references('id')->on('contributions');
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->timestamps();
        });
        Schema::create('aid_contribution_voucher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('aid_contribution_id')->unsigned();
            $table->bigInteger('voucher_id')->unsigned();
            $table->foreign('aid_contribution_id')->references('id')->on('aid_contributions');
            $table->foreign('voucher_id')->references('id')->on('vouchers');
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
        Schema::dropIfExists('aid_contribution_voucher');
        Schema::dropIfExists('contribution_voucher');
        Schema::table('quota_aid_correlatives', function (Blueprint $table) {
            $table->dropColumn('procedure_type_id');
        });
        Schema::table('info_loans', function (Blueprint $table) {
            $table->dropColumn('quota_aid_mortuary_id');
        });
        Schema::dropIfExists('discount_type_quota_aid_mortuary');
        Schema::table('quota_aid_procedures', function (Blueprint $table) {
            $table->dropColumn('months');
            $table->dropColumn('is_enabled');
        });
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
