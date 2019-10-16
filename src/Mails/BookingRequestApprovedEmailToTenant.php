<?php

namespace RealEstateDoc\Asset\Mails;

use App\Mail\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use RealEstateDoc\Asset\Models\Booking;
use RealEstateDoc\Asset\Models\Customer;

class BookingRequestApprovedEmailToTenant extends Mailable
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
        return $this->view($this->namespace.'::emails.booking-approved-email-to-tenant')
            ->subject('Booking approved')
            ->with([
                'booking' => $this->booking,
                'resource'=>(new Resource($this->booking->customer->email))
            ]);;
    }
}
