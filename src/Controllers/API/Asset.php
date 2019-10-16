<?php

namespace Shura\Asset\Controllers\API;

use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Asset as AssetModel;
use Shura\Asset\Models\AssetType;
use Shura\Asset\Models\EventType;
use Shura\Asset\Models\Organization;
use Shura\Asset\Models\VenueType;
use Validator;
use Illuminate\Validation\Rule;
use Shura\Asset\Models\Price;
use Shura\Asset\Models\Field;
use Shura\Asset\Models\Amenity;

class Asset extends Controller
{
    public function add(Request $request) {
//        var_dump($request->get('prices'));exit;
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'status'=>['nullable',function ($attribute, $value, $fail) {
                $statuses = Helper::collect('asset-status.json');
                if(empty($statuses->where('id',$value)->all())) {
                    $fail("Status  {$value} invalid.");
                }
            }],
            'code'=>[
                'nullable',
                Rule::unique('ass_assets')->where(function ($query) {
                    return $query->where('building_id', Helper::building()->id);
                })],
            'cover_photo'=>'nullable|int|exists:media,id',
            'floor_photo'=>'nullable|int|exists:media,id',
            'asset_type_id'=>'required|int|exists:ass_type,id',
            'types.*'=>'required|int|exists:ass_static_data,id',
            'events'=>'nullable|array|min:1',
            'events.*'=>'nullable|int|exists:ass_static_data,id',
            'amenities'=>'nullable|array|min:1',
            'amenities.*'=>'nullable|int|exists:ass_static_data,id',
            'floor_id'=>'nullable|int|exists:ass_floor,id',
            'parent_asset_id'=>'nullable|int|exists:ass_assets,id',
            'description'=>'required',
            'photos'=>'nullable|array|min:1',
            'prices'=>[
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $prices = [];
                    foreach ($value as $price_id => $price_value) {
                        if(empty(Price::find($price_id))) {
                            $fail('Price ID '.$price_id.' is invalid.');
                        }
                    }
                },
            ],
            'prices.*' => [
                'nullable',
                'regex:/^\d*(\.\d{1,2})?$/'
            ],
            'tax_id'=>'nullable|int|exists:ass_setting,id',
            'fields'=>[
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $prices = [];
                    foreach ($value as $field_id => $field_value) {
                        if(empty(Field::find($field_id))) {
                            $fail('Price ID '.$field_id.' is invalid.');
                        }
                    }
                },
            ]
        ]);
