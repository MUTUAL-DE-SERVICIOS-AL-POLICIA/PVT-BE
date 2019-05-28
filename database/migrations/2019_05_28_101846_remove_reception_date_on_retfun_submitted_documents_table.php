<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveReceptionDateOnRetfunSubmittedDocumentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('ret_fun_submitted_documents', function (Blueprint $table) {
      $table->dropColumn('reception_date');
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
      $table->date('reception_date')->nullable();
    });
  }
}
