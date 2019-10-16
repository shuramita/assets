<?php

namespace RealEstateDoc\Asset\Listener;

use Illuminate\Support\Facades\Mail;
use RealEstateDoc\Asset\Events\NewAssetBooking;
use RealEstateDoc\Asset\Mails\NewBookingRequestEmailToLandlord;

class SendEmailToLandlord
{


    public function handle(NewAssetBooking $event)
    {

        Mail::to($event->booking->asset->owner->email)->send(new NewBookingRequestEmailToLandlord($event->booking));
    }
}



