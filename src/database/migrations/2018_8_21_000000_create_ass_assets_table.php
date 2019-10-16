<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RealEstateDoc\Asset\Helpers\Helper;

class CreateAssAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_assets')){
            Schema::create('ass_assets', function (Blueprint $table) {
                $status_enum = collect(Helper::getJsonFromStaticData('asset-status.json'))->map(function($item, $key) {
                    return $item->id;
                });
                $table->increments('id');
                $table->string('name',1000);
                $table->string('code',15);
                $table->string('size',50)->nullable();
                $table->enum('status',$status_enum->all())->default('draft');
                $table->string('slug',250);
                $table->integer('category_id')->nullable();
                $table->integer('building_id')->nullable();
                $table->integer('asset_type_id')->nullable();
                $table->string('description',1000)->nullable();
                $table->integer('cover_photo')->nullable();
                $table->integer('floor_id')->nullable();
                $table->integer('tax_id')->nullable();
                $table->integer('parent_asset_id')->nullable();
                $table->integer('floor_photo')->nullable()->comment('This photo will overwrite default floor photo');
                $table->text('floor_polygon',1000)->nullable();
                $table->integer('created_by');
//                $table->text('photos')->nullable();
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
        Schema::dropIfExists('asset_location');
    }
}
