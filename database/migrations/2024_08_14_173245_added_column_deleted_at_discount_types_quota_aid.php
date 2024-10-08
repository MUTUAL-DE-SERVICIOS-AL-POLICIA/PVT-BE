<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedColumnDeletedAtDiscountTypesQuotaAid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discount_type_quota_aid_mortuary', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('discount_type_quota_aid_mortuary', function (Blueprint $table) {
            $table->dropUnique('discount_type_quota_aid_mortuary_discount_type_id_quota_aid_mor');
        });
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
