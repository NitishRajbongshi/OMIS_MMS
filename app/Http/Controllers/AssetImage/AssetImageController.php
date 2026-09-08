<?php

namespace App\Http\Controllers\AssetImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AssetImageController extends Controller
{
    public function culvertImages(Request $request)
    {
        $asset_id = $request->id;
        $asset_type = $request->type;
        Log::info('ID:' . $asset_id);
        Log::info('Type:' . $asset_type);
        $url = "http://43.205.45.246:8085/getAssetImagesURL";
        // $url = config('customconfigpath.ASSET_IMAGES_URL');
        Log::info('URL:' . $url);
        // Define query parameters
        $queryParams = [
            'uid' => 20,
            'asset_cd' => $asset_id,
            'asset_type_cd' => $asset_type,
        ];

        // Make the HTTP GET request
        $response = Http::get($url, $queryParams);

        // Check if the request was successful
        if ($response->successful()) {
            // Decode the JSON response
            $data = $response->json();
            Log::info($data['img_url_list']);
            return response()->json($data);
        } else {
            Log::error('Failed to fetch images from external API', ['response' => $response->body()]);
            return response()->json(['error' => 'Failed to fetch images'], 500);
        }
    }
}
