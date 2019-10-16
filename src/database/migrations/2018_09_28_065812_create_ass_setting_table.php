<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ass_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key',50);
            $table->text('value')->nulable();
            $table->string('model',50);
            $table->string('group',11);
            $table->index(['key']);
            $table->string('title',250);
            $table->text('description')->nulable();
            $table->string('type',10);

            $table->integer('organization_id');
            $table->integer('order')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ass_setting');
    }
}
