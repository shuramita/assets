<?php

namespace RealEstateDoc\Asset\Listener;

use Illuminate\Support\Facades\Mail;
use RealEstateDoc\Asset\Events\NewAssetBooking;
use RealEstateDoc\Asset\Mails\NewBookingRequestEmailToTenant;

class SendEmailToTenant
{


    public function handle(NewAssetBooking $event)
    {
        Mail::to($event->booking->customer->email)->send(new NewBookingRequestEmailToTenant($event->booking));
    }
}