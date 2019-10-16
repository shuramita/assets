<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssFloorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_floor')){
            Schema::create('ass_floor', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name',1000);
                        $table->string('code',250);
//                         $table->integer('category_id')->nullable();
                         $table->integer('building_id');
                         $table->integer('photo_id')->nullable();
                         $table->integer('map_id')->nullable();
                         $table->text('map_data')->nullable()->comment('Incase map data can be a svg data or a poligon data');
                        $table->string('description',1000)->nullable();
                        $table->timestamps();
                    });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ass_floor');
    }
}
