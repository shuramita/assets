<?php

namespace Shura\Asset\Requests;

use App\Http\Requests\BaseRequest;

class CreateAssetRequest extends BaseRequest
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
            'dsfd' => 'required|unique:posts|max:255'
        ];
    }

}
