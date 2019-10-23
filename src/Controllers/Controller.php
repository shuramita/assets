<?php

namespace Shura\Asset\Controllers;

use App\Http\Controllers\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    public $namespace = 'Asset'; // registered in Service Provider

}
