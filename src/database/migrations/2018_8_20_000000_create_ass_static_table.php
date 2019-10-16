<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssStaticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_static_data')){
            Schema::create('ass_static_data', function (Blueprint $table) {
                        $table->increments('id');
                        $table->integer('static_id');
                        $table->string('name',1000);
                        $table->string('slug',250);
                        $table->string('system_id',50);
                        $table->integer('group_id');
                        $table->string('model',50);
                        $table->string('group_name',250)->comment('This is duplicate string, but who care, storage is cheap :)');
                        $table->string('description',1000)->nullable();
                        $table->boolean('is_published')->default(true);
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
        Schema::dropIfExists('ass_static_data');
    }
}
