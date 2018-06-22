<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInboxState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retirement_funds', function (Blueprint $table) {
            $table->boolean('inbox_state')->default(false);
        });
        // Schema::table('contribution_types', function(Blueprint $table){
        //     $table->mediumText('description')->nullable();
        // });
        Schema::table('affiliate_folders', function(Blueprint $table){
            $table->boolean('is_paid')->nullable();
            $table->string('note')->nullable();
            //$table->string('folder_number')->nullable(); //mada directly from db
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retiremet_funds', function (Blueprint $table) {
            //
        });
    }
}
