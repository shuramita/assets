<?php

namespace RealEstateDoc\Asset\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealEstateDoc\Asset\Helpers\Helper;
use RealEstateDoc\Asset\Helpers\ImperialUnit;
use RealEstateDoc\Asset\Models\Trails\AssetScope;
use RealEstateDoc\Asset\Models\Trails\PriceCalculation;
use RealEstateDoc\Calendar\Models\Contract;

class Asset extends Model
{
    protected $table = 'ass_assets';

    protected $hidden = [];
//    protected $appends = ['floor'];

//    protected $with = ['floor'];
//    protected $dateFormat = 'c'; //ISO 8601 date (added in PHP 5)

    use AssetScope;
    use PriceCalculation;
    protected $casts = [
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c',
    ];

    public function getFloorPhotoAttribute($value)
    {
        if(!empty($value)) {
            return json_decode($value);
        }
    }
//    public function getPhotosAttribute($value)
//    {
//        if(!empty($value)) {
//            return json_decode($value);
//        }
//    }
//    public function getCoverPhotoAttribute($value)
//    {
//        if(!empty($value)) {
//            return json_decode($value);
//        }
//    }
    public function getFloorPolygonAttribute($value)
    {
        if(!empty($value)) {
            return json_decode($value);
        }
    }
    public function getIdentifyColorAttribute(){
        return array_random([
            '#723bd4',
            '#f5a323'
        ]);
    }
//    public function setFloorPhotoAttribute($value)
//    {
//        $this->attributes['floor_photo'] = gettype($value) != 'string' ? json_encode($value) : $value;
//    }
    public function setFloorPolygonAttribute($value)
    {
        $this->attributes['floor_polygon'] = gettype($value) != 'string' ? json_encode($value) : $value;
    }
//    public function setPhotosAttribute($value)
//    {
//        $this->attributes['photos'] = gettype($value) != 'string' ? json_encode($value) : $value;
//    }
//    public function setCoverPhotoAttribute($value)
//    {
//        $this->attributes['cover_photo'] = gettype($value) != 'string' ? json_encode($value) : $value;
//    }
    public function setSizeAttribute($value){
//        $this->attributes['size'] = gettype($value) == 'string' $value;
        if($value instanceof ImperialUnit) {
            $this->attributes['size'] = $value->toStringFormat();
        }elseif(gettype($value) == 'string'){
            $this->attributes['size'] = $value;
        }
//        var_dump($value);exit;
    }
    public function getSizeAttribute($value){
//        var_dump(gettype($value));exit;
        if(!empty($value) ) {
            if(gettype($value) == 'string') {
                return new ImperialUnit($value);
            }elseif(gettype($value) == 'double' || gettype($value) == 'integer'){
                return new ImperialUnit($value);
            }
        }
    }
    /**
     * Boot function
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->slug = str_slug($model->name);
            $model->building_id = Helper::building()->id;
            $model->created_by = auth()->id() ?? 0;
            if(empty($model->tax_id)) {
                $model->tax_id = Helper::org()->getDefaultTaxAttribute()->id;
            }
        });
        static::updating(function ($model)
        {
            $model->slug = str_slug($model->name);
        });
    }
    public function building()
    {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Building');
    }
//    public function getFloorAttribute(){
//        return $this->floor();
//    }
    public function floor()
    {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Floor');
    }
    public function cover()
    {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Media','cover_photo');
    }
    public function background()
    {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Media','background_photo');
    }
    public function type()
    {
        return $this->belongsTo('RealEstateDoc\Asset\Models\AssetType','asset_type_id');
    }
    public function locationType() {
        return $this->belongsTo('RealEstateDoc\Asset\Models\AssetType','asset_type_id');
    }
    public function tags(): BelongsToMany {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\Tag','ass_asset_tag','asset_id','tag_id');
    }
    public function prices() {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\Price','ass_asset_price','asset_id','price_id')->withPivot('price');
    }
    public function tax() {
        return $this->belongsTo('RealEstateDoc\Asset\Models\Tax');
    }
    public function childs(){
        return $this->hasMany('RealEstateDoc\Asset\Models\Asset','parent_asset_id')->with('prices');
    }
    public function parent(){
        return $this->belongsTo('RealEstateDoc\Asset\Models\Asset','parent_asset_id')->with('prices');
    }
    public function photos() {
        return $this->morphToMany('RealEstateDoc\Asset\Models\Media', 'mediable','ass_mediables');
    }
    public function fields(){
        return $this->belongsToMany('RealEstateDoc\Asset\Models\Field','ass_asset_field','asset_id','field_id')->withPivot('value');
    }
//    public function fieldsPublic(){
//        return $this->fields()->withoutGlobalScope('model');
//    }
    public function types() {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\VenueType', 'ass_asset_static_data', 'asset_id','static_id');
//            ->where('static_type','=','type');
    }
//    public function types() {
//        return $this->morphToMany('RealEstateDoc\Asset\Models\VenueType', 'statics', 'ass_asset_static_data',
//            'static_id','asset_id');
//    }

    public function events() {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\EventType', 'ass_asset_static_data', 'asset_id','static_id');
    }
    public function amenities() {
        return $this->belongsToMany('RealEstateDoc\Asset\Models\Amenity', 'ass_asset_static_data', 'asset_id','static_id');
    }
    public function owner(){
        return $this->belongsTo('RealEstateDoc\Asset\Models\User','created_by')->select('*');

    }
    public function getBookedDatesAttribute($format = 'toIso8601String'){
        $booked_dates = [];

        if($this->contracts->count() > 0 ) {
            foreach ($this->contracts as $contract) {
                // TODO , refactory contract model
                $period = CarbonPeriod::create($contract->{'contract-start-date'}, $contract->{'contract-end-date'});
//                $periods[] = $period;
                // Iterate over the period
                foreach ($period as $date) {
                    if($format == 'Carbon') {
                        $booked_dates[] = $date;
                    }else{
                        $booked_dates[] = $date->toIso8601String();
                    }

                }
            }
        }
        return $booked_dates;
    }


    public function getNextAvailableDateAttribute(){
        $available_date = Carbon::createMidnightDate()->addDay(1);
        // srot booked dates
        $booked_dates = collect($this->getBookedDatesAttribute('Carbon'))->sortBy(function ($obj, $key) {
            return $obj->timestamp;
        });
        foreach ($booked_dates as $key => $date) {
            if($date->eq($available_date)) {
                $available_date = $available_date->addDay(1);
            }
        }
        return $available_date;
    }
    public function nextEndDateFrom(Carbon $start_date){
        $end_date = $start_date->addDay(1);
        // sort booked dates
        if($this->isInBookedDates($end_date)) return null;
        // check if any date available in ext 14 days
        for($i=1;$i<8;$i++){
            $end_date = $end_date->addDay(1);
            if($this->isInBookedDates($end_date)) {
                return $end_date->subDays(1);
            }
        }
        return $end_date;
    }
    public function isInBookedDates(Carbon $date){
        $booked_dates = collect($this->getBookedDatesAttribute('Carbon'))->sortBy(function ($obj, $key) {
            return $obj->timestamp;
        });
        foreach ($booked_dates as $d){
            if($d->eq($date)) {
                return true;
            }
        }
        return false;
    }
    public static function addNewAsset($data){

        $asset = new Asset();
        $asset->name = $data['name'];
        $asset->status = $data['status'] ?? 'draft';
        $asset->category_id = $data['category_id']  ?? 0;
        $asset->description = $data['description'];
        $asset->code = $data['code'] ?? 0;
        if(isset($data['size']))  $asset->size = $data['size'];
        $asset->asset_type_id = $data['asset_type_id'];
        $asset->cover_photo = $data['cover_photo'] ?? 0;
        $asset->floor_photo = $data['floor_photo'] ?? 0;
        $asset->floor_id = $data['floor_id'] ?? 0;
        $asset->parent_asset_id = $data['parent_asset_id'] ?? 0;
        $asset->floor_polygon = $data['floor_polygon'] ?? [];
        if(isset($data['tax_id'])) {
            $asset->tax_id = $data['tax_id'];
        }
        $asset->save();
//        var_dump($data['photos']);exit;
        if( isset($data['photos']) && is_array($data['photos']) ) {
            $photos = Media::whereIn('id', $data['photos'])->get();
            $asset->photos()->saveMany($photos);
        }
        if( isset($data['prices']) && is_array($data['prices']) ) {
            $prices = [];
            foreach ($data['prices'] as $price_id => $price_value) {
                $prices[$price_id] = ['price'=>$price_value];
            }
            $asset->prices()->attach($prices);
        }
        if( isset($data['fields']) && is_array($data['fields']) ) {
            $fields = [];
            foreach ($data['fields'] as $field_id => $field_value) {
                $fields[$field_id] = ['value'=>$field_value];
            }
            $asset->fields()->attach($fields);
        }
        if( isset($data['types']) && is_array($data['types']) ) {
            $types = VenueType::whereIn('id', $data['types'])->get();
            $asset->types()->saveMany($types);
        }
        if( isset($data['events']) && is_array($data['events']) ) {
            $events = EventType::whereIn('id', $data['events'])->get();
            $asset->types()->saveMany($events);
        }
        if( isset($data['amenities']) && is_array($data['amenities']) ) {
            $amenities = Amenity::whereIn('id', $data['amenities'])->get();
            $asset->amenities()->saveMany($amenities);
        }
        return $asset;
    }
    public static function updateAsset($data){
        $asset = Asset::find($data['id']);
        $asset->name = $data['name'] ?? $asset->name;
        $asset->category_id = $data['category_id']  ?? $asset->category_id;
        $asset->description = $data['description'] ?? $asset->description;
        $asset->code = $data['code'] ?? $asset->code;
        $asset->size = $data['size'] ?? $asset->size;
        $asset->floor_id = $data['floor_id'] ?? $asset->floor_id;
        $asset->cover_photo = $data['cover_photo'] ?? $asset->cover_photo;
        $asset->floor_photo = $data['floor_photo'] ?? $asset->floor_photo;
        $asset->floor_id = $data['floor_id'] ?? $asset->floor_id;
        $asset->floor_polygon = $data['floor_polygon'] ?? $asset->floor_polygon;
        $asset->status = $data['status'] ?? $asset->status;
        $asset->update();
        if( isset($data['photos']) && is_array($data['photos']) ) {
            $photos = Media::whereIn('id', $data['photos'])->get();
            $asset->photos()->sync($photos);
        }
        if( isset($data['prices']) && is_array($data['prices']) ) {
            $prices = [];
            foreach ($data['prices'] as $price_id => $price_value) {
                $prices[$price_id] = ['price'=>$price_value];
            }
            $asset->prices()->sync($prices);
        }
        if( isset($data['fields']) && is_array($data['fields']) ) {
            $fields = [];
            foreach ($data['fields'] as $field_id => $field_value) {
                $fields[$field_id] = ['value'=>$field_value];
            }
            $asset->fields()->sync($fields);
        }
        if( isset($data['events']) && is_array($data['events']) ) {
            $events = EventType::select('id')->whereIn('id', $data['events'])->get();
            $olds = $asset->events->map(function ($event){
                return $event->id;
            });
//            var_dump($olds);exit;
            $asset->events()->detach($olds);
            $asset->events()->saveMany($events);
        }
        if( isset($data['types']) && is_array($data['types']) ) {
            $types = VenueType::select('id')->whereIn('id', $data['types'])->get();
            $olds = $asset->types->map(function ($type){
                return $type->id;
            });
//            var_dump($olds);
            $asset->types()->detach($olds);
            $asset->types()->saveMany($types);
        }
        if( isset($data['amenities']) && is_array($data['amenities']) ) {
            $amenities = Amenity::select('id')->whereIn('id', $data['amenities'])->get();
            $olds = $asset->amenities->map(function ($amenity){
                return $amenity->id;
            });
            $asset->amenities()->detach($olds);
            $asset->amenities()->saveMany($amenities);
        }
        return $asset;
    }
    protected function updateRelation($model){

    }
}
