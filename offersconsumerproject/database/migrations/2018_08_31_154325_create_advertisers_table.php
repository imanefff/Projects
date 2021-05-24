<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('adv_id');
            $table->string('alias');
            $table->string('api_key');
            $table->string('url');
            $table->string('url_to_add')->default("");
            $table->string('api_type')->default("hit");
            $table->string('status');
            $table->timestamps();

            $table->integer('entity_id')->unsigned();
            $table->foreign('entity_id')->references('id')->on('entities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisers');
    }
}
