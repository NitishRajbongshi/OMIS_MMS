<?php

namespace App\Http\Controllers\Api\V1\Maintenance\SubAsset;

use App\Http\Controllers\Controller;
use App\Services\Maintenance\RoadSubAssetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoadSubAsetsApiController extends Controller
{
    public function __construct(
        private RoadSubAssetService $roadSubAssetService
    ) {}

    /**
     * Get sub-assets for a road.
     */
    public function index(string $roadId, string $subAssetCd)
    {
        $data = $this->roadSubAssetService->getSubAssets(
            $roadId,
            $subAssetCd
        );
        return response()->json([
            'success' => true,
            'message' => $data->isEmpty()
                ? 'No sub-assets found for this road.'
                : 'Sub-assets fetched successfully.',
            'data' => $data,
        ]);
    }
}
