<?php

namespace Shura\Asset\Middleware;

use Closure;
use Shura\Asset\Controllers\AssetResponse;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\User;
class CheckEnviroment
{
    use AssetResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$env)
    {
        $api = in_array('api', $env);
        $organization = in_array('organization', $env);
        $building = in_array('building', $env);
        if(($organization && empty(Helper::org())) || (!empty(Helper::org()) && $building && empty(Helper::building()))) {
            if($api) {
                return $this->validationError('Please setup your organization or building or maybe switch to other organization before call this api before execute this api',422);
            }else{
                return redirect()->route('asset.angular.index');
            }
        }
        return $next($request);
    }
}
