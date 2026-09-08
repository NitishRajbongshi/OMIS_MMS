<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Mechanical\AssetMechEquipmentDetail;
use App\Models\Mechanical\AssetMechVehicalsDetail;
use Exception;

class ChangeStatusOfAssetsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function listOfAssetToChangeStatus()
    {
        DB::enableQueryLog();
        $user_dept_cd = session('user_dept_cd');
        $userMapping = session('userMapping');
        $array = json_decode(json_encode($userMapping), true);

        $user_zone_cd = $array["zone_cd"];
        $user_circle_cd = $array["circle_cd"];
        $user_division_cd = $array["division_cd"];
        $user_sub_division_cd = $array["sub_division_cd"];
        $user_office_type_cd = $array["office_type_cd"];
        $user_office_cd = $array["office_cd"];

        $asset_details = null;
        $asset_details_1 = null;
        $office_cds = null;
        $arr_ofis_cds = null;
        if ($user_office_type_cd == "SDO") {
            Log::info("Check SDO");
            $office_cds = DB::table('office_details AS ofs_m')
                ->select("ofs_m.id")
                ->where("ofs_m.sub_division_cd", $user_sub_division_cd)
                ->where("ofs_m.division_cd", $user_division_cd)
                ->where("ofs_m.circle_cd", $user_circle_cd)
                ->where("ofs_m.zone_cd", $user_zone_cd)
                ->where("ofs_m.department_id", $user_dept_cd)
                ->get();
        }

        if ($user_office_type_cd == "DO") {
            Log::info("Check DO");
            $office_cds = DB::table('office_details AS ofs_m')
                ->select("ofs_m.id")
                ->where("ofs_m.division_cd", $user_division_cd)
                ->where("ofs_m.circle_cd", $user_circle_cd)
                ->where("ofs_m.zone_cd", $user_zone_cd)
                ->where("ofs_m.department_id", $user_dept_cd)
                ->get();
        }

        if ($user_office_type_cd == "CO") {
            Log::info("Check CO");
            $office_cds = DB::table('office_details AS ofs_m')
                ->select("ofs_m.id")
                ->where("ofs_m.circle_cd", $user_circle_cd)
                ->where("ofs_m.zone_cd", $user_zone_cd)
                ->where("ofs_m.department_id", $user_dept_cd)
                ->get();
        }

        if ($user_office_type_cd == "ZO") {
            Log::info("Check ZO");
            $office_cds = DB::table('office_details AS ofs_m')
                ->select("ofs_m.id")
                ->where("ofs_m.zone_cd", $user_zone_cd)
                ->where("ofs_m.department_id", $user_dept_cd)
                ->get();
        }

        if ($office_cds) {
            $arr_ofis_cds = json_decode(json_encode($office_cds), true);
        }
        $query = DB::getQueryLog();
        Log::info(end($query));

        Log::info($arr_ofis_cds);
        if ($user_dept_cd == 15) // Mechanical
        {
            $veh_cond_m = DB::table('mechanicals.asset_master_vehicle_conditions AS veh_cnd_m')
                ->orderBy('veh_cnd_m.condition_descr', 'asc')
                ->get();

            $eqp_cond_m = DB::table('mechanicals.asset_master_equipment_conditions AS eqp_cnd_m')
                ->orderBy('eqp_cnd_m.condition_descr', 'asc')
                ->get();

            $baseQuery = DB::table('mechanicals.asset_mech_equipment_details AS eqp')
                ->select("eqp.*", "cond_m.condition_descr")
                ->join("mechanicals.asset_master_equipment_conditions as cond_m", "eqp.equipment_condition_cd", "=", "cond_m.condition_cd")
                ->orderBy('eqp.created_at_office_cd', 'desc');

            if ($user_office_type_cd == "SDO" || $user_office_type_cd == "DO" || $user_office_type_cd == "CO" || $user_office_type_cd == "ZO") {
                $baseQuery->wherein("eqp.created_at_office_cd", $arr_ofis_cds);
            }

            $asset_details = $baseQuery->get();

            $baseQuery = DB::table('mechanicals.asset_mech_vehicles_details AS veh')
                ->select("veh.*", "cond_m.condition_descr")
                ->join("mechanicals.asset_master_vehicle_conditions as cond_m", "veh.vehicle_condition", "=", "cond_m.condition_cd")
                ->orderBy('veh.created_at_office_cd', 'desc');

            if ($user_office_type_cd == "SDO" || $user_office_type_cd == "DO" || $user_office_type_cd == "CO" || $user_office_type_cd == "ZO") {
                $baseQuery->wherein("veh.created_at_office_cd", $arr_ofis_cds);
            }

            $asset_details_1 = $baseQuery->get();

            $query = DB::getQueryLog();
            Log::info(end($query));
        }
        return view("admin.AssetStatusChange", compact('asset_details', 'asset_details_1', 'user_dept_cd', 'veh_cond_m', 'eqp_cond_m'));
    }

    public function updateAssetStatus(Request $request)
    {
        Log::info("updateAssetStatus Controller");
        try {
            DB::enableQueryLog();
            $asset_table_name = $request->asset_table_name;
            $asset_cd = $request->asset_cd;
            $new_asset_condition = $request->new_value_cd;
            $main_tables_data = null;
            $uid = Auth::user()->id;
            $currentTime = now();
            if ($asset_table_name == "vehicles") {
                $main_tables_data = AssetMechVehicalsDetail::find($asset_cd);

                if ($main_tables_data) {
                    //Copy the Old Record To History Table
                    $status_move_data = DB::table('mechanicals.asset_mech_vehicles_details_hist')->insertUsing([
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
                        'hist_created_by',
                        'hist_remarks',
                        'hist_created_at'

                    ], function ($query) use ($asset_cd, $currentTime, $uid) {
                        $query->from('mechanicals.asset_mech_vehicles_details')
                            ->where('vehicle_asset_cd', '=', $asset_cd)
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
                                DB::raw("'$uid' as hist_created_by"),
                                DB::raw("'Modify Vehicle Condition' as hist_remarks"),
                                DB::raw("'$currentTime' as hist_created_at")
                            );
                    });
                    if ($status_move_data > 0) {
                        Log::info("Vehcile Asset Data copied to History Table with vehicle_asset_cd : " . $asset_cd);
                    }

                    $main_tables_data->vehicle_condition = $new_asset_condition;
                    $status = $main_tables_data->save();

                    if ($status) {
                        return response()->json([
                            'message' => 'success'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'failed'
                        ]);
                    }
                } else {
                    Log::Info("No Road Data Data found with with Vehicle Asset CD: " . $asset_cd);
                    return response()->json([
                        'message' => 'failed'
                    ]);
                }
            }

            if ($asset_table_name == "equipment") {
                $main_tables_data = AssetMechEquipmentDetail::find($asset_cd);

                if ($main_tables_data) {
                    //Copy the Old Record To History Table
                    $status_move_data = DB::table('mechanicals.asset_mech_equipment_details_hist')->insertUsing([
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
                        'created_by',
                        'created_at',
                        'hist_created_by',
                        'hist_remarks',
                        'hist_created_at'

                    ], function ($query) use ($asset_cd, $currentTime, $uid) {
                        $query->from('mechanicals.asset_mech_equipment_details')
                            ->where('euipment_cd', '=', $asset_cd)
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
                                'created_by',
                                'created_at',
                                DB::raw("'$uid' as hist_created_by"),
                                DB::raw("'Modify Vehicle Condition' as hist_remarks"),
                                DB::raw("'$currentTime' as hist_created_at")
                            );
                    });
                    if ($status_move_data > 0) {
                        Log::info("Equipment Asset Data copied to History Table with euipment_cd : " . $asset_cd);
                    }

                    $main_tables_data->equipment_condition_cd = $new_asset_condition;
                    $status = $main_tables_data->save();

                    if ($status) {
                        return response()->json([
                            'message' => 'success'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'failed'
                        ]);
                    }
                } else {
                    Log::Info("No Road Data Data found with with Vehicle Asset CD: " . $asset_cd);
                    return response()->json([
                        'message' => 'failed'
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }
}