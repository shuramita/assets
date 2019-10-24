<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('asset.schema_prefix').'customer')){
            Schema::create(config('asset.schema_prefix').'customer', function (Blueprint $table) {
                $table->increments('id');
                $table->string('first_name',1000);
                $table->string('last_name',1000);
                $table->string('phone_number',30);
                $table->string('email',100);
                $table->string('company',100)->nullable();
                $table->string('trade',100)->nullable();
                $table->string('acra_reg_no',1000)->nullable();
                $table->string('type_of_merchandise',1000)->nullable();
                $table->string('title',15)->nullable();
                $table->string('street',300)->nullable();
                $table->integer('country')->nullable();
                $table->string('unit_number',10)->nullable();
                $table->string('house_number',10)->nullable();
                $table->string('postal_code',10)->nullable();
                $table->string('credit_card_number',16)->nullable()->comment('should be seperated in production');
                $table->string('card_holder',50);
                $table->string('expired_date',5);
                $table->string('cvv',3);
                $table->string('card_type',15)->nullable();
                $table->integer('user_id')->nullable();

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
        Schema::dropIfExists(config('asset.schema_prefix').'customer');
    }
}
