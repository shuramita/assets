<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('asset.schema_prefix').'booking')){
            Schema::create(config('asset.schema_prefix').'booking', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name',1000)->nullable();
                        $table->string('slug',250)->nullable();
                        $table->integer('category_id')->nullable();
                        $table->string('description',1000)->nullable();
                        $table->integer('booked_by')->nullable();
                        $table->integer('customer_id');
                        $table->date('start_date')->nullable();
                        $table->date('end_date')->nullable();
                        $table->text('describe_business')->nullable();
                        $table->text('product_photos')->nullable();
                        $table->integer('asset_id')->nullable();
                        $table->integer('approved_by')->nullable();
                        $table->enum('status',['draft','rejected','pending','approved','paid'])->default('draft');
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
        Schema::dropIfExists(config('asset.schema_prefix').'booking');
    }
}
