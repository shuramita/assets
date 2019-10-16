<?php

namespace Shura\Asset\Controllers\API;

use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Models\Booking;
use Illuminate\Validation\Rule;
use Shura\Asset\Models\Asset;
use Shura\Asset\Events\NewAssetBooking;
use Validator;

class BookingController extends Controller
{
    public function book(Request $request) {
        $model = new Booking();
        $validator = Validator::make($request->all(),[
            'asset_id'=>'required|exists:ass_assets,id',
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'phone_number'=>'required',
            'credit_card_number'=>'required',
            'card_holder'=>'required',
            'expired_date'=>'required',
            'cvv'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->validationError('Booking data invalid',422,$validator->errors()->messages());
        }
        $booking = Booking::addNewBooking($request->all());
        event(new NewAssetBooking($booking,Asset::find($request->get('asset_id'))));
        return $this->jsonResponse(Booking::with(['customer','asset'])->find($booking->id));
    }
    public function update(Request $request) {

    }
    public function info(Request $request, $booking){
        $bookingDetail = Booking::
        inMyOrganization()
            ->with(['customer','asset','asset.photos'])
            ->find($booking);
        return !empty($bookingDetail)
                ? $this->jsonResponse($bookingDetail)
            : $this->validationError('Booking not found',
                200,['suggestion'=>"The booking with ID {$booking} not found not you're not have permission to view this booking"]);
    }

    public function search(Request $request){
            return $this->jsonResponse(Booking::
            inMyOrganization()
                ->with(['customer','asset','asset.photos','asset.floor'])
                ->orderBy('created_at','desc')
                ->paginate($request->per_page ?? 30)
            );
    }

    public function approve(Request $request,$booking) {
        /** @var  $bookingDetail Booking*/
        $bookingDetail = Booking::
                        inMyOrganization()
                        ->canApprove()
                        ->find($booking);
        if(empty($bookingDetail)) {
            return $this->validationError('Booking not found or current status is not able for approve',
                200,['suggestion'=>"The booking with ID {$booking} with current status not found or you're not have permission to view this booking"]);
        }
//        var_dump($bookingDetail);exit;
        if($booking  = $bookingDetail->approve()){
            return $this->jsonResponse($booking,'Approved successful');
        }
    }
    public function reject(Request $request,$booking) {
        /** @var  $bookingDetail Booking*/
        $bookingDetail = Booking::
        inMyOrganization()
            ->canReject()
            ->find($booking);
        if(empty($bookingDetail)) {
            return $this->validationError('Booking not found or current status is not able for reject',
                200,['suggestion'=>"The booking with ID {$booking} with current status not found or you're not have permission to view this booking"]);
        }
        if($booking  = $bookingDetail->reject($request->get('comment'))){
            return $this->jsonResponse($booking,'Rejected successful :(');
        }
    }
}
