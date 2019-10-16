<?php

namespace Shura\Asset\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rule;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Customer;
use Shura\Asset\Models\Organization;
use Illuminate\Support\Facades\DB;
use Shura\Asset\Models\User;
use Validator;
class OrganizationController extends Controller
{
    public $namespace = 'Asset'; // registered in Service Provider

    public function add(Request $request){
        $model = new Organization();
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'location'=>['required',Rule::in($model->getLocationsAttribute()->map(function($item){
                return $item->id;
            }))],
            'currency'=>['required',Rule::in($model->getCurrenciesAttribute()->map(function($item){
                return $item->id;
            }))],
            'language'=>['nullable',Rule::in($model->getLanguagesAttribute()->map(function($item){
                return $item->id;
            }))],
            'timezone'=>['required',Rule::in($model->getTimezonesAttribute()->map(function($item){
                return $item->id;
            }))],
            'logo'=>'nullable|int|exists:media,id'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        $organization = Organization::addNewOrganization($request->all());
        $organization->logo = $organization->logo();
        return $this->jsonResponse($organization);
    }
    public function info(Request $request, $org){
//        var_dump($org);exit;
        $validator = Validator::make(['id'=>$org],[
            'id'=>'required|int|min:1|exists:ass_organization'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->messages(),422);
        }
        $organization = Organization::with('logo','buildings','prices','fields')->find($org);

        return $this->jsonResponse($organization);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'id'=>'required|numeric|min:1',
            'name'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->all());
        }
        $organization = Organization::updateOrganization($request->all());

        return $this->jsonResponse($organization,'organization updated');
    }

    public function change(Request $request, $organization) {
        $organization = Organization::find($organization);
        $organization->changeToOrganization();
        return $this->jsonResponse($organization);
    }

    public function customers(Request $request) {
        // find all customer who has a booking for the asset that belong to my organization :)

        return $this->jsonResponse(
            Customer::addSelect('*')
                    ->whereHas('bookings',function ($query) {
                        return $query->whereHas('asset',function($query){
                            return $query->where('building_id', '=', Helper::building()->id);
                        });
                    })
                    ->groupBy('email')
                    ->paginate($request->get('per_page',15))
        );
    }
}
