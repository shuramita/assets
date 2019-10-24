<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('asset.schema_prefix').'permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('organization_id');

            $table->string('model',50);
            $table->boolean('view');
            $table->boolean('create');
            $table->boolean('edit');
            $table->boolean('delete');
            $table->boolean('approve');
            $table->timestamps();

            $table->unique(['role_id','organization_id','model']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('asset.schema_prefix').'permission');
    }
}
