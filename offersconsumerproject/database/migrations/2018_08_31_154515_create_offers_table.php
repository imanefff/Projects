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
            $table->increments('id');
            $table->integer('sid');
            $table->string('name')->nullable();
            $table->string('offer_url')->nullable();
            $table->string('preview_url')->nullable();
            $table->string('unsubscribe_url')->nullable();
            $table->string('landing_page_url')->nullable();
            $table->string('categories')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->string('default_payout')->nullable();
            $table->text('from_lines')->nullable();
            $table->text('subject_lines')->nullable();
            $table->tinyInteger('level')->nullable();
            $table->string('countries')->nullable();
            $table->string('suppression_link')->nullable();
            $table->string('suppression_link_2')->nullable();
            $table->integer('entity_id')->nullable();
            $table->tinyInteger('is_mobile')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->decimal('amount', 9, 2)->nullable();
            $table->tinyInteger('has_supp')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('offers');
    }
}
