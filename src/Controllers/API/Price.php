<?php

namespace RealEstateDoc\Asset\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Builder\Class_;
use RealEstateDoc\Asset\Controllers\AssetResponse;
use RealEstateDoc\Asset\Controllers\Controller;
use RealEstateDoc\Asset\Helpers\Helper;
use Validator;
use Illuminate\Validation\Rule;
use RealEstateDoc\Asset\Models\Price as PriceModel;
use RealEstateDoc\Asset\Models\Asset;
class Price extends Controller
{
    public function add(Request $request) {
        $price_option = Helper::getJsonFromStaticData('price_option.json');
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'type'=> [
                'required',
                Rule::in($price_option->type),
            ],
            'unit'=> [
                'required',
                Rule::in($price_option->unit),
            ],
            'available_at'=> [
                'required',
                Rule::in($price_option->available_at),
            ],
            'range'=>'nullable|array'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator->errors()->getMessages(),422);
        }

        $price = PriceModel::addNewPrice($request->all());

        return $this->jsonResponse($price);

    }

    public function update(Request $request) {

    }
    /* Calculate price of one or more asset with one or more period of time

    */
    public function calculate(Request $request){

        $params = [
            (object) [
                "asset_id"=>8,
                "time"=>[
                    (object) [
                        "from"=> Carbon::now()->addDays(10)->toIso8601String(),
                        "to"=>Carbon::now()->addDays(30)->toIso8601String()
                    ],
                    (object) [
                        "from"=> Carbon::now()->addDays(11)->toIso8601String(),
                        "to"=>Carbon::now()->addDays(20)->toIso8601String()
                    ]
                ]
            ],
            (object) [
                "asset_id"=>9,
                "time"=>[
                    (object) [
                        "from"=> Carbon::now()->addDays(10)->toIso8601String(),
                        "to"=>Carbon::now()->addDays(20)->toIso8601String()
                    ],
                ]
            ]
        ];
        foreach ($params as &$param) {
            $asset = Asset::with('prices')->find($param->asset_id);
            $param->prices = $asset->prices ?? null;
            $param->caculated = [];
            foreach ($param->time as $period) {
                $price = $asset->calculatePrice($period->from,$period->to);
                $param->caculated[] = $price;
            }
        }
        $data = [
            "input"=>$params
        ];

        return $this->jsonResponse($data);
    }
}
