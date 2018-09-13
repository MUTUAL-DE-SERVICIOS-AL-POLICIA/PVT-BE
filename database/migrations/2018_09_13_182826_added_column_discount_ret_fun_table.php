<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnDiscountRetFunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discount_type_retirement_fund', function (Blueprint $table) {
            $table->date('note_code_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discount_type_retirement_fund', function (Blueprint $table) {
            $table->dropColumn(['note_code_date']);
        });
    }
}
