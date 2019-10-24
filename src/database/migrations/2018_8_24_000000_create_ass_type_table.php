<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('asset.schema_prefix').'type')){
            Schema::create(config('asset.schema_prefix').'type', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name',1000);
                        $table->string('system_id',50);
                        $table->string('slug',250);
                         $table->integer('category_id')->nullable();
                        $table->string('description',1000)->nullable();
                        $table->boolean('enabled')->default(true);
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
        Schema::dropIfExists(config('asset.schema_prefix').'type');
    }
}
