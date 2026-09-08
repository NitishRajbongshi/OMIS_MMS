<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinalizeHabitationController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize Habitation Controller");
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
                    ->select('ofd.zone_cd', 'ofd.circle_cd', 'ofd.division_cd', 'ofd.sub_division_cd')
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
            if ($roadID != null) {
                $baseQuery = DB::table('asset_road_habitation_details_draft')
                    ->select(
                        'asset_road_habitation_details_draft.*',
                        'asset_master_lgd_district.dist_name as district',
                        'asset_master_block.block_name as block',
                        'asset_master_village.village_name as village',
                        'asset_master_mla_const.const_descr as mla',
                        'asset_master_mp_const.const_desc as mp'
                    )
                    ->leftJoin('asset_master_lgd_district', 'asset_road_habitation_details_draft.district_name', '=', 'asset_master_lgd_district.dist_code')
                    ->leftJoin('asset_master_block', 'asset_road_habitation_details_draft.block_name', '=', 'asset_master_block.block_cd')
                    ->leftJoin('asset_master_village', 'asset_road_habitation_details_draft.village_name', '=', 'asset_master_village.village_code')
                    ->leftJoin('asset_master_mla_const', 'asset_road_habitation_details_draft.mla_constituency', '=', 'asset_master_mla_const.const_cd')
                    ->leftJoin('asset_master_mp_const', 'asset_road_habitation_details_draft.mp_constituency', '=', 'asset_master_mp_const.const_cd')
                    ->where('asset_road_habitation_details_draft.rd_system_id', '=', $roadID)
                    ->where('asset_road_habitation_details_draft.sent_for_finalize', '=', 'Y')
                    ->orderBy('asset_road_habitation_details_draft.updated_at', 'desc');
                if ($users_office_type_cd == 'HQ') {
                    $habitationDetails = $baseQuery->get();
                    $habitationFacilities = DB::table('asset_road_habitation_facility_details_draft as hf')
                        ->select(
                            'hf.id',
                            'hf.facility_id',
                            'hf.sub_facility_id',
                            'hf.habitation_cd',
                            'f.facility_name',
                            'sf.sub_facility_name'
                        )
                        ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                        ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                        ->get()
                        ->groupBy('habitation_cd');
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
                    $habitationDetails = $baseQuery->whereIn('asset_road_habitation_details_draft.created_at_ofis_cd', $ZOOffices)->get();
                    $habitationCds = $habitationDetails->pluck('habitation_cd');
                    $habitationFacilities = DB::table('asset_road_habitation_facility_details_draft as hf')
                        ->select(
                            'hf.id',
                            'hf.facility_id',
                            'hf.sub_facility_id',
                            'hf.habitation_cd',
                            'f.facility_name',
                            'sf.sub_facility_name'
                        )
                        ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                        ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                        ->whereIn('hf.habitation_cd', $habitationCds)
                        ->get()
                        ->groupBy('habitation_cd');
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
                    $habitationDetails = $baseQuery->whereIn('asset_road_habitation_details_draft.created_at_ofis_cd', $COOffices)->get();

                    $habitationCds = $habitationDetails->pluck('habitation_cd');
                    $habitationFacilities = DB::table('asset_road_habitation_facility_details_draft as hf')
                        ->select(
                            'hf.id',
                            'hf.facility_id',
                            'hf.sub_facility_id',
                            'hf.habitation_cd',
                            'f.facility_name',
                            'sf.sub_facility_name'
                        )
                        ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                        ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                        ->whereIn('hf.habitation_cd', $habitationCds)
                        ->get()
                        ->groupBy('habitation_cd');


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
                    $habitationDetails = $baseQuery->whereIn('asset_road_habitation_details_draft.created_at_ofis_cd', $DOOffices)->get();

                    $habitationCds = $habitationDetails->pluck('habitation_cd');
                    $habitationFacilities = DB::table('asset_road_habitation_facility_details_draft as hf')
                        ->select(
                            'hf.id',
                            'hf.facility_id',
                            'hf.sub_facility_id',
                            'hf.habitation_cd',
                            'f.facility_name',
                            'sf.sub_facility_name'
                        )
                        ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                        ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                        ->whereIn('hf.habitation_cd', $habitationCds)
                        ->get()
                        ->groupBy('habitation_cd');

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
                    $habitationDetails = $baseQuery->whereIn('asset_road_habitation_details_draft.created_at_ofis_cd', $SDOffices)->get();

                    $habitationCds = $habitationDetails->pluck('habitation_cd');
                    $habitationFacilities = DB::table('asset_road_habitation_facility_details_draft as hf')
                        ->select(
                            'hf.id',
                            'hf.facility_id',
                            'hf.sub_facility_id',
                            'hf.habitation_cd',
                            'f.facility_name',
                            'sf.sub_facility_name'
                        )
                        ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                        ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                        ->whereIn('hf.habitation_cd', $habitationCds)
                        ->get()
                        ->groupBy('habitation_cd');


                }
                $query = DB::getQueryLog();
                Log::info($query);
                return view('road.finalize.habitation', compact(
                    'roadID',
                    'user',
                    'habitationDetails',
                    'habitationFacilities'
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
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('asset_road_habitation_details')->insertUsing([
                    'habitation_cd',
                    'rd_system_id',
                    'district_name',
                    'block_name',
                    'village_name',
                    'chainage',
                    'administrative_center',
                    'market_facility',
                    'health_center',
                    'educational_institution',
                    'list_of_monuments',
                    'no_of_intersections',
                    'no_of_terrain',
                    'no_of_reserve_forest',
                    'no_of_sanctuary',
                    'no_of_lakes',
                    'no_of_tourist_spots',
                    'mla_constituency',
                    'mp_constituency',
                    'total_population',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'remarks',
                    'created_at_ofis_cd',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($currentTime, $approvedBy) {
                    $query->from('asset_road_habitation_details_draft')
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'habitation_cd',
                            'rd_system_id',
                            'district_name',
                            'block_name',
                            'village_name',
                            'chainage',
                            'administrative_center',
                            'market_facility',
                            'health_center',
                            'educational_institution',
                            'list_of_monuments',
                            'no_of_intersections',
                            'no_of_terrain',
                            'no_of_reserve_forest',
                            'no_of_sanctuary',
                            'no_of_lakes',
                            'no_of_tourist_spots',
                            'mla_constituency',
                            'mp_constituency',
                            'total_population',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            'remarks',
                            'created_at_ofis_cd',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('asset_road_habitation_details_draft')
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

    public function acceptSingleHabitationData(Request $request)
    {
        // try {
        $response = DB::transaction(function () use ($request) {
            if ($request->header('X-CSRF-TOKEN')) {
                $habitationID = $request->id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('asset_road_habitation_details')->insertUsing([
                    'habitation_cd',
                    'rd_system_id',
                    'district_name',
                    'block_name',
                    'village_name',
                    'chainage',
                    'administrative_center',
                    'market_facility',
                    'health_center',
                    'educational_institution',
                    'list_of_monuments',
                    'no_of_intersections',
                    'no_of_terrain',
                    'no_of_reserve_forest',
                    'no_of_sanctuary',
                    'no_of_lakes',
                    'no_of_tourist_spots',
                    'mla_constituency',
                    'mp_constituency',
                    'total_population',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'remarks',
                    'created_at_ofis_cd',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($habitationID, $currentTime, $approvedBy) {
                    $query->from('asset_road_habitation_details_draft')
                        ->where('habitation_cd', '=', $habitationID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'habitation_cd',
                            'rd_system_id',
                            'district_name',
                            'block_name',
                            'village_name',
                            'chainage',
                            'administrative_center',
                            'market_facility',
                            'health_center',
                            'educational_institution',
                            'list_of_monuments',
                            'no_of_intersections',
                            'no_of_terrain',
                            'no_of_reserve_forest',
                            'no_of_sanctuary',
                            'no_of_lakes',
                            'no_of_tourist_spots',
                            'mla_constituency',
                            'mp_constituency',
                            'total_population',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            'remarks',
                            'created_at_ofis_cd',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });


                $statusFacilities = DB::table('asset_road_habitation_facility_details')->insertUsing([
                    'habitation_cd',
                    'facility_id',
                    'sub_facility_id',
                    'created_at'
                ], function ($query) use ($habitationID, $currentTime, $approvedBy) {
                    $query->from('asset_road_habitation_facility_details_draft')
                        ->where('habitation_cd', '=', $habitationID)
                        ->select(
                            'habitation_cd',
                            'facility_id',
                            'sub_facility_id',
                            DB::raw("'$currentTime' as created_at")
                        );
                });

                if ($statusFacilities > 0) {
                    DB::table('asset_road_habitation_facility_details_draft')
                        ->where('habitation_cd', '=', $habitationID)
                        ->delete();
                }

                if ($status > 0) {
                    DB::table('asset_road_habitation_details_draft')
                        ->where('habitation_cd', '=', $habitationID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    return [
                        'status' => 200,
                        'message' => 'Habitation Data Approved successfully!'
                    ];
                }
                return [
                    'status' => 204,
                    'message' => 'Data not available to Approve!'
                ];
            }
            return [
                'status' => 401,
                'message' => 'Unauthorized Access'
            ];
        });
        return response()->json($response);
        // } catch (Exception $e) {
        //     Log::error("message: " . $e->getMessage());
        //     return response()->json([
        //         'status' => 500,
        //         'message' => 'Internal server error!'
        //     ]);
        // }
    }

    public function rejectSingleHabitationData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $habitationID = $request->id;
                $status = DB::table('asset_road_habitation_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('habitation_cd', $habitationID)
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
