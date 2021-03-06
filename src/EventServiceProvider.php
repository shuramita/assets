<?php

namespace Shura\Asset;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Shura\Asset\Listeners\UpdatePermissionForOrganizationOwner;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Shura\Asset\Events\NewAssetBooking' => [
            'Shura\Asset\Listener\SendEmailToTenant',
            'Shura\Asset\Listener\SendEmailToLandlord',
        ],
        'Shura\Asset\Events\BookingApproved' => [
            'Shura\Asset\Listener\SendBookingApprovedToTenant',
        ],
        'Shura\Asset\Events\BookingRejected' => [
            'Shura\Asset\Listener\SendBookingRejectedToTenant',
        ],
        'Core\Organization\Events\ModuleAdded'=>[
            UpdatePermissionForOrganizationOwner::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
