<?php

namespace Shura\Asset\Requests;

use App\Http\Requests\BaseAPIRequest;
use Illuminate\Validation\Rule;
use Shura\Asset\Helpers\Helper;

class CreateAssetRequest extends BaseAPIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
        ];
    }

}
