<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use RealEstateDoc\Asset\Helpers\Helper;

class CreateVenVenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return;
        $venueStatuses = Helper::collect('venue-status.json');
        if(!Schema::hasTable('ven_venue')){
            Schema::create('ven_venue', function (Blueprint $table) use($venueStatuses) {

                $table->increments('id');
                $table->string('name',1000);
                $table->text('description')->nullable();

                $table->enum('status', $venueStatuses->map(function($status){
                    return $status->id;
                })->toArray());

                $table->string('slug',250);
                $table->integer('logo_id')->nullable();
                $table->integer('category_id')->nullable();
                $table->integer('building_id')->nullable()->comment('a venue can belong to a building or not');
                $table->string('size',50)->nullable();

                $table->string('address',1000)->nullable();
                $table->string('unit_numnber',20)->nullable();
                $table->string('house_numner',20)->nullable();
                $table->string('block',20)->nullable();
                $table->string('street',100)->nullable();
                $table->string('city',50)->nullable();
                $table->string('lat',25)->nullable();
                $table->string('lng',25)->nullable();

                $table->string('phone_country',5)->default('+65');
                $table->string('phone_number',20)->nullable();
                $table->string('email',150)->nullable();
                $table->string('website',50)->nullable();
                $table->integer('background_photo')->nullable();
                $table->integer('organization_id');

                $table->integer('min_standing');
                $table->integer('min_siting');
                $table->integer('max_standing');
                $table->integer('max_siting');

                $table->boolean('insured')->default(false);
                $table->double('deposit',12,2)->default(0);

                $table->double('min_price',12,2)->default(0);
                $table->boolean('show_min_price')->default(true);
                $table->text('addition_price_info')->nullable();

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
        return;
        Schema::dropIfExists('ven_venue');
    }
}
