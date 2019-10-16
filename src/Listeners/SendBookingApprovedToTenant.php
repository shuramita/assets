<?php

namespace RealEstateDoc\Asset\Listener;

use Illuminate\Support\Facades\Mail;
use RealEstateDoc\Asset\Events\BookingApproved;
use RealEstateDoc\Asset\Mails\BookingRequestApprovedEmailToTenant;

class SendBookingApprovedToTenant
{

    public function __construct()
    {

    }

    public function handle(BookingApproved $event)
    {
        // only send if status in the list to update
        Mail::to($event->booking->customer->email)->send(new BookingRequestApprovedEmailToTenant($event->booking));

    }
}