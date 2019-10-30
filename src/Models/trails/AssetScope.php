<?php
/**
 * Created by PhpStorm.
 * User: tamnguyen
 * Date: 17/12/2018
 * Time: 10:16 AM
 */

namespace Shura\Asset\Models\Trails;

use Core\Organization\Helpers\Helper as OrgHelper;

trait AssetScope
{
    public function scopeInBusinessUnit($query)
    {
        return $query->where('building_id', '=', OrgHelper::businessUnit()->id ?? 0);
    }
    public function scopeInOrganization($query)
    {
        $business_unit_ids = OrgHelper::org()->businessUnits()->map(function($value) {
            return $value->id;
        });
        return $query->whereIn('business_unit_id', $business_unit_ids);
    }
}
