<?php

namespace Shura\Asset\Observers;

use Core\Organization\Models\Organization;
use Shura\Asset\Models\Currency;
use Shura\Asset\Models\Price;

class OrganizationObserver
{
    public function created(Organization $organization)
    {
        Currency::seedCurrencyToDatabase($organization->id);
        Price::addNewPrice([
            'name' => 'Normal Price',
            'type' => 'normal',
            'unit' => 'daily',
            'description' => 'Default Price type that created by system'
        ], $organization->id);
    }
}
