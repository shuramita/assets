<?php

namespace Shura\Asset\Controllers\API;

use Core\Organization\Facades\Auth;
use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Asset as AssetModel;
use Shura\Asset\Requests\CreateAssetRequest;
use Validator;
use Illuminate\Validation\Rule;
use Shura\Asset\Models\Price;
use Shura\Asset\Models\Field;

/**
 * @group Asset Module
 *
 * */
class Asset extends Controller
{
    /**
     * Add new asset
     * User can add new asset
     * @authenticated
     * @bodyParam name string require The name of asset Example: The location A in floor 1
     * @bodyParam status string option The status of asset, can be null or one of value default **draft** or **unpublished**, **published**   Example: published
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => ['nullable', function ($attribute, $value, $fail) {
                $statuses = Helper::collect('asset-status.json');
                if (empty($statuses->where('id', $value)->all())) {
                    $fail("Status  {$value} invalid.");
                }
            }],
            'code' => [
                'nullable',
                Rule::unique('ass_assets')->where(function ($query) {
                    return $query->where('building_id', Helper::building()->id);
                })],
            'cover_photo' => 'nullable|int|exists:media,id',
            'floor_photo' => 'nullable|int|exists:media,id',
            'asset_type_id' => 'required|int|exists:ass_type,id',
            'floor_id' => 'nullable|int|exists:ass_floor,id',
            'parent_asset_id' => 'nullable|int|exists:ass_assets,id',
            'description' => 'required',
            'photos' => 'nullable|array|min:1',
            'prices' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $prices = [];
                    foreach ($value as $price_id => $price_value) {
                        if (empty(Price::find($price_id))) {
                            $fail('Price ID ' . $price_id . ' is invalid.');
                        }
                    }
                },
            ],
            'prices.*' => [
                'nullable',
                'regex:/^\d*(\.\d{1,2})?$/'
            ]
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(), 422);
        }
        $asset = AssetModel::addNewAsset($request->all());
        return $this->jsonResponse($asset);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required',
                function ($attribute, $value, $fail) {
                    $asset = AssetModel::where('id', '=', $value)->where('business_unit_id', '=', Auth::getBusinessUnit()->id)->get();
                    if (count($asset) == 0) {
                        $fail('Asset with ' . $attribute . ' ' . $value . ' is invalid or not belong to Business Unit .' . Auth::getBusinessUnit()->name);
                    }
                },
            ],
            'name' => 'nullable|string',
            'status' => ['nullable', function ($attribute, $value, $fail) {
                $statuses = Helper::collect('asset-status.json');

                if (empty($statuses->where('id', $value)->all())) {
                    $fail("Status  {$value} invalid.");
                }
            }],
            'cover_photo' => 'nullable|int|exists:media,id',
            'floor_photo' => 'nullable|int|exists:media,id',
            'asset_type_id' => 'nullable|int|exists:ass_type,id',
            'floor_id' => 'nullable|int|exists:ass_floor,id',
            'description' => 'nullable|string',
            'photos' => 'nullable|array|min:1',
            'prices.*' => [
                'nullable',
                'regex:/^\d*(\.\d{1,2})?$/'
            ]
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(), 422);
        }

        $asset = AssetModel::updateAsset($request->all());
        return $this->jsonResponse($asset);
    }

    public function search(Request $request)
    {
        return $this->jsonResponse(
            AssetModel::
            inWorkingBuilding()
                ->inMyOrganization()
                ->isVenue()
                ->with(['building', 'cover', 'background', 'prices'])
                ->paginate($request->per_page ?? 15));
    }

    public function searchMarketPlace(Request $request)
    {
//        var_dump(\Shura\Asset\Models\Asset::with('owner')->find(35)->toArray());exit;
        return $this->jsonResponse(
            AssetModel::
            with(['building', 'cover', 'background', 'prices', 'events', 'types', 'photos', 'owner'])
                ->whereNotNull('created_by')->where('created_by', '<>', 0)
                ->has('types')
                ->where('status', '=', 'published')
                ->paginate($request->per_page ?? 15));
    }

    public function detail(Request $request, $id)
    {
//        return AssetModel::with(['types'])->find($id);
        $validator = Validator::make(['id' => $id], [
            'id' => ['required',
                function ($attribute, $value, $fail) {
                    $asset = AssetModel::where('id', '=', $value)->where('building_id', '=', Helper::building()->id)->get();
                    if (count($asset) == 0) {
                        $fail('Asset with ' . $attribute . ' ' . $value . ' is invalid or not belong to Building .' . Helper::building()->name);
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(), 422);
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

    public function detailForMarket(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => ['required',
                function ($attribute, $value, $fail) {
                    $asset = AssetModel::where('id', '=', $value)->get();
                    if (count($asset) == 0) {
                        $fail('Asset with ' . $attribute . ' ' . $value . ' not found');
                    }
                },
            ]
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(), 422);
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

    public function addField(Request $request)
    {
        $data = $request->all();
        $data['model'] = 'asset';
        $validator = Validator::make($data, [
            'key' => 'required',
            'type' => 'required|in:number,string,text',
            'model' => 'required',
            'title' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(), 422);
        }

        return $this->jsonResponse(Field::addNewField($data));
    }
}
