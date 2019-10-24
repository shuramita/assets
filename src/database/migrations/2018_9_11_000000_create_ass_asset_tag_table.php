<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssAssetTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('asset.schema_prefix').'asset_tag')){
            Schema::create(config('asset.schema_prefix').'asset_tag', function (Blueprint $table) {
                        $table->increments('id');
                         $table->integer('asset_id');
                         $table->integer('tag_id');
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
        Schema::dropIfExists(config('asset.schema_prefix').'asset_tag');
    }
}
