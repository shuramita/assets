<?php
namespace Shura\Asset\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Shura\Asset\Helpers\Helper;

class StaticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ass_static_data')->truncate();

        $venue_static_data = Helper::getJsonFromStaticData('venue-type.json');
        $this->addStaticData($venue_static_data,'type');

        $accessibility_static_data = Helper::getJsonFromStaticData('accessibility.json');
        $this->addStaticData($accessibility_static_data,'accessibility');

        $amenties_static_data = Helper::getJsonFromStaticData('amenties.json');
        $this->addStaticData($amenties_static_data,'amenities');

        $event_static_data = Helper::getJsonFromStaticData('event-type.json');
        $this->addStaticData($event_static_data,'event');

        $food_beverage_static_data = Helper::getJsonFromStaticData('food-and-beverage.json');
        $this->addStaticData($food_beverage_static_data,'food-beverage');

        $vibe_beverage_static_data = Helper::getJsonFromStaticData('vibe.json');
        $this->addStaticData($vibe_beverage_static_data,'vibe');


    }
    protected function addStaticData($data,$model){
        foreach ($data as $static_data) {
            DB::table('ass_static_data')->insert([
                'static_id' => $static_data->id,
                'name' => $static_data->name,
                'slug' => $static_data->slug,
                'system_id' => $static_data->system_id,
                'group_id' => $static_data->group_id ?? 0,
                'group_name' => $static_data->group_name ?? '',
                'model' => $model,
                'description'=>$static_data->description,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
        }
    }
}
