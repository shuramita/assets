<?php

namespace RealEstateDoc\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealEstateDoc\Asset\Helpers\Helper;
use RealEstateDoc\Asset\Models\Trails\VenueScope;

class Venue extends Asset
{
    use VenueScope;
    protected $table = 'ass_assets';

    protected $hidden = [];

    public function organization() {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Organization','organization_id');
    }
    
    public function photos() {
        return $this->morphToMany('RealEstateDoc\Asset\Models\Media', 'mediable','ass_mediables');
    }

    public function types() {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\VenueType', 'ven_venue_static_data', 'venue_id','static_id');
    }
    public function events() {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\EventType', 'ven_venue_static_data', 'venue_id','static_id');
    }
    public function background() {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Media','background_photo');
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->slug = str_slug($model->name);
            $model->organization_id = Helper::org()->id;
            $model->created_by = auth()->id();
        });
        static::updating(function ($model)
        {
            $model->slug = str_slug($model->name);
        });
        
    }
    public static function addNewVenue($data){
        $venue = new Venue();
        $venue->name = $data['name'];
        $venue->description = $data['description'] ?? '';
        
        $venue->address = $data['address'] ?? '';
        $venue->email = $data['email']  ?? '';
        $venue->website = $data['website']  ?? '';
        $venue->phone_number = $data['phone_number']  ?? '';
        if (isset($data['min_siting'])) $venue->min_siting =  $data['min_siting'];
        $venue->save();
        if( isset($data['photos']) && is_array($data['photos']) ) {
            $photos = Media::whereIn('id', $data['photos'])->get();
            $venue->photos()->saveMany($photos);
        }
        if( isset($data['types']) && is_array($data['types']) ) {
            $types = VenueType::whereIn('id', $data['types'])->get();
            $venue->types()->saveMany($types);
        }
        if( isset($data['events']) && is_array($data['events']) ) {
            $events = EventType::whereIn('id', $data['events'])->get();
            $venue->types()->saveMany($events);
        }
        return $venue;
    }

}
