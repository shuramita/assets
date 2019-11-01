<?php

namespace Shura\Asset\Requests;

use App\Http\Requests\BaseAPIRequest;
use Core\Organization\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Shura\Asset\Models\Asset;

class AssetDetailRequest extends BaseAPIRequest
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
                        $fail('Asset with ' . $attribute . ' ' . $value . ' is invalid or not belong to Business .' . Auth::getBusinessUnit()->name);
                    }
                },
            ]
        ];
    }
    public function validationData()
    {
        return array_merge($this->request->all(), [
            'id' => Route::input('id'),
        ]);
    }

}
