<?php

namespace Shura\Asset\Controllers\API;

use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Models\Building as BuildingModel;
use Illuminate\Validation\Rule;
use Validator;
class Building extends Controller
{
    public function add(Request $request) {
        $model = new BuildingModel();
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'building_type'=>['nullable',Rule::in($model->getTypesAttribute()->map(function($item){
                return $item->id;
            }))],
            'logo_id'=>'nullable|int|exists:media,id'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        $building = BuildingModel::addNewBuilding($request->all());
        return $this->jsonResponse(BuildingModel::with(['logo','organization','floors'])->find($building->id));
    }

    public function info(Request $request, $building){
        $validator = Validator::make(['id'=>$building],[
            'id'=>'required|int|min:1|exists:ass_building'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        return $this->jsonResponse(BuildingModel::with(['logo','organization','floors'])->find($building));
    }
    public function update(Request $request) {

    }


}
