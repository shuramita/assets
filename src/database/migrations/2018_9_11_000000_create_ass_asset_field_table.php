<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssAssetFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('asset.schema_prefix').'asset_field')){
            Schema::create(config('asset.schema_prefix').'asset_field', function (Blueprint $table) {
                        $table->increments('id');
                        $table->integer('asset_id');
                        $table->string('field_id',60);
                        $table->text('value');
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
        Schema::dropIfExists(config('asset.schema_prefix').'asset_field');
    }
}
