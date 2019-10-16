<?php
/**
 * Created by PhpStorm.
 * User: tamnguyen
 * Date: 18/12/2018
 * Time: 9:19 AM
 */

namespace RealEstateDoc\Asset\Helpers;


use function GuzzleHttp\Psr7\str;

class ImperialUnit
{
    public $value;
    public $unit = 'sqft';
    public $note;
    public $units = [
        'area' => ['sqft','sqmt','sqin','sqyd'],
        'signs'=>['.',',']
    ];
    public $thousand_sign = ',';
    public $decimal_sign = '.';
    /// sample format 24,324,345.45 sqft , .
    public function __construct(string $format)
    {
        // get 4 latest chars

        $format = explode(' ', $format);
        if(isset($format[1]) &&  in_array($format[1],$this->units['area'])) {
            $this->unit = $format[1];
        }else{
            $this->note = 'The format is not valid, the defaut unit fallback to sqft';
        }
        if(isset($format[2]) && in_array($format[2],$this->units['signs'])) {
            $this->thousand_sign = $format[2];
        }
        if(isset($format[3]) && in_array($format[3],$this->units['signs'])) {
            $this->decimal_sign = $format[3];
        }
        $this->value = $this->toFloat($format[0]);
    }
    public static function create(string $format) {
        return new ImperialUnit($format);
    }
    public function toFloat($value){
        return (float) str_replace($this->thousand_sign,'', $value);
    }
    public function toStringFormat(){
        return "$this->value $this->unit $this->thousand_sign $this->decimal_sign";
    }
}