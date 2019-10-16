<?php

namespace Shura\Asset\Listener;

use Illuminate\Support\Facades\Mail;
use Shura\Asset\Events\NewAssetBooking;
use Shura\Asset\Mails\NewBookingRequestEmailToLandlord;

class SendEmailToLandlord
{


    public function handle(NewAssetBooking $event)
    {

        Mail::to($event->booking->asset->owner->email)->send(new NewBookingRequestEmailToLandlord($event->booking));
    }
}



