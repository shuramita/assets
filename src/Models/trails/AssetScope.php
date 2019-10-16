<?php
/**
 * Created by PhpStorm.
 * User: tamnguyen
 * Date: 17/12/2018
 * Time: 10:16 AM
 */

namespace Shura\Asset\Models\Trails;


use Shura\Asset\Helpers\Helper;

trait AssetScope
{
    public function scopeInWorkingBuilding($query)
    {
        return $query->where('building_id', '=', Helper::building()->id ?? 0);
    }
    public function scopeInMyOrganization($query)
    {
        $buildings = Helper::org()->buildings->map(function($value) {
            return $value->id;
        });
//        var_dump($buildings);exit;
        return $query->whereIn('building_id', $buildings);
    }
    public function scopeIsVenue($query){
        return $query->where('asset_type_id','=',5);
    }
}
