<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ass_user_role', function (Blueprint $table) {

            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->boolean('is_default')->default(1);
            // we don't need organization because it already in role table
            //$table->integer('organization_id')->unsigned();
//
//            /*
//             * Add Foreign/Unique/Index
//             */
//
            $table->foreign('user_id', 'ass_foreign_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('role_id', 'ass_foreign_role')
                ->references('id')
                ->on('ass_role')
                ->onDelete('cascade');
//
//            $table->foreign('organization_id', 'inv_foreign_organization')
//                ->references('id')
//                ->on('inv_organization')
//                ->onDelete('cascade');
//
            $table->unique(['user_id', 'role_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ass_user_role', function (Blueprint $table) {
            $table->dropForeign('ass_foreign_user');
            $table->dropForeign('ass_foreign_role');
//            $table->dropForeign('inv_foreign_organization');
        });

        Schema::dropIfExists('ass_user_role');
    }
}
