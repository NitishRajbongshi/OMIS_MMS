<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinalizePCIController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize PCI Controller");
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
                    $division_cd =  $officeDivisionDtls->division_cd;
                    $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                }
            }
            // End
            if ($roadID != null) {
                $baseQuery = DB::table('asset_road_pavement_condition_indexes_draft')
                    ->select('*')
                    ->where('rd_system_id', '=', $roadID)
                    ->where('sent_for_finalize', '=', 'Y')
                    ->orderBy('updated_at', 'desc');
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
                    $surfaceTypeDetails = $baseQuery->whereIn('created_at_office_cd', $ZOOffices)->get();
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
                    $surfaceTypeDetails = $baseQuery->whereIn('created_at_office_cd', $COOffices)->get();
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
                    $surfaceTypeDetails = $baseQuery->whereIn('created_at_office_cd', $DOOffices)->get();
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
                    $surfaceTypeDetails = $baseQuery->whereIn('created_at_office_cd', $SDOffices)->get();
                }
                $query = DB::getQueryLog();
                Log::info($query);
                return view('road.finalize.pci', compact(
                    'user',
                    'roadID',
                    'surfaceTypeDetails'
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
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('asset_road_pavement_condition_indexes')->insertUsing([
                    'pci_section_cd',
                    'pci_section_length_in_meter',
                    'rd_system_id',
                    'chainage',
                    'cracking_percent',
                    'ravelling_percent',
                    'pot_holes_percent',
                    'shoving_percent',
                    'patching_percent',
                    'settlement_depression_percent',
                    'rut_depth',
                    'tot_motorized_traffic_per_day',
                    'tot_comm_veh_traffic_per_day',
                    'pv_traffic_light',
                    'pci_value',
                    'pci_remarks',
                    'created_by',
                    'updated_by',
                    'created_at',
                    'updated_at',
                    'created_at_office_cd',
                    'pci_year',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($roadID, $currentTime, $approvedBy) {
                    $query->from('asset_road_pavement_condition_indexes_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'pci_section_cd',
                            'pci_section_length_in_meter',
                            'rd_system_id',
                            'chainage',
                            'cracking_percent',
                            'ravelling_percent',
                            'pot_holes_percent',
                            'shoving_percent',
                            'patching_percent',
                            'settlement_depression_percent',
                            'rut_depth',
                            'tot_motorized_traffic_per_day',
                            'tot_comm_veh_traffic_per_day',
                            'pv_traffic_light',
                            'pci_value',
                            'pci_remarks',
                            'created_by',
                            'updated_by',
                            'created_at',
                            'updated_at',
                            'created_at_office_cd',
                            'pci_year',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('asset_road_pavement_condition_indexes_draft')
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

    public function acceptSinglePCIData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $pci_id = $request->id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('asset_road_pavement_condition_indexes')->insertUsing([
                    'pci_section_cd',
                    'pci_section_length_in_meter',
                    'rd_system_id',
                    'chainage',
                    'cracking_percent',
                    'ravelling_percent',
                    'pot_holes_percent',
                    'shoving_percent',
                    'patching_percent',
                    'settlement_depression_percent',
                    'rut_depth',
                    'tot_motorized_traffic_per_day',
                    'tot_comm_veh_traffic_per_day',
                    'pv_traffic_light',
                    'pci_value',
                    'pci_remarks',
                    'created_by',
                    'updated_by',
                    'created_at',
                    'updated_at',
                    'created_at_office_cd',
                    'pci_year',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($pci_id, $currentTime, $approvedBy) {
                    $query->from('asset_road_pavement_condition_indexes_draft')
                        ->where('pci_section_cd', '=', $pci_id)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'pci_section_cd',
                            'pci_section_length_in_meter',
                            'rd_system_id',
                            'chainage',
                            'cracking_percent',
                            'ravelling_percent',
                            'pot_holes_percent',
                            'shoving_percent',
                            'patching_percent',
                            'settlement_depression_percent',
                            'rut_depth',
                            'tot_motorized_traffic_per_day',
                            'tot_comm_veh_traffic_per_day',
                            'pv_traffic_light',
                            'pci_value',
                            'pci_remarks',
                            'created_by',
                            'updated_by',
                            'created_at',
                            'updated_at',
                            'created_at_office_cd',
                            'pci_year',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('asset_road_pavement_condition_indexes_draft')
                        ->where('pci_section_cd', $pci_id)
                        ->where('sent_for_finalize', 'Y')
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

    public function rejectSinglePCIData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $pci_id = $request->id;
                $status = DB::table('asset_road_pavement_condition_indexes_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('pci_section_cd', $pci_id)
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
