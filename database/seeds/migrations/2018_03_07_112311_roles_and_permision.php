<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolesAndPermision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('operations', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('module_id')->unsigned()->nullable();
            $table->string('name');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->timestamps();
        });
        Schema::create('actions', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('permissions', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('operation_id')->unsigned()->nullable();
            $table->bigInteger('action_id')->unsigned()->nullable();
            $table->foreign('operation_id')->references('id')->on('operations');
            $table->foreign('action_id')->references('id')->on('actions');
            $table->timestamps();
        });
        Schema::create('role_permissions', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->bigInteger('permission_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
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
        Schema::drop('role_permissions');
        Schema::drop('permissions');
        Schema::drop('actions');
        Schema::drop('operations');
    }
}
