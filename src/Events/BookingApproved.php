<?php

namespace Shura\Asset\Events;

use Illuminate\Queue\SerializesModels;
use Shura\Asset\Models\Asset;
use Shura\Asset\Models\Booking;
use Shura\Asset\Models\Venue;

class BookingApproved
{
    use SerializesModels;
    public $booking;
    public $asset;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(Booking $booking, Asset $asset)
    {
        $this->asset = $asset;
        $this->booking = $booking;
    }
}
