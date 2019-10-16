<?php

namespace Shura\Asset\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shura\Asset\Helpers\Helper as AssetHelper;

class Currency extends Model
{
    protected $table = 'ass_setting';

    protected $hidden = [];

    public static function seedCurrencyToDatabase($organization_id){
        $currencies = json_decode(AssetHelper::getStaticData('currency.json'));
        foreach ($currencies as $static_currency) {
            $currency = new Currency();
            $currency->key = $static_currency->id;
            $currency->value = $static_currency->value;
            $currency->model = 'currency';
            $currency->group = 'none';
            $currency->title = 'currency';
            $currency->description = 'currency';
            $currency->type = 'string';

            $currency->organization_id = $organization_id;
            $currency->save();
        }
    }
}
