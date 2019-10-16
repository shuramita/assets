<?php

namespace Shura\Asset\Controllers\API;

use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Models\Floor as FloorModel;
use Illuminate\Validation\Rule;
use Validator;
class Floor extends Controller
{
    public function add(Request $request) {
        $model = new FloorModel();
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'code'=>'nullable|string',
            'photo_id'=>'nullable|int|exists:media,id'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        $building = FloorModel::addNewFloor($request->all());
        return $this->jsonResponse(FloorModel::with(['building'])->find($building->id));
    }

    public function info(Request $request, $floor){
        $validator = Validator::make(['id'=>$floor],[
            'id'=>'required|int|min:1|exists:ass_floor'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        return $this->jsonResponse(FloorModel::with(['building'])->find($floor));
    }
    public function update(Request $request) {

    }


}
