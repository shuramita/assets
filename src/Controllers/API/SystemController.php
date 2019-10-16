<?php

namespace Shura\Asset\Controllers\API;

use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Amenity;
use Shura\Asset\Models\Asset as AssetModel;
use Shura\Asset\Models\AssetType;
use Shura\Asset\Models\EventType;
use Shura\Asset\Models\Organization;
use Shura\Asset\Models\VenueType;
use Validator;
use Illuminate\Validation\Rule;
use Shura\Asset\Models\Price;
use Shura\Asset\Models\Field;

class SystemController extends Controller
{
    public function info(Request $request) {
        $info = new \stdClass();
//        $info->organization = Helper::org();
//        if(!empty($info->organization)) {
//            $info->organization->building = Helper::building();
//            if(!empty($info->organization->building)) {
//                $info->organization->building->floors;
//                $info->organization->buildings;
//            }
//            $info->organization->prices;
//            $info->organization->fields;
//            $info->organization->taxes;
//            $info->organizations = Organization::createdByMe()->get()->all();
//        }
        $info->asset_types = AssetType::all();
        $info->events = EventType::all()->groupBy('group_name');
        $info->venue_types = VenueType::all();
        $info->amenities = Amenity::all();
        $info->locations = Helper::getJsonFromStaticData('location.json');
        $info->currencies = Helper::getJsonFromStaticData('currency.json');
        $info->languages = Helper::getJsonFromStaticData('language.json');
        $info->timezone = Helper::getJsonFromStaticData('timezone.json');
        $info->price_options = Helper::getJsonFromStaticData('price_option.json');
        return $this->jsonResponse($info);
    }
}
