<?php
/**
 * Created by PhpStorm.
 * User: tamnguyen
 * Date: 03/01/2019
 * Time: 10:16 AM
 */

namespace Shura\Asset\Models\Trails;


use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait PriceCalculation
{
    public function calculatePrice($f, $t) {
        $from = new Carbon($f);
        $to = new Carbon($t);
        $period = CarbonPeriod::createFromArray([$from,$to]);
        $total = [];
        // loop each price
        foreach ($this->sortPriceByPriority() as $price) {
            // try to caculate the pricce from priority
            if($this->fallIntoThisPrice($price, $period)) {
                //
                $f = "get".ucfirst($price->unit)."OfPeriod";
                // IMPORTANT, CAN ONLY CALCULATE ONE TIME
                $number = $this->$f($period, $price);
                if($number > 0) {
                    $total[] = [
                        "unit" => $price->unit ,
                        "number" => $number ,
                        "in"=>$price->available_at ,
                        "price"=>  $price->pivot->price ,
                        "value" => $price->pivot->price * $number
                    ];
                }

            }
        }
        return $total;
    }
    protected function sortPriceByPriority(){
        return $this->prices->sortBy('priority');
    }
    protected function fallIntoThisPrice($price, CarbonPeriod & $period) {
//        $unit = $price->unit;
        // it mean the period have lenght > a price unit
        return true;
    }
    protected function getHourlyOfPeriod(CarbonPeriod $period, $price=null) {
        return $period->getEndDate()->diffInHours($period->getStartDate());
    }

    protected function getDailyOfPeriod(CarbonPeriod & $period, $price=null) {
        $number = 0;
        if($price->available_at == 'all') {
            $number =  $period->getEndDate()->diffInDays($period->getStartDate());
        }elseif($price->available_at == 'dateInWeek') {
            /* @var $day Carbon*/
            foreach ($period as $day) {
                if(in_array($day->dayOfWeek, $price->range)) {
                    $number = $number + 1;
                }
            }
        }
        // TODO: add exclude day range for period
        $period->setStartDate($period->getStartDate()->addDays($number));

        return $number;
    }
    protected function getNightlyOfPeriod(CarbonPeriod $period, $price=null) {
        return $period->getEndDate()->diffInDays($period->getStartDate());
    }

    protected function getWeeklyOfPeriod(CarbonPeriod & $period, $price=null) {
        $number =  $period->getEndDate()->diffInWeeks($period->getStartDate());
        $period->setStartDate($period->getStartDate()->addWeeks($number));
        return $number;
    }

    protected function getMonthlyOfPeriod(CarbonPeriod $period, $price=null) {
        return $period->getEndDate()->diffInMonths($period->getStartDate());
    }

    protected function getQuartlyOfPeriod(CarbonPeriod $period, $price=null) {
        return 0;
    }

    protected function getYearlyOfPeriod(CarbonPeriod $period, $price=null) {
        return $period->getEndDate()->diffInYears($period->getStartDate());
    }

}
