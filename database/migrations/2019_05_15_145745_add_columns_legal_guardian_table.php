<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsLegalGuardianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_com_legal_guardians', function (Blueprint $table) {
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('number_authority')->nullable(); //numero de poder
            $table->string('notary_of_public_faith')->nullable(); //notaria de fe publica Nro...
            $table->string('notary')->nullable(); //notario
            $table->date('date_authority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eco_com_legal_guardians', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'number_authority',
                'notary_of_public_faith',
                'notary',
                'date_authority',
            ]);
        });
    }
}
