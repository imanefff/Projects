<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCacheOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sid');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('payout')->nullable();
            $table->string('unit')->nullable();
            $table->string('daysleft')->nullable();
            $table->string('category')->nullable();
            $table->string('geotargeting')->nullable();
            $table->string('api_type')->nullable();
            $table->boolean('is_added')->default(false);
            $table->timestamps();

            $table->integer('advertiser_id')->unsigned();
            $table->foreign('advertiser_id')->references('id')->on('advertisers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cache_offers');
    }
}
