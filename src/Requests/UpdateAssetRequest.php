<?php

namespace Shura\Asset\Requests;

use App\Http\Requests\BaseAPIRequest;
use Core\Organization\Facades\Auth;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Asset;

class UpdateAssetRequest extends BaseAPIRequest
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
            'id' => ['required',
                function ($attribute, $value, $fail) {
                    $asset = Asset::where('id', '=', $value)->where('business_unit_id', '=', Auth::getBusinessUnit()->id)->get();
                    if (count($asset) == 0) {
                        $fail('Asset with ' . $attribute . ' ' . $value . ' is invalid or not belong to Business Unit .' . Auth::getBusinessUnit()->name);
                    }
                },
            ],
            'name' => 'required|string',
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
        ];
    }

}
