<?php

namespace RealEstateDoc\Asset\Middleware;

use Closure;
use RealEstateDoc\Asset\Controllers\AssetResponse;
use RealEstateDoc\Asset\Helpers\Helper;
use RealEstateDoc\Asset\Models\User;
class Asset
{
    use AssetResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $organization, $building)
    {
        $organization = Helper::org();
        if(empty($organization)) {
            if($request->ajax()) {
                return $this->jsonResponse([],'Please setup your organization');
            }else{
                return redirect()->route('asset.angular.index');
            }
        }
        return $next($request);
    }
}
