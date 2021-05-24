<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreativeImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creative_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creative_url');
            $table->string('creative_path');
            $table->integer('creative_id')->unsigned();
            $table->foreign('creative_id')->references('id')->on('creatives');

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
        Schema::dropIfExists('creative_images');
    }
}
