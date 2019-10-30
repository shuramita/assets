<?php

namespace Shura\Asset\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Core\Organization\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Helpers\ImperialUnit;
use Shura\Asset\Models\Trails\AssetScope;

class Asset extends Model
{
    protected $table = 'ass_assets';

    protected $hidden = [];

    protected $casts = [
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c',
    ];

    protected $guarded = [];

    use AssetScope;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
            $model->business_unit_id = Auth::getBusinessUnit()->id;
            $model->code = $model->code ?? 0;
            $model->created_by = auth()->id() ?? 0;

        });
        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function building()
    {
        return $this->belongsTo('Shura\Asset\Models\Building');
    }

    public function floor()
    {
        return $this->belongsTo('Shura\Asset\Models\Floor');
    }

    public function type()
    {
        return $this->belongsTo('Shura\Asset\Models\AssetType', 'asset_type_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('Shura\Asset\Models\Tag', 'ass_asset_tag', 'asset_id', 'tag_id');
    }

    public function prices()
    {
        return $this->belongsToMany('Shura\Asset\Models\Price', 'ass_asset_price', 'asset_id', 'price_id')->withPivot('price');
    }


    public function owner()
    {
        return $this->belongsTo('Shura\Asset\Models\User', 'created_by')->select('*');

    }

    public static function addNewAsset($data)
    {

        $asset = Asset::create($data);
//        $asset->name = $data['name'];
//        $asset->status = $data['status'] ?? 'draft';
//        $asset->category_id = $data['category_id'] ?? 0;
//        $asset->description = $data['description'];
//        $asset->code = $data['code'] ?? 0;
//        if (isset($data['size'])) $asset->size = $data['size'];
//        $asset->asset_type_id = $data['asset_type_id'];
//        $asset->cover_photo = $data['cover_photo'] ?? null;
//        $asset->floor_photo = $data['floor_photo'] ?? null;
//        $asset->floor_id = $data['floor_id'] ?? 0;

        $asset->save();

        return $asset;
    }

    public static function updateAsset($data)
    {
        $asset = Asset::find($data['id'])->update($data);
//        $asset->name = $data['name'] ?? $asset->name;
//        $asset->category_id = $data['category_id'] ?? $asset->category_id;
//        $asset->description = $data['description'] ?? $asset->description;
//        $asset->code = $data['code'] ?? $asset->code;
//        $asset->size = $data['size'] ?? $asset->size;
//        $asset->cover_photo = $data['cover_photo'] ?? $asset->cover_photo;
//        $asset->floor_photo = $data['floor_photo'] ?? $asset->floor_photo;
//        $asset->status = $data['status'] ?? $asset->status;
//        $asset->update();

        return $asset;
    }


}
