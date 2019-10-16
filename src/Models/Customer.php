<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    protected $table = 'ass_customer';

    protected $hidden = [];
    protected $appends = ['name'];
    public function bookings(){
        return $this->hasMany('Shura\Asset\Models\Booking');
    }
    public function getNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }
    protected static function boot()
    {
        parent::boot();
    }

    public function scopeInOrganization($query)
    {
        $organization_id = Helper::org()->id ?? 0;
        return $query->where('organization_id', $organization_id);
    }
    public static function addNewCustomer($data){
        $customer = new Customer();
        $customer->first_name = $data['first_name'];
        $customer->last_name = $data['last_name'];
        $customer->email = $data['email'];
        $customer->phone_number = $data['phone_number'];
        $customer->credit_card_number = $data['credit_card_number'];
        $customer->card_holder = $data['card_holder'];
        $customer->expired_date = $data['expired_date'];
        $customer->cvv = $data['cvv'];

        $customer->save();
        return $customer;
    }
}
