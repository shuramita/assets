<?php

namespace Shura\Asset\Controllers\API;

use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Models\Venue;
use Illuminate\Validation\Rule;
use Validator;
class VenueController extends Controller
{
    public function add(Request $request) {
        $model = new Venue();
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'types'=>'required|array|min:1',
            'types.*'=>'required|int|exists:ven_static_data,id',
            'events'=>'required|array|min:1',
            'events.*'=>'required|int|exists:ven_static_data,id',
            'photos'=>'nullable|array|min:1',
            'photos.*'=>'nullable|int|exists:media,id',

        ]);
        if ($validator->fails()) {
            return $this->validationError('The submitted data is invalid',422,$validator->errors()->messages());
        }
        $venue = Venue::addNewVenue($request->all());
        return $this->jsonResponse(Venue::with(['types','events','photos','organization'])->find($venue->id));
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(),[
            'id'=>'required|int|min:1|exists:ven_venue'
        ]);
        if ($validator->fails()) {
            return $this->validationError('The submitted data is invalid',422,$validator->errors()->messages());
        }
    }
    public function info(Request $request, $venue){
        $validator = Validator::make(['id'=>$venue],[
            'id'=>'required|int|min:1|exists:ven_venue'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        return $this->jsonResponse(Venue::with(['types','events','photos','organization'])->find($venue));
    }

    public function search(Request $request) {
        return $this->jsonResponse(
            Venue::
                with(['types','events','photos','organization'])
                ->paginate($request->per_page ?? 15));
    }

}
