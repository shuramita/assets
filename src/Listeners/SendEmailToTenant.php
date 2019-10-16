<?php

namespace Shura\Asset\Listener;

use Illuminate\Support\Facades\Mail;
use Shura\Asset\Events\NewAssetBooking;
use Shura\Asset\Mails\NewBookingRequestEmailToTenant;

class SendEmailToTenant
{


    public function handle(NewAssetBooking $event)
    {
        Mail::to($event->booking->customer->email)->send(new NewBookingRequestEmailToTenant($event->booking));
    }
}
