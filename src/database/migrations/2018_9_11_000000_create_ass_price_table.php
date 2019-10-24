<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Shura\Asset\Helpers\Helper;
class CreateAssPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $price_option = Helper::getJsonFromStaticData('price_option.json');
        if(!Schema::hasTable(config('asset.schema_prefix').'price')){
            Schema::create(config('asset.schema_prefix').'price', function (Blueprint $table) use($price_option) {
                        $table->increments('id');
                        $table->string('name',1000);
                        $table->enum('type',$price_option->type)->comment('Chars specify price type, peak, off peak or whatever');
                        $table->enum('unit',$price_option->unit);
                        $table->unsignedInteger('minimum_by_unit')->default(0)->nullable();
                        $table->enum('available_at',$price_option->available_at)->default('all')->nullable();
                        $table->text('range')->nullable()->comment('[] or [2,3,4] or [14/7-25/4, 22/12, 25/16] or [is801 string,...]');
                        $table->string('description',1000)->nullable();
                        $table->unsignedInteger('priority')->default(1);
                        $table->integer('organization_id')->comment('the price definition are belong to organization');
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
        Schema::dropIfExists(config('asset.schema_prefix').'price');
    }
}
