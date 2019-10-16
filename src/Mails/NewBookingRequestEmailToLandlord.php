<?php

namespace Shura\Asset\Mails;

use App\Mail\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shura\Asset\Models\Booking;
use Shura\Asset\Models\Customer;

class NewBookingRequestEmailToLandlord extends Mailable
{
    use Queueable, SerializesModels;
    public $namespace = 'Asset';
    protected $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->namespace.'::emails.new-booking-to-landlord')
            ->subject('New booking for your venue')
            ->with([
                'booking' => $this->booking,
                'resource'=>(new Resource($this->booking->customer->email))
            ]);;
    }
}
