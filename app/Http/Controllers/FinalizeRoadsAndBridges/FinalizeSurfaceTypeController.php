<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinalizeSurfaceTypeController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize Surface Type Controller");
        $this->middleware("auth");
    }

    public function index()
    {
        try {
            $user = Auth::user();
            $roadID = session('system_id');
            // In case of switching between the offices
            // start
            $office_cd = $user->office;
            $users_office_type_cd = $user->office_type_cd;
            $zone_cd = null;
            $circle_cd = null;
            $division_cd = null;
            $sub_division_cd = null;
            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();
            $zone_cd = $userMapping->zone_cd;
            $circle_cd = $userMapping->circle_cd;
            $division_cd = $userMapping->division_cd;
            $sub_division_cd = $userMapping->sub_division_cd;
            if (session('office_charge_type') == 1) {
                $office_cd = session('office_cd');
                $users_office_type_cd = session('users_office_type_cd');
                $officeDivisionDtls = DB::table('office_details as ofd')
                    ->select(
                        'ofd.zone_cd',
                        'ofd.circle_cd',
                        'ofd.division_cd',
                        'ofd.sub_division_cd'
                    )
                    ->where('ofd.id', '=', $office_cd)
                    ->get()->first();
                if ($officeDivisionDtls) {
                    $zone_cd = $officeDivisionDtls->zone_cd;
                    $circle_cd = $officeDivisionDtls->circle_cd;
                    $division_cd =  $officeDivisionDtls->division_cd;
                    $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                }
            }
            // End
            if ($roadID != null) {
                $baseQuery = DB::table('asset_road_surface_type_details_draft')
                    ->select(
                        'asset_road_surface_type_details_draft.*',
                        'asset_master_surface_type.surface_descr',
                        'asset_master_road_condition.rd_condition_descr',
                        'office_details.office_name',
                        'asset_master_base_layer_types.base_layer_type_descr',
                        'asset_master_sub_base_layer_types.sub_base_layer_type_descr',
                        'asset_master_pavement_types.pavement_type_descr',
                        'asset_master_shoulder_types.shoulder_type_descr',
                        "asset_master_maintenance_types.maintenance_type_descr",
                        'asset_master_drainage_types.drainage_descr'
                    )
                    ->leftJoin('asset_master_surface_type', 'asset_road_surface_type_details_draft.surface_type_cd', '=', 'asset_master_surface_type.surface_cd')
                    ->leftJoin('asset_master_road_condition', 'asset_road_surface_type_details_draft.surface_condition_cd', '=', 'asset_master_road_condition.rd_condition_cd')
                    ->leftJoin('office_details', 'asset_road_surface_type_details_draft.created_at_office_cd', '=', 'office_details.id')
                    ->leftJoin('asset_master_base_layer_types', 'asset_road_surface_type_details_draft.base_layer_type', '=', 'asset_master_base_layer_types.base_layer_type_cd')
                    ->leftJoin('asset_master_sub_base_layer_types', 'asset_road_surface_type_details_draft.sub_base_layer_type', '=', 'asset_master_sub_base_layer_types.sub_base_layer_type_cd')
                    ->leftJoin('asset_master_pavement_types', 'asset_road_surface_type_details_draft.pavement_type', '=', 'asset_master_pavement_types.pavement_type_cd')
                    ->leftJoin('asset_master_shoulder_types', 'asset_road_surface_type_details_draft.shoulder_type', '=', 'asset_master_shoulder_types.shoulder_type_cd')
                    ->leftJoin('asset_master_maintenance_types', 'asset_road_surface_type_details_draft.maintenance_type', '=', 'asset_master_maintenance_types.maintenance_type_cd')
                    ->leftJoin('asset_master_drainage_types', 'asset_road_surface_type_details_draft.drainage', '=', 'asset_master_drainage_types.drainage_cd')
                    ->where('asset_road_surface_type_details_draft.rd_system_id', '=', $roadID)
                    ->where('asset_road_surface_type_details_draft.sent_for_finalize', '=', 'Y')
                    ->orderBy('asset_road_surface_type_details_draft.updated_at', 'desc');
                if ($users_office_type_cd == 'HQ') {
                    $surfaceTypeDetails = $baseQuery->get();
                }
                if ($users_office_type_cd == 'ZO') {
                    $ZOOffices = [];
                    $offices = DB::table('office_details')
                        ->where('zone_cd', $zone_cd)
                        ->where('department_id', session('user_dept_cd'))
                        ->select('id')->get();
                    foreach ($offices as $item) {
                        $ZOOffices[] = $item->id;
                    }
                    $surfaceTypeDetails = $baseQuery->whereIn('asset_road_surface_type_details_draft.created_at_office_cd', $ZOOffices)->get();
                }
                if ($users_office_type_cd == 'CO') {
                    $COOffices = [];
                    $offices = DB::table('office_details')
                        ->where('circle_cd', $circle_cd)
                        ->where('department_id', session('user_dept_cd'))
                        ->select('id')->get();
                    foreach ($offices as $item) {
                        $COOffices[] = $item->id;
                    }
                    $surfaceTypeDetails = $baseQuery->whereIn('asset_road_surface_type_details_draft.created_at_office_cd', $COOffices)->get();
                }
                if ($users_office_type_cd == 'DO') {
                    $DOOffices = [];
                    $offices = DB::table('office_details')
                        ->where('division_cd', $division_cd)
                        ->where('department_id', session('user_dept_cd'))
                        ->select('id')->get();
                    foreach ($offices as $item) {
                        $DOOffices[] = $item->id;
                    }
                    $surfaceTypeDetails = $baseQuery->whereIn('asset_road_surface_type_details_draft.created_at_office_cd', $DOOffices)->get();
                }
                if ($users_office_type_cd == 'SDO') {
                    $SDOffices = [];
                    $offices = DB::table('office_details')
                        ->where('sub_division_cd', $sub_division_cd)
                        ->where('department_id', session('user_dept_cd'))
                        ->select('id')->get();
                    foreach ($offices as $item) {
                        $SDOffices[] = $item->id;
                    }
                    $surfaceTypeDetails = $baseQuery->whereIn('asset_road_surface_type_details_draft.created_at_office_cd', $SDOffices)->get();
                }
                $query = DB::getQueryLog();
                Log::info($query);
                return view('road.finalize.surfaceType', compact(
                    'roadID',
                    'user',
                    'surfaceTypeDetails',
                ));
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function store(Request $request)
    {
        try {
            if (isset($request->_token)) {
                $roadID = session('roadId');
                $createdBy = Auth::user()->id;
                $currentTime = now();

                // Copy data from student_table_draft to another_table
                $status = DB::table('asset_road_surface_type_details')->insertUsing([
                    'rd_system_id',
                    'rd_surface_cd',
                    'surface_condition_cd',
                    'start_chainage',
                    'end_chainage',
                    'created_at_office_cd',
                    'base_layer_type',
                    'base_layer_thickness',
                    'sub_base_layer_type',
                    'sub_base_layer_thickness',
                    'pavement_type',
                    'shoulder_type',
                    'land_slide',
                    'construction_year',
                    'surface_type_cd',
                    'surface_width',
                    'shoulder_width',
                    'base_cbr',
                    'base_pi',
                    'sub_base_cbr',
                    'sub_base_pi',
                    'maintenance_type',
                    'protection_wall',
                    'drainage',
                    'last_maintenance_date',
                    'land_slide_chainage',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by'
                ], function ($query) use ($roadID, $currentTime, $createdBy) {
                    $query->from('asset_road_surface_type_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'rd_system_id',
                            'rd_surface_cd',
                            'surface_condition_cd',
                            'start_chainage',
                            'end_chainage',
                            'created_at_office_cd',
                            'base_layer_type',
                            'base_layer_thickness',
                            'sub_base_layer_type',
                            'sub_base_layer_thickness',
                            'pavement_type',
                            'shoulder_type',
                            'land_slide',
                            'construction_year',
                            'surface_type_cd',
                            'surface_width',
                            'shoulder_width',
                            'base_cbr',
                            'base_pi',
                            'sub_base_cbr',
                            'sub_base_pi',
                            'maintenance_type',
                            'protection_wall',
                            'drainage',
                            'last_maintenance_date',
                            'land_slide_chainage',
                            DB::raw("'$currentTime' as created_at"),
                            DB::raw("'$currentTime' as updated_at"),
                            DB::raw("'$createdBy' as updated_by"),
                            DB::raw("'$createdBy' as created_by")
                        );
                });

                if ($status > 0) {
                    DB::table('asset_road_surface_type_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalize all data successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Data not available to finalize!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function acceptSingleSurfaceTypeData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $surfaceTypeID = $request->id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('asset_road_surface_type_details')->insertUsing([
                    'rd_system_id',
                    'rd_surface_cd',
                    'surface_condition_cd',
                    'start_chainage',
                    'end_chainage',
                    'created_at_office_cd',
                    'base_layer_type',
                    'base_layer_thickness',
                    'sub_base_layer_type',
                    'sub_base_layer_thickness',
                    'pavement_type',
                    'shoulder_type',
                    'land_slide',
                    'last_maintenance_date',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'construction_year',
                    'surface_type_cd',
                    'surface_width',
                    'shoulder_width',
                    'base_cbr',
                    'base_pi',
                    'sub_base_cbr',
                    'sub_base_pi',
                    'maintenance_type',
                    'drainage',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($surfaceTypeID, $currentTime, $approvedBy) {
                    $query->from('asset_road_surface_type_details_draft')
                        ->where('rd_surface_cd', '=', $surfaceTypeID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'rd_system_id',
                            'rd_surface_cd',
                            'surface_condition_cd',
                            'start_chainage',
                            'end_chainage',
                            'created_at_office_cd',
                            'base_layer_type',
                            'base_layer_thickness',
                            'sub_base_layer_type',
                            'sub_base_layer_thickness',
                            'pavement_type',
                            'shoulder_type',
                            'land_slide',
                            'last_maintenance_date',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            'construction_year',
                            'surface_type_cd',
                            'surface_width',
                            'shoulder_width',
                            'base_cbr',
                            'base_pi',
                            'sub_base_cbr',
                            'sub_base_pi',
                            'maintenance_type',
                            'drainage',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('asset_road_surface_type_details_draft')
                        ->where('rd_surface_cd', '=', $surfaceTypeID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalized all data successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Data not available to finalize!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function rejectSingleSurfaceTypeData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $surfaceTypeID = $request->id;
                $status = DB::table('asset_road_surface_type_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('rd_surface_cd', $surfaceTypeID)
                    ->update([
                        'is_rejected' => 'Y',
                        'reason_of_rejection' => $request->reason,
                        'date_of_rejection' => Carbon::now(),
                        'rejected_by' => Auth::user()->id,
                        'sent_for_finalize' => 'N',
                        'updated_at' => Carbon::now()
                    ]);

                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Data Rejected Successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Failed to reject due to some error!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }
}
