<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssAssetPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_asset_price')){
            Schema::create('ass_asset_price', function (Blueprint $table) {
                        $table->increments('id');
                        $table->integer('asset_id');
                        $table->integer('price_id');
                        $table->float('price',15,2);
                        $table->float('priority')->default(1)->comment('The price that have higher value will priority when calculate total price');
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
        Schema::dropIfExists('ass_asset_price');
    }
}
