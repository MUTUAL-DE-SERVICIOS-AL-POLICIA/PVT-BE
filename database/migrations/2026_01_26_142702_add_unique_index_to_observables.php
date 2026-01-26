<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexToObservables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE UNIQUE INDEX observables_unique_active
            ON observables (
                user_id,
                observation_type_id,
                observable_id,
                observable_type,
                message,
                date,
                enabled
            )
            WHERE deleted_at IS NULL
        ");
    }

    public function down()
    {
        DB::statement("
            DROP INDEX IF EXISTS observables_unique_active
        ");
    }
}
