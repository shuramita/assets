<?php

namespace Shura\Asset\Mails;

use App\Mail\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shura\Asset\Models\Booking;
use Shura\Asset\Models\Customer;

class BookingRequestRejectedEmailToTenant extends Mailable
{
    use Queueable, SerializesModels;
    public $namespace = 'Asset';
    protected $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking,$comment)
    {
        $this->booking = $booking;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->namespace.'::emails.booking-rejected-email-to-tenant')
            ->subject('Booking rejected')
            ->with([
                'booking' => $this->booking,
                'comment'=> $this->comment,
                'resource'=>(new Resource($this->booking->customer->email))
            ]);;
    }
}
