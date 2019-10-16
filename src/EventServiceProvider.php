<?php

namespace RealEstateDoc\Asset;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'RealEstateDoc\Asset\Events\NewAssetBooking' => [
            'RealEstateDoc\Asset\Listener\SendEmailToTenant',
            'RealEstateDoc\Asset\Listener\SendEmailToLandlord',
        ],
        'RealEstateDoc\Asset\Events\BookingApproved' => [
            'RealEstateDoc\Asset\Listener\SendBookingApprovedToTenant',
        ],
        'RealEstateDoc\Asset\Events\BookingRejected' => [
            'RealEstateDoc\Asset\Listener\SendBookingRejectedToTenant',
        ],
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
