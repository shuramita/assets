<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_organization')){
            Schema::create('ass_organization', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name',1000);
                        $table->string('slug',250);
                        $table->string('location',500);
                        $table->string('currency',10);
                        $table->string('language',25);
                        $table->string('timezone',50);
                        $table->string('industry',250)->nullable();
                        $table->string('client_portal_url',250);
                        $table->integer('logo')->nullable();

                        // Organization address
                        $table->string('address',500)->nullable();
                        $table->string('street2',225)->nullable();
                        $table->string('city',50)->nullable();
                        $table->string('state',50)->nullable();
                        $table->string('country',50)->nullable();
                        $table->string('code',15)->nullable();
                        $table->string('phone',50)->nullable();
                        $table->string('fax',50)->nullable();
                        
//                        $table->integer('invoice_template_id')->nullable();
                        $table->integer('category_id')->nullable();
                        $table->string('description',1000)->nullable();

                        $table->text('extra_fields')->nullable();
                        $table->integer('created_by');

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
        Schema::dropIfExists('ass_organization');
    }
}
