<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnReimbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aid_reimbursements', function (Blueprint $table) {
            $table->decimal('mortuary_aid', 13, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aid_reimbursements', function (Blueprint $table) {
            $table->dropColumn('mortuary_aid');
        });
    }
}
