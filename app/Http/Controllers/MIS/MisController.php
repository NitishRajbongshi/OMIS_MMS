<?php

namespace App\Http\Controllers\MIS;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class MisController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    // Filter road by district code
    public function filterRoadByDistrict(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {

                $district_name = DB::table('asset_master_lgd_district')
                    ->select('dist_name')
                    ->where('dist_code', $request->id)
                    ->first();

                // $districtName = Str::ucfirst($district_name->dist_name);
                // return $district_name->dist_name;

                $roadDetails = DB::table('asset_road_details')
                    ->select('asset_road_details.rd_system_id', 'asset_road_details.rd_number', 'asset_road_details.rd_name', 'asset_road_details.road_length', 'asset_road_details.district_name', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    // ->where('asset_road_details.district_name', $districtName)
                    ->Where('asset_road_details.district_name', $district_name->dist_name)
                    ->orderBy('created_at', 'desc')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter road by zonal code
    public function filterRoadByZone(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.block_name',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereIn('rd_system_id', function ($query) use ($request) {
                        $query->select('rd_system_id')
                            ->distinct()
                            ->from('asset_road_chainage_mappings')
                            ->where('zone_cd', $request->id);
                    })
                    ->orderByDesc('updated_at')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter road by circle code
    public function filterRoadByCircle(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.block_name',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereIn('rd_system_id', function ($query) use ($request) {
                        $query->select('rd_system_id')
                            ->distinct()
                            ->from('asset_road_chainage_mappings')
                            ->where('circle_cd', $request->id);
                    })
                    ->orderByDesc('updated_at')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter road by division code
    public function filterRoadByDivision(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.block_name',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereIn('rd_system_id', function ($query) use ($request) {
                        $query->select('rd_system_id')
                            ->distinct()
                            ->from('asset_road_chainage_mappings')
                            ->where('division_cd', $request->id);
                    })
                    ->orderByDesc('updated_at')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter road by division code
    public function filterRoadByDivisionName(Request $request)
    {
        try {
            if ((isset($request->division)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.block_name',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->where('road_type', 'SR')
                    ->where('division_name', 'like', '%' . $request->division . '%')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter road by division code
    public function filterRoadByDivisionNameUsingChainage(Request $request)
    {
        try {
            Log::info('Division Code:' . $request->division);
            if ((isset($request->division)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_chainage_mappings as arc')
                    ->join('asset_road_details as ard', 'arc.rd_system_id', '=', 'ard.rd_system_id')
                    ->where('arc.division_cd', $request->division)
                    ->select('ard.rd_system_id', 'ard.rd_name')
                    ->get();
                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter NH by division code
    public function filterNHByDivisionName(Request $request)
    {
        try {
            if ((isset($request->division)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.block_name',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->where('road_type', 'NH')
                    ->where('division_name', 'like', '%' . $request->division . '%')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    // Filter road by SubDivision code
    public function filterRoadBySubDivision(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.block_name',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereIn('rd_system_id', function ($query) use ($request) {
                        $query->select('rd_system_id')
                            ->distinct()
                            ->from('asset_road_chainage_mappings')
                            ->where('sub_division_cd', $request->id);
                    })
                    ->orderByDesc('updated_at')
                    ->get();

                if ($roadDetails) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Road data fetched successfully!',
                        'result' => $roadDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }
}
