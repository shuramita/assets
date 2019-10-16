<?php

namespace RealEstateDoc\Asset\Models;

use Averspace\Admin\Models\Media as BaseMedia;
use Illuminate\Database\Eloquent\Model;

class Media extends BaseMedia
{
    public function getValuesAttribute($value) {
        if(is_string($value)) {
            $media =  json_decode($value);
            if(isset($media->{'data-url'})) {
                unset($media->{'data-url'});
            }
            return $media;
        }
    }
    /**
     * Get all of the invoices that are assigned this item.
     */
    public function assets()
    {
        return $this->morphedByMany('RealEstateDoc\Asset\Models\Asset', 'mediable','ass_mediable');
    }
}