//        var_dump($request->get('photos'));exit;
//        var_dump();exit;
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(),422);
        }

        $asset = AssetModel::addNewAsset($request->all());

        $asset->floor;
        $asset->cover;
        $asset->building;
        $asset->type;
        $asset->photos;
        $asset->prices;
        $asset->fields;
        $asset->tax;
        $asset->childs;
        $asset->types;
        $asset->events;
        $asset->amenities;
        return $this->jsonResponse($asset);
    }
    public function info(Request $request) {
        $info = new \stdClass();
        $info->organization = Helper::org();
        if(!empty($info->organization)) {
            $info->organization->building = Helper::building();
            if(!empty($info->organization->building)) {
                $info->organization->building->floors;
                $info->organization->buildings;
            }
            $info->organization->prices;
            $info->organization->fields;
            $info->organization->taxes;
            $info->organizations = Organization::createdByMe()->get()->all();
        }
        $info->asset_types = AssetType::enabled()->get();
        $info->events = EventType::all()->groupBy('group_name');
        $info->venue_types = VenueType::all();
        $info->amenities = Amenity::all();
        $info->locations = Helper::getJsonFromStaticData('location.json');
        $info->currencies = Helper::getJsonFromStaticData('currency.json');
        $info->languages = Helper::getJsonFromStaticData('language.json');
        $info->timezone = Helper::getJsonFromStaticData('timezone.json');
        $info->price_options = Helper::getJsonFromStaticData('price_option.json');
        return $this->jsonResponse($info);
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(),[
            'id'=>[ 'required',
                    function ($attribute, $value, $fail) {
                        $asset = AssetModel::where('id','=',$value)->where('building_id','=',Helper::building()->id)->get();
                        if (count($asset) == 0 ) {
                            $fail('Asset with '.$attribute. ' ' . $value . ' is invalid or not belong to Building .'.Helper::building()->name );
                        }
                    },
                ],
            'name'=>'nullable|string',
            'status'=>['nullable',function ($attribute, $value, $fail) {
                $statuses = Helper::collect('asset-status.json');

                if(empty($statuses->where('id',$value)->all())) {
                    $fail("Status  {$value} invalid.");
                }
            }],
            'code'=>[
                'nullable',
                Rule::unique('ass_assets')->where(function ($query) {
                    return $query->where('building_id', Helper::building()->id);
                })],
            'cover_photo'=>'nullable|int|exists:media,id',
            'floor_photo'=>'nullable|int|exists:media,id',
            'asset_type_id'=>'nullable|int|exists:ass_type,id',
            'floor_id'=>'nullable|int|exists:ass_floor,id',
            'parent_asset_id'=>'nullable|int|exists:ass_assets,id',
            'types.*'=>'required|int|exists:ass_static_data,id',
            'events'=>'nullable|array|min:1',
            'events.*'=>'nullable|int|exists:ass_static_data,id',
            'amenities'=>'nullable|array|min:1',
            'amenities.*'=>'nullable|int|exists:ass_static_data,id',
//            'description'=>'required',
            'photos'=>'nullable|array|min:1',
            'prices'=>[
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $prices = [];
                    foreach ($value as $price_id => $price_value) {
                        if(empty(Price::find($price_id))) {
                            $fail('Price ID '.$price_id.' is invalid.');
                        }
                    }
                },
            ],
            'prices.*' => [
                'nullable',
                'regex:/^\d*(\.\d{1,2})?$/'
            ],
            'tax_id'=>'nullable|int|exists:ass_setting,id',
            'fields'=>[
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $prices = [];
                    foreach ($value as $field_id => $field_value) {
                        if(empty(Field::find($field_id))) {
                            $fail('Price ID '.$field_id.' is invalid.');
                        }
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(),422);
        }

        $asset = AssetModel::updateAsset($request->all());
        $asset = AssetModel::with([
            'floor',
            'cover',
            'building',
            'type',
            'photos',
            'prices',
            'childs',
            'types',
            'events',
            'amenities',
            'fields'
        ])->find($asset->id);
//        $asset->floor;
//        $asset->cover;
//        $asset->building;
//        $asset->type;
//        $asset->photos;
//        $asset->prices;
//        $asset->childs;
//
//        $asset->types;
//        $asset->events;
//        $asset->amenities;
        return $this->jsonResponse($asset);
    }
    public function search(Request $request) {
        return $this->jsonResponse(
            AssetModel::
                inWorkingBuilding()
                ->inMyOrganization()
                ->isVenue()
                ->with(['building','cover','background','prices'])
                ->paginate($request->per_page ?? 15));
    }
    public function searchMarketPlace(Request $request) {
//        var_dump(\Shura\Asset\Models\Asset::with('owner')->find(35)->toArray());exit;
        return $this->jsonResponse(
            AssetModel::
                with(['building','cover','background','prices','events','types','photos','owner'])
                ->whereNotNull('created_by')->where('created_by','<>',0)
                ->has('types')
                ->where('status','=','published')
                ->paginate($request->per_page ?? 15));
    }
    public function detail(Request $request,$id) {
//        return AssetModel::with(['types'])->find($id);
        $validator = Validator::make(['id'=>$id],[
            'id'=>[ 'required',
                function ($attribute, $value, $fail) {
                    $asset = AssetModel::where('id','=',$value)->where('building_id','=',Helper::building()->id)->get();
                    if (count($asset) == 0 ) {
                        $fail('Asset with '.$attribute. ' ' . $value . ' is invalid or not belong to Building .'.Helper::building()->name );
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(),422);
        }

        $asset = AssetModel::find($id);

        $asset->floor;
        $asset->cover;
        $asset->building;
        $asset->type;
        $asset->photos;
        $asset->prices;
        $asset->tax;
        $asset->parent;
        $asset->childs;
        $asset->owner;
        $asset->types;
        $asset->events;
        $asset->amenities;
        $asset->fields;
        return $this->jsonResponse($asset);
    }
    public function detailForMarket(Request $request,$id){
        $validator = Validator::make(['id'=>$id],[
            'id'=>[ 'required',
                function ($attribute, $value, $fail) {
                    $asset = AssetModel::where('id','=',$value)->get();
                    if (count($asset) == 0 ) {
                        $fail('Asset with '.$attribute. ' ' . $value . ' not found' );
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(),422);
        }

        $asset = AssetModel::find($id);

        $asset->floor;
        $asset->cover;
        $asset->building;
        $asset->type;
        $asset->photos;
        $asset->prices;
//        $asset->tax;
        $asset->parent;
//        $asset->childs;
        $asset->owner;
        $asset->types;
        $asset->events;
        $asset->amenities;
        $asset->fields;
//        $asset->fieldsPublic;
        return $this->jsonResponse($asset);
    }
    public function addField(Request $request) {
        $data = $request->all();
        $data['model'] = 'asset';
        $validator = Validator::make($data,[
            'key'=>'required',
            'type'=>'required|in:number,string,text',
            'model'=>'required',
            'title'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(),422);
        }

        return $this->jsonResponse(Field::addNewField($data));
    }
}
