<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssMediablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ass_mediables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id');
            $table->integer('mediable_id');
            $table->enum('mediable_type',['asset_photos','building_photo','venue_photo']);
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ass_mediables');
    }
}
