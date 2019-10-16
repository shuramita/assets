<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssBuildingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_building')){
            Schema::create('ass_building', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name',1000);
                $table->string('slug',250);
                $table->integer('logo_id')->nullable();
                $table->integer('category_id')->nullable();
                $table->string('building_type',10)->default('mall')->nullable()->comment('Current Support Mall or Co-working Space or Theater');
                $table->string('address',1000)->nullable();
                $table->string('phone_country',5)->default('+65');
                $table->string('phone_number',20)->nullable();
                $table->string('email',150)->nullable();
                $table->string('website',50)->nullable();
                $table->text('description',1000)->nullable();
                $table->integer('background_photo')->nullable();
//                $table->text('photos')->nullable(); // handled by mediable 
                $table->integer('organization_id');

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
        Schema::dropIfExists('ass_building');
    }
}
