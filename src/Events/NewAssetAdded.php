<?php

namespace RealEstateDoc\Asset\Events;

use Illuminate\Queue\SerializesModels;
use RealEstateDoc\Asset\Models\Asset;
use RealEstateDoc\Asset\Models\Booking;
use RealEstateDoc\Asset\Models\Venue;

class NewAssetAdded
{
    use SerializesModels;
    public $asset;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(Asset $asset)
    {
        $this->booking = $booking;
    }
}