<?php

namespace Shura\Asset\Controllers\API;

use Core\Organization\Facades\Auth;
use Illuminate\Http\Request;
use Shura\Asset\Controllers\Controller;
use Shura\Asset\Helpers\Helper;
use Shura\Asset\Models\Asset;
use Shura\Asset\Requests\AssetDetailRequest;
use Shura\Asset\Requests\CreateAssetRequest;
use Shura\Asset\Requests\UpdateAssetRequest;
use Validator;
use Illuminate\Validation\Rule;
use Shura\Asset\Models\Price;
use Shura\Asset\Models\Field;

/**
 * @group Asset Module
 *
 * */
class AssetController extends Controller
{
    /**
     * Add new asset
     * User can add new asset
     * @authenticated
     * @bodyParam name string require The name of asset Example: The location A in floor 1
     * @bodyParam status string option The status of asset, can be null or one of value default **draft** or **unpublished**, **published**   Example: published
     */
    public function add(CreateAssetRequest $request)
    {
        $asset = Asset::addNewAsset($request->validated());
        return $this->jsonResponse($asset);
    }

    /**
     * Update asset
     * User can update asset
     * @authenticated
     * @bodyParam name string require The name of asset Example: The location A in floor 1
     * @bodyParam status string option The status of asset, can be null or one of value default **draft** or **unpublished**, **published**   Example: published
     */
    public function update(UpdateAssetRequest $request)
    {
        $asset = Asset::updateAsset($request->all());
        return $this->jsonResponse($asset);
    }

    /**
     * Get Asset Detail
     * User can get asset detail
     * @authenticated
     * @bodyParam id int require The id of asset Example: 1
     */
    public function detail(AssetDetailRequest $request, $id)
    {
        $asset = Asset::with(['floor', 'building', 'type', 'prices', 'owner'])->find($id);
        return $this->jsonResponse($asset);
    }

    /**
     * Search Assets
     * User can get asset detail
     * @authenticated
     * @bodyParam id int require The id of asset Example: 1
     */
    public function search(Request $request)
    {
        return $this->jsonResponse(
            Asset::query()
                ->with(['building', 'prices'])
                ->paginate($request->per_page ?? 15));
    }

}
