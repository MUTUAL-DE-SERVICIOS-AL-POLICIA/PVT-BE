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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wf_records', function (Blueprint $table) {
            $table->dropColumn('quota_aid_id');
        });
        Schema::dropIfExists('quota_aid_mortuary_tag');
        Schema::table('quota_aid_mortuaries', function (Blueprint $table) {
            $table->dropColumn('inbox_state');
        });
    }
}
