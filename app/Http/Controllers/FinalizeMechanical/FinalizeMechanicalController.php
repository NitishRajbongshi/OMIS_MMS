<?php

namespace App\Http\Controllers\FinalizeMechanical;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinalizeMechanicalController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        Log::info("Finalize Mechanical Controller");
    }

    public function finalizeVehicle()
    {
        try {
            $user = Auth::user();
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
            // end
            $baseVehicleQuery = DB::table('mechanicals.asset_mech_vehicles_details_draft AS mvd')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'mvd.vehicle_condition', '=', 'vc.condition_cd')
                ->leftJoin('mechanicals.asset_master_fuel_types AS ft', 'mvd.fuel_type', '=', 'ft.fuel_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_types AS vt', 'mvd.vehicle_type', '=', 'vt.veh_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_makers AS vm', 'mvd.maker', '=', 'vm.maker_cd')
                ->leftJoin('office_details AS ofis', 'mvd.created_at_office_cd', '=', 'ofis.id')
                ->select('mvd.*', DB::raw('DATE(mvd.alloted_from) as alloted_from_date'), 'vc.condition_descr', 'ft.fuel_type_descr', 'vt.veh_type_descr', 'vm.maker_name', 'ofis.office_name')
                ->where('mvd.sent_for_finalize', "Y")
                ->orderByDesc('mvd.updated_at');

            if ($users_office_type_cd == 'HQ') {
                $draftVehicleDetails = $baseVehicleQuery->get();
            }

            if ($users_office_type_cd == 'ZO') {
                $ZOOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('zone_cd', $zone_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $ZOOffices[] = $item->id;
                }
                $draftVehicleDetails = $baseVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $ZOOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'CO') {
                $COOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('circle_cd', $circle_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $COOffices[] = $item->id;
                }
                $draftVehicleDetails = $baseVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $COOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'DO') {
                $DOOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('division_cd', $division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $DOOffices[] = $item->id;
                }
                $draftVehicleDetails = $baseVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $DOOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'SDO') {
                $SDOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('sub_division_cd', $sub_division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $SDOffices[] = $item->id;
                }
                $draftVehicleDetails = $baseVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $SDOffices)
                    ->get();
            }

            return view('mechanical.finalize.vehicle', compact(
                'user',
                'draftVehicleDetails',
            ));
        } catch (QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function acceptVehicle(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $vehicleID = $request->id;
                $currentTime = now();
                $approvedBy = Auth::user()->id;
                $status = DB::table('mechanicals.asset_mech_vehicles_details')->insertUsing([
                    'vehicle_asset_cd',
                    'vehicle_regn_no',
                    'chassis_no',
                    'engine_no',
                    'vehicle_type',
                    'seating_capacity',
                    'no_of_wheels',
                    'maker',
                    'model',
                    'fuel_type',
                    'date_of_purchase',
                    'purchase_cost',
                    'vehicle_condition',
                    'laden_weight',
                    'unladen_weight',
                    'vehicle_name',
                    'created_at_office_cd',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'remarks',
                    'alloted_to',
                    'alloted_from',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($vehicleID, $currentTime, $approvedBy) {
                    $query->from('mechanicals.asset_mech_vehicles_details_draft')
                        ->where('vehicle_asset_cd', '=', $vehicleID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'vehicle_asset_cd',
                            'vehicle_regn_no',
                            'chassis_no',
                            'engine_no',
                            'vehicle_type',
                            'seating_capacity',
                            'no_of_wheels',
                            'maker',
                            'model',
                            'fuel_type',
                            'date_of_purchase',
                            'purchase_cost',
                            'vehicle_condition',
                            'laden_weight',
                            'unladen_weight',
                            'vehicle_name',
                            'created_at_office_cd',
                            'created_by',
                            'created_at',
                            'updated_at',
                            'remarks',
                            'alloted_to',
                            'alloted_from',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });
                if ($status > 0) {
                    DB::table('mechanicals.asset_mech_vehicles_details_draft')
                        ->where('vehicle_asset_cd', '=', $vehicleID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalized the vehicle data successfully'
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
                    'message' => 'Unothorized Access!'
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

    public function rejectVehicle(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $vehicleID = $request->id;

                $status = DB::table('mechanicals.asset_mech_vehicles_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('vehicle_asset_cd', $vehicleID)
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
                        'message' => 'Vehicle data rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Failed to reject vehicle data!'
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

    public function finalizeEquipment()
    {
        try {
            $user = Auth::user();
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
            $baseEquipmentQuery = DB::table('mechanicals.asset_mech_equipment_details_draft AS med')
                ->leftJoin('mechanicals.asset_master_equipment_conditions AS vc', 'med.equipment_condition_cd', '=', 'vc.condition_cd')
                ->leftJoin('office_details AS ofis', 'med.created_at_office_cd', '=', 'ofis.id')
                ->select('med.*', 'vc.condition_descr', 'ofis.office_name')
                ->where('med.sent_for_finalize', "Y")
                ->orderByDesc('med.updated_at');

            if ($users_office_type_cd == 'HQ') {
                $draftEquipmentDetails = $baseEquipmentQuery->get();
            }

            if ($users_office_type_cd == 'ZO') {
                $ZOOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('zone_cd', $zone_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $ZOOffices[] = $item->id;
                }
                $draftEquipmentDetails = $baseEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $ZOOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'CO') {
                $COOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('circle_cd', $circle_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $COOffices[] = $item->id;
                }
                $draftEquipmentDetails = $baseEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $COOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'DO') {
                $DOOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('division_cd', $division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $DOOffices[] = $item->id;
                }
                $draftEquipmentDetails = $baseEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $DOOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'SDO') {
                $SDOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('sub_division_cd', $sub_division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $SDOffices[] = $item->id;
                }
                $draftEquipmentDetails = $baseEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $SDOffices)
                    ->get();
            }

            return view('mechanical.finalize.equipment', compact(
                'user',
                'draftEquipmentDetails',
            ));
        } catch (QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function acceptEquipment(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $equipmentID = $request->id;
                $currentTime = now();
                $approvedBy = Auth::user()->id;
                $status = DB::table('mechanicals.asset_mech_equipment_details')->insertUsing([
                    'euipment_cd',
                    'equipment_name',
                    'serial_number',
                    'model_no',
                    'purchase_year',
                    'purchase_cost',
                    'equipment_condition_cd',
                    'is_under_waranty',
                    'created_at_office_cd',
                    'equipment_remarks',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($equipmentID, $currentTime, $approvedBy) {
                    $query->from('mechanicals.asset_mech_equipment_details_draft')
                        ->where('sent_for_finalize', '=', 'Y')
                        ->where('euipment_cd', $equipmentID)
                        ->select(
                            'euipment_cd',
                            'equipment_name',
                            'serial_number',
                            'model_no',
                            'purchase_year',
                            'purchase_cost',
                            'equipment_condition_cd',
                            'is_under_waranty',
                            'created_at_office_cd',
                            'equipment_remarks',
                            'created_at',
                            'updated_at',
                            'created_by',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('mechanicals.asset_mech_equipment_details_draft')
                        ->where('euipment_cd', '=', $equipmentID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalized the equipment data successfully'
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
                    'message' => 'Unothorized Access!'
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

    public function rejectEquipment(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $equipmentID = $request->id;
                $status = DB::table('mechanicals.asset_mech_equipment_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('euipment_cd', $equipmentID)
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
                        'message' => 'Equipment data rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Failed to reject equipment data!'
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
