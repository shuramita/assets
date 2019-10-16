<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('ass_payment')){
            Schema::create('ass_payment', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('transaction_id',100);
                        $table->string('credit_card',16);
                        $table->string('name',250);
                        $table->string('address',250);
                        $table->integer('token')->nullable();
                        $table->double('amount',14,2)->nullable();
                        $table->integer('customer_id')->nullable();
                        $table->integer('booking_id')->nullable();
                        $table->enum('status',['draft','reject','pending','approved'])->default('pending');
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
        Schema::dropIfExists('ass_payment');
    }
}
