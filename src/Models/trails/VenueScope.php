<?php
/**
 * Created by PhpStorm.
 * User: tamnguyen
 * Date: 17/12/2018
 * Time: 10:16 AM
 */

namespace RealEstateDoc\Asset\Models\Trails;


use RealEstateDoc\Asset\Helpers\Helper;

trait VenueScope
{
    public function scopeInWorkingBuilding($query)
    {
        return $query->where('building_id', '=', Helper::building()->id ?? 0);
    }
    public function scopeInMyOrganization($query)
    {
        $organization_id = Helper::org()->id ?? 0;
        return $query->where('organization_id', $organization_id);
    }
}