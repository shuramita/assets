<?php

namespace RealEstateDoc\Asset\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    public $namespace = 'Asset'; // registered in Service Provider

    use AssetResponse;
}
