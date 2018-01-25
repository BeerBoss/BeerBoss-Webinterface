<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeerProfileDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beer_profile_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('beer_profile_id')->unsigned();
            $table->integer('amountDays')->unsigned();
            $table->double('desiredTemp')->unsigned();
            $table->timestamps();
            $table->foreign('beer_profile_id')->references('id')->on('beer_profiles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beer_profile_datas');
    }
}
