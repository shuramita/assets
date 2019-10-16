<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ass_role', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name',60);
            $table->text('description');
            $table->smallInteger('weight')->default(0)->unsigned();

            $table->unique(['name','organization_id']);
            $table->integer('organization_id');
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
        Schema::dropIfExists('ass_role');
    }
}
