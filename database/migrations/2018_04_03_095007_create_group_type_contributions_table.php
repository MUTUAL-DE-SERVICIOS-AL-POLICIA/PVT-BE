<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTypeContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_type_contributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');            
            $table->timestamps();
        });
        Schema::table('contribution_types', function (Blueprint $table) {
            $table->bigInteger('group_type_contribution_id')->nullable();
            $table->foreign('group_type_contribution_id')->references('id')->on('group_type_contributions');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_type_contributions');
    }
}
