<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('shortened')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
        Schema::create('retirement_fund_tag', function (Blueprint $table) {
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();
            $table->unique(['retirement_fund_id','tag_id']);
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
        Schema::create('tag_wf_state', function (Blueprint $table) {
            $table->bigInteger('wf_state_id')->unsigned();
            $table->bigInteger('tag_id')->unsigned();
            $table->unique(['wf_state_id', 'tag_id']);
            $table->foreign('wf_state_id')->references('id')->on('wf_states');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_wf_state');
        Schema::dropIfExists('retirement_fund_tag');
        Schema::dropIfExists('tags');
    }
}
