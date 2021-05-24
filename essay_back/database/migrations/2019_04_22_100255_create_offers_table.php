<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sid');
            $table->string('name');
            $table->string('offer_url');
            $table->string('landing_page_url');
            $table->string('description');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('amount');
            $table->boolean('is_mobile')->default(0);
            $table->boolean('is_active');



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
        Schema::dropIfExists('offers');
    }
}
