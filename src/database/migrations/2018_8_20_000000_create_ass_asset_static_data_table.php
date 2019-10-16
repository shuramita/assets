<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssAssetStaticDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_asset_static_data')){
            Schema::create('ass_asset_static_data', function (Blueprint $table)  {
                $table->increments('id');
                $table->integer('asset_id');
                $table->integer('static_id');
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
        Schema::dropIfExists('ass_asset_static_data');
    }
}
