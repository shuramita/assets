<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use RealEstateDoc\Asset\Events\BookingApproved;
use RealEstateDoc\Asset\Events\BookingRejected;
use RealEstateDoc\Asset\Helpers\Helper;

class Booking extends Model
{
    protected $table = 'ass_booking';

    protected $hidden = [];
    /**
     * Boot function
     *
     * @return void
     */

    public function customer(){
        return $this->belongsTo('RealEstateDoc\Asset\Models\Customer','customer_id');
    }
    public function asset(){
        return $this->belongsTo('RealEstateDoc\Asset\Models\Asset','asset_id');
    }
    public function location(){
        return $this->belongsTo('RealEstateDoc\Asset\Models\Asset','asset_id');
    }
    public function getInvoiceAttribute(){
        if(!empty($this->start_date) && !empty($this->end_date)) {
            /* @var $this->asset Asset*/
            return $this->asset->calculatePrice($this->start_date, $this->end_date);
        }else{
            $invoice =  new \stdClass();
            $invoice->total = 1367;
            return $invoice;
        }


    }
    protected static function boot()
    {
        parent::boot();

    }
    public function scopeInMyOrganization($query)
    {
        $organization_id = Helper::org()->id ?? 0;
        $buildings = Helper::org()->buildings->map(function($value) {
            return $value->id;
        });
        return $query->whereHas('asset', function($query) use($buildings){
                $query->whereIn('ass_assets.building_id',$buildings);
        });
    }
    public function scopeCanApprove($query)
    {
        $query->whereIn('status',['pending','draft','rejected']);
    }
    public function scopeCanReject($query)
    {
        $query->whereIn('status',['pending','draft']);
    }
    public static function addNewBooking($data){
        $customer = Customer::addNewCustomer($data);
        $booking = new Booking();
        $booking->asset_id = $data['asset_id'];
        $booking->description = $data['description'];
        $booking->customer_id = $customer->id;
        $booking->status = $data['status'] ?? 'pending';
        $booking->save();
//        $booking->customer()->save($customer);
        return $booking;
    }
    public function approve(){
        $this->status = 'approved';
        $this->approved_by = auth()->id();
        $this->save();
        event(new BookingApproved($this, $this->asset));
        return $this;
    }
    public function reject($comment = null){
        $this->status = 'rejected';
        $this->save();
        event(new BookingRejected($this, $this->asset,$comment));
        return $this;
    }
    
}
