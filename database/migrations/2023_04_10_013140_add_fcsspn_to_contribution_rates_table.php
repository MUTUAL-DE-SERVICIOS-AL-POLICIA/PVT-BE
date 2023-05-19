<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFcsspnToContributionRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contribution_rates', function(Blueprint $table){
            $table->decimal('fcsspn',13,3)->default(0.00);
            DB::statement("ALTER TABLE contribution_rates ALTER COLUMN mortuary_aid DROP NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contribution_rates',function(Blueprint $table){
            $table->dropColumn('fcsspn');
            DB::statement("ALTER TABLE contribution_rates ALTER COLUMN mortuary_aid SET NOT NULL;");

        });
    }
}
