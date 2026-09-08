<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AssetRoadDistressDetailsController extends Controller
{
    public function getDistressDetails(Request $request)
    {
        try {
            if (isset($request->id)) {
                DB::enableQueryLog();
                Log::info('Distress Details Controller: ');
                $distressDetails = DB::table('asset_road_distress_details')
                    ->select('asset_road_distress_details.*', 'asset_road_details.rd_name', 'asset_master_distress_type.distress_type_descr')
                    ->leftJoin('asset_road_details', 'asset_road_distress_details.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->leftJoin('asset_master_distress_type', 'asset_road_distress_details.distress_type_cd', '=', 'asset_master_distress_type.distress_type_cd')
                    ->where('asset_road_distress_details.rd_distress_cd', '=', $request->id)
                    ->get()
                    ->first();
                $query = DB::getQueryLog();
                Log::info($query);

                if ($distressDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Distress data fetch successfully!',
                        'result' => $distressDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Census code is not available',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error in getting getDistressDetails: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function getDistressImagesOld(Request $request)
    {
        try {
            $assetCd = $request->id;
            DB::enableQueryLog();
            Log::info('Distress Details Controller: ');
            $distressDetails = DB::table('asset_road_distress_details as distress')
                ->leftJoin('asset_road_details as road', 'distress.rd_system_id', '=', 'road.rd_system_id')
                ->where('distress.rd_distress_cd', '=', $assetCd)
                ->select('distress.distress_remarks', 'road.rd_name')
                ->get()->first();

            $query = DB::getQueryLog();
            Log::info($query);


            $distressImageEndpoint = config('customconfigpath.DISTRESS_IMAGES');
            $client = new Client();
            $response = $client->get($distressImageEndpoint, [
                'query' => [
                    // 'uid' => '1',
                    'asset_cd' => $assetCd,
                    'asset_type_cd' => '11',
                ],
                // 'verify' => 'C:/xampp/php/extras/ssl/cacert.pem'
                'verify' => false,
            ]);
            // Get the response body as a string
            $responseBody = $response->getBody()->getContents();
            // Decode the JSON response
            $data = json_decode($responseBody, true);
            $imageLists = $data['img_url_list'];
            Log::info("Image Lists: ", $imageLists);
            return view('distress.showImage', compact('imageLists', 'distressDetails'));
        } catch (Exception $e) {
            Log::error("Error in getting getDistressImages: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function getDistressImages(Request $request)
    {
        try {
            $assetCd = $request->id;
            DB::enableQueryLog();
            $distressDetails = DB::table('asset_road_distress_details as distress')
                ->leftJoin('asset_road_details as road', 'distress.rd_system_id', '=', 'road.rd_system_id')
                ->where('distress.rd_distress_cd', '=', $assetCd)
                ->select('distress.distress_remarks', 'road.rd_name')
                ->first();
            $query = DB::getQueryLog();
            Log::info($query);

            $distressImageEndpoint = config('customconfigpath.DISTRESS_IMAGES');
            $client = new Client();

            $response = $client->get($distressImageEndpoint, [
                'query' => [
                    'asset_cd' => $assetCd,
                    'asset_type_cd' => '11',
                ],
                'verify' => false,
            ]);

            $responseBody = $response->getBody()->getContents();
            $data = json_decode($responseBody, true);

            if (!isset($data['img_url_list'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image list not found in response',
                    'data' => $data
                ], 500);
            }

            $imageLists = $data['img_url_list'];
            Log::info("Image Lists: ", $imageLists);

            return response()->json([
                'status' => 'success',
                'imageLists' => $imageLists,
                'distressDetails' => $distressDetails,
            ]);
        } catch (Exception $e) {
            Log::error("Error in getDistressImages: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the distress images.',
            ], 500);
        }
    }
}
