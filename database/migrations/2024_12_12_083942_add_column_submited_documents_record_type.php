<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSubmitedDocumentsRecordType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ret_fun_submitted_documents', function (Blueprint $table) {
            $table->boolean('is_uploaded')->default(false);
        });

        Schema::table('eco_com_submitted_documents', function (Blueprint $table) {
            $table->boolean('is_uploaded')->default(false);
        });

        Schema::table('quota_aid_submitted_documents', function (Blueprint $table) {
            $table->boolean('is_uploaded')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ret_fun_submitted_documents', function (Blueprint $table) {
            $table->dropColumn('is_uploaded');
        });

        Schema::table('eco_com_submitted_documents', function (Blueprint $table) {
            $table->dropColumn('is_uploaded');
        });

        Schema::table('quota_aid_submitted_documents', function (Blueprint $table) {
            $table->dropColumn('is_uploaded');
        });
    }
}
