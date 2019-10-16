<?php

namespace Shura\Asset\Listener;

use Illuminate\Support\Facades\Mail;
use Shura\Asset\Events\BookingRejected;
use Shura\Asset\Mails\BookingRequestRejectedEmailToTenant;

class SendBookingRejectedToTenant
{

    public function __construct()
    {

    }

    public function handle(BookingRejected $event)
    {
        Mail::to($event->booking->customer->email)->send(new BookingRequestRejectedEmailToTenant($event->booking,$event->comment));

    }
}
