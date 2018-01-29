<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
    if (!Schema::hasTable('model_has_permissions')) {
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });
    }
//        Schema::table($tableNames['roles'], function (Blueprint $table) {          
//            $table->string('guard_name')->nullable();
//        });
    if (!Schema::hasTable('model_has_permissions')) {
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->integer('permission_id')->unsigned();
            $table->morphs('model');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });
    }
    
    if (!Schema::hasTable('model_has_roles')) {
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            $table->integer('role_id')->unsigned();
            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });
    }
    
    if (!Schema::hasTable('role_has_permissions')) {
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');
//         Schema::table('roles', function (Blueprint $table) {
//            $table->dropColumn('guard_name');
//        });
//        Schema::drop($tableNames['roles']);
//        Schema::drop($tableNames['role_has_permissions']);
//        Schema::drop($tableNames['model_has_roles']);
//        Schema::drop($tableNames['model_has_permissions']);        
//        Schema::drop($tableNames['permissions']);
    }
}
