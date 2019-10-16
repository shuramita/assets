<?php

namespace RealEstateDoc\Asset\Events;

use Illuminate\Queue\SerializesModels;
use RealEstateDoc\Asset\Models\Asset;
use RealEstateDoc\Asset\Models\Booking;
use RealEstateDoc\Asset\Models\Venue;

class BookingRejected
{
    use SerializesModels;
    public $booking;
    public $asset;
    public $comment;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(Booking $booking, Asset $asset, $comment)
    {
        $this->asset = $asset;
        $this->booking = $booking;
        $this->comment = $comment;
    }
}