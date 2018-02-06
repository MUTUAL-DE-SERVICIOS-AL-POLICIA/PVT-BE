<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BeneficiaryStatusTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //EN ESPERA.. FALTA TERMINAR
//        DB::unprepared('
//            CREATE OR REPLACE FUNCTION fill_type()
//            RETURN TRIGGER AS
//            $$
//            BEGIN
//                CASE WHEN (SELECT count(*) FROM ret_fun_beneficiaries where ) )
//            END;
//            $$            
//
//            CREATE TRIGGER add_status_beneficiary   
//            AFTER INSERT on ret_fun_beneficiaries
//            FOR EACH ROW EXECUTE PROCEDURE fill_type();
//        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
