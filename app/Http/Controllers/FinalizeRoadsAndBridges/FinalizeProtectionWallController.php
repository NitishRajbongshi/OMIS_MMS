<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinalizeProtectionWallController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize Protection Wall Controller");
        $this->middleware("auth");
    }

    public function index(Request $request)
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
                    $division_cd = $officeDivisionDtls->division_cd;
                    $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                }
            }
            // End
            $baseQuery = DB::table('asset_protection_wall_details_draft')
                ->select(
                    'asset_protection_wall_details_draft.*',
                    'asset_master_protection_wall_type.wall_type_descr',
                    'asset_master_protection_wall_structure_type.structure_type_descr',
                    //Saiful -- 05-05-2026 -- Start
                    'pp.project_cd'
                    //Saiful -- 05-05-2026 -- End
                )
                ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details_draft.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
                ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details_draft.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
                ->leftJoin('prt_project_asset_plan as pp', 'pp.id', '=', 'asset_protection_wall_details_draft.asset_plan_id')//saiful 05-05-2026
                ->where('asset_protection_wall_details_draft.rd_system_id', '=', $roadID)
                ->where('asset_protection_wall_details_draft.sent_for_finalize', '=', 'Y')
                ->orderBy('asset_protection_wall_details_draft.updated_at', 'desc');
            if ($users_office_type_cd == 'HQ') {
                $protectionWallDetails = $baseQuery->get();
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
                $protectionWallDetails = $baseQuery->whereIn('asset_protection_wall_details_draft.created_at_office_cd', $ZOOffices)->get();
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
                $protectionWallDetails = $baseQuery->whereIn('asset_protection_wall_details_draft.created_at_office_cd', $COOffices)->get();
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
                $protectionWallDetails = $baseQuery->whereIn('asset_protection_wall_details_draft.created_at_office_cd', $DOOffices)->get();
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
                $protectionWallDetails = $baseQuery->whereIn('asset_protection_wall_details_draft.created_at_office_cd', $SDOffices)->get();
            }
            $query = DB::getQueryLog();
            Log::info($query);
            return view('road.finalize.protectionWall', compact(
                'roadID',
                'user',
                'protectionWallDetails',
            ));
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
            if ($request->header('X-CSRF-TOKEN')) {
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('asset_protection_wall_details')->insertUsing([
                    'protection_wall_cd',
                    'rd_system_id',
                    'chainage',
                    'wall_type_cd',
                    'structure_type_cd',
                    'bottom_width',
                    'top_width',
                    'length',
                    'height',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    'created_at_office_cd',
                    'remarks',
                    'year_of_construction',
                    'year_of_renovation',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($currentTime, $approvedBy) {
                    $query->from('asset_protection_wall_details_draft')
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'protection_wall_cd',
                            'rd_system_id',
                            'chainage',
                            'wall_type_cd',
                            'structure_type_cd',
                            'bottom_width',
                            'top_width',
                            'length',
                            'height',
                            'created_at',
                            'updated_at',
                            'updated_by',
                            'created_by',
                            'created_at_office_cd',
                            'remarks',
                            'year_of_construction',
                            'year_of_renovation',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('asset_protection_wall_details_draft')
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

    public function acceptSingleProtectionWallData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $protectionWallId = $request->id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                //saiful # 05-05-2026 # Start
                //check if same bridge code exist in main table or not, if exist then update otherwise insert
                $existingProtecWall = DB::table('asset_protection_wall_details')
                    ->where('protection_wall_cd', $protectionWallId)
                    ->first();
                if ($existingProtecWall) {
                    return $this->handleApprovalOfExistingProtectionWall($protectionWallId, $approvedBy, $currentTime);
                } else {
                    //saiful # 05-05-2026 # End
                    $status = DB::table('asset_protection_wall_details')->insertUsing([
                        'protection_wall_cd',
                        'rd_system_id',
                        'chainage',
                        'wall_type_cd',
                        'structure_type_cd',
                        'bottom_width',
                        'top_width',
                        'length',
                        'height',
                        'created_at',
                        'updated_at',
                        'updated_by',
                        'created_by',
                        'created_at_office_cd',
                        'remarks',
                        'year_of_construction',
                        'year_of_renovation',
                        'approved_by',
                        'approved_at'
                    ], function ($query) use ($protectionWallId, $currentTime, $approvedBy) {
                        $query->from('asset_protection_wall_details_draft')
                            ->where('protection_wall_cd', '=', $protectionWallId)
                            ->where('sent_for_finalize', '=', 'Y')
                            ->select(
                                'protection_wall_cd',
                                'rd_system_id',
                                'chainage',
                                'wall_type_cd',
                                'structure_type_cd',
                                'bottom_width',
                                'top_width',
                                'length',
                                'height',
                                'created_at',
                                'updated_at',
                                'updated_by',
                                'created_by',
                                'created_at_office_cd',
                                'remarks',
                                'year_of_construction',
                                'year_of_renovation',
                                DB::raw("'$approvedBy' as approved_by"),
                                DB::raw("'$currentTime' as approved_at")
                            );
                    });

                    if ($status > 0) {
                        DB::table('asset_protection_wall_details_draft')
                            ->where('protection_wall_cd', '=', $protectionWallId)
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
                    //saiful # 05-05-2026 # Start
                }
                //saiful # 05-05-2026 # End

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
    //saiful # 29-04-2026 # Start
    private function handleApprovalOfExistingProtectionWall($protecWallCD, $approvedBy, $currentTime)
    {
        Log::info("Protection Wall with ID $protecWallCD already exists. Updating existing record.");
        $protecWallInfo = DB::table('asset_protection_wall_details_draft')
            ->where('protection_wall_cd', '=', $protecWallCD)
            ->where('sent_for_finalize', '=', 'Y')
            ->first();
        $status = DB::table('asset_protection_wall_details_hist')->insertUsing([
            'protection_wall_cd',
            'rd_system_id',
            'chainage',
            'wall_type_cd',
            'structure_type_cd',
            'bottom_width',
            'top_width',
            'length',
            'height',
            'created_at',
            'updated_at',
            'updated_by',
            'created_by',
            'created_at_office_cd',
            'remarks',
            'year_of_construction',
            'year_of_renovation',
            'asset_plan_id',
            'approved_by',
            'approved_at',
            'lat',
            'lon',
            'remarks_hist',
            'hist_created_by',
            'hist_created_at'
        ], function ($query) use ($protecWallCD, $currentTime, $approvedBy) {
            $query->from('asset_protection_wall_details')
                ->where('protection_wall_cd', '=', $protecWallCD)
                ->select(
                    'protection_wall_cd',
                    'rd_system_id',
                    'chainage',
                    'wall_type_cd',
                    'structure_type_cd',
                    'bottom_width',
                    'top_width',
                    'length',
                    'height',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    'created_at_office_cd',
                    'remarks',
                    'year_of_construction',
                    'year_of_renovation',
                    'asset_plan_id',
                    'approved_by',
                    'approved_at',
                    'lat',
                    'lon',
                    DB::raw("'Asset Redefined by Project' as remarks_hist"),
                    DB::raw("'$approvedBy' as hist_created_by"),
                    DB::raw("'$currentTime' as hist_created_on")
                );
        });
        if ($status > 0) {
            $status = DB::table('public.asset_protection_wall_details')
                ->where('protection_wall_cd', '=', $protecWallCD)
                ->update([
                    'chainage' => $protecWallInfo->chainage ?? null,
                    'wall_type_cd' => $protecWallInfo->wall_type_cd ?? null,
                    'structure_type_cd' => $protecWallInfo->structure_type_cd ?? null,
                    'bottom_width' => $protecWallInfo->bottom_width ?? null,
                    'top_width' => $protecWallInfo->top_width ?? null,
                    'length' => $protecWallInfo->length ?? null,
                    'height' => $protecWallInfo->height ?? null,
                    'remarks' => $protecWallInfo->remarks ?? null,
                    'year_of_construction' => $protecWallInfo->year_of_construction,
                    'year_of_renovation' => $protecWallInfo->year_of_renovation,
                    'asset_plan_id' => $protecWallInfo->asset_plan_id,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'lat' => $protecWallInfo->lat,
                    'lon' => $protecWallInfo->lon,
                    'updated_at' => now(),
                    'updated_by' => auth()->id()

                ]);
            DB::table('asset_protection_wall_details_draft')
                ->where('protection_wall_cd', '=', $protecWallCD)
                ->where('sent_for_finalize', '=', 'Y')
                ->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Finalize data successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 204,
                'message' => 'No data available to finalize.'
            ], 204);
        }
    }
    //saiful # 29-04-2026 # End
    public function rejectSingleProtectionWallData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $protectionWallID = $request->id;
                $status = DB::table('asset_protection_wall_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('protection_wall_cd', $protectionWallID)
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
