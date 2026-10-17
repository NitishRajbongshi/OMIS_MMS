<?php

namespace App\Http\Controllers\Api\V1\Maintenance\AssetDetails;

use App\Http\Controllers\Controller;
use App\Services\Maintenance\AssetDetails\AssetDetailService;
use Illuminate\Http\Request;

class AssetDetailsController extends Controller
{
    public function __construct(
        private AssetDetailService $assetDetailService
    ) {}

    public function index(string $assetType, string $assetId)
    {
        $data = $this->assetDetailService->getAssetDetails(
            $assetType,
            $assetId
        );
        return response()->json([
            'success' => true,
            'message' => $data->isEmpty()
                ? 'No asset details found for this asset.'
                : 'Asset details fetched successfully.',
            'data' => $data,
        ]);
    }
}
