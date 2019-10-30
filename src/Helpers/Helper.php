<?php

namespace Shura\Asset\Helpers;


use Shura\Asset\Constants\Option;
use Shura\Asset\Constants\Status;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Shura\Asset\Models\User;

class Helper extends \App\Helpers\Helper
{
    public static $static_data_path = __DIR__.'/../database/static/';
}
