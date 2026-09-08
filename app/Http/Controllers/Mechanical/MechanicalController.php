<?php

namespace App\Http\Controllers\Mechanical;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AssetMasterDocumentCategory;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Mechanical\AssetMasterFuelType;
use App\Models\Mechanical\AssetMasterVehicleType;
use App\Models\Mechanical\AssetVehicleDocumentDetail;
use App\Models\Mechanical\AssetMasterVehicleCondition;
use App\Models\Mechanical\AssetEquipmentDocumentDetail;
use App\Models\Mechanical\AssetMechEquipmentDetail;
use App\Models\Mechanical\AssetMechVehicalsDetailsDraft;
use App\Models\Mechanical\AssetMechEquipmentDetailsDraft;
use App\Models\Mechanical\AssetMechVehicalsDetail;
use App\Models\Mechanical\Master\AssetMasterVehicleMaker;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class MechanicalController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info('Mechanical Controller');
    }

    public function index()
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
            $baseEquipmentQuery =  DB::table('mechanicals.asset_mech_equipment_details_draft AS med')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'med.equipment_condition_cd', '=', 'vc.condition_cd')
                ->select('med.*', 'vc.condition_descr')
                ->where('med.sent_for_finalize', "N");

            $baseFinalEquipmentQuery = DB::table('mechanicals.asset_mech_equipment_details AS med')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'med.equipment_condition_cd', '=', 'vc.condition_cd')
                ->select('med.*', 'vc.condition_descr')
                ->orderBy('med.updated_at', 'desc');

            if ($users_office_type_cd == 'HQ') {
                $draftEquipmentDetails = $baseEquipmentQuery->get();
                $finalEquipmentDetails = $baseFinalEquipmentQuery->get();
            } else if ($users_office_type_cd == 'ZO') {
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
                $finalEquipmentDetails = $baseFinalEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $ZOOffices)
                    ->get();
            } else if ($users_office_type_cd == 'CO') {
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
                $finalEquipmentDetails = $baseFinalEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $COOffices)
                    ->get();
            } else if ($users_office_type_cd == 'DO') {
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
                $finalEquipmentDetails = $baseFinalEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $DOOffices)
                    ->get();
            } else if ($users_office_type_cd == 'SDO') {
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
                $finalEquipmentDetails = $baseFinalEquipmentQuery
                    ->whereIn('med.created_at_office_cd', $SDOffices)
                    ->get();
            } else {
                $draftEquipmentDetails = $baseEquipmentQuery->get();
                $finalEquipmentDetails = $baseFinalEquipmentQuery->get();
            }
            $conditions = AssetMasterVehicleCondition::all();
            $query = DB::getQueryLog();
            Log::info($query);

            return view('mechanical.viewEquipment', compact(
                'user',
                'draftEquipmentDetails',
                'finalEquipmentDetails',
                'conditions'
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

    public function viewVehicle()
    {
        try {
            Log::info('Inside viewVehicle method of Mechanical controller');
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
            $fuelTypes = AssetMasterFuelType::all();
            $vehTypes = AssetMasterVehicleType::all();
            $conditions = AssetMasterVehicleCondition::all();
            $vehMakers = AssetMasterVehicleMaker::all();
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
                ->select('mvd.*', DB::raw('DATE(mvd.alloted_from) as alloted_from_date'), 'vc.condition_descr', 'ft.fuel_type_descr', 'vt.veh_type_descr', 'vm.maker_name')
                ->where('mvd.sent_for_finalize', "N");

            $baseFinalVehicleQuery = DB::table('mechanicals.asset_mech_vehicles_details AS mvd')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'mvd.vehicle_condition', '=', 'vc.condition_cd')
                ->leftJoin('mechanicals.asset_master_fuel_types AS ft', 'mvd.fuel_type', '=', 'ft.fuel_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_types AS vt', 'mvd.vehicle_type', '=', 'vt.veh_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_makers AS vm', 'mvd.maker', '=', 'vm.maker_cd')
                ->select('mvd.*', DB::raw('DATE(mvd.alloted_from) as alloted_from_date'), 'vc.condition_descr', 'ft.fuel_type_descr', 'vt.veh_type_descr', 'vm.maker_name')
                ->orderBy('mvd.updated_at', 'desc');
            if ($users_office_type_cd == 'HQ') {
                $draftVehicleDetails = $baseVehicleQuery->get();
                $finalVehicleDetails = $baseFinalVehicleQuery->get();
            } else if ($users_office_type_cd == 'ZO') {
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
                $finalVehicleDetails = $baseFinalVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $ZOOffices)
                    ->get();
            } else if ($users_office_type_cd == 'CO') {
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
                $finalVehicleDetails = $baseFinalVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $COOffices)
                    ->get();
            } else if ($users_office_type_cd == 'DO') {
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
                $finalVehicleDetails = $baseFinalVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $DOOffices)
                    ->get();
            } else if ($users_office_type_cd == 'SDO') {
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
                $finalVehicleDetails = $baseFinalVehicleQuery
                    ->whereIn('mvd.created_at_office_cd', $SDOffices)
                    ->get();
            } else {
                $draftVehicleDetails = $baseVehicleQuery->get();
                $finalVehicleDetails = $baseFinalVehicleQuery->get();
            }

            $query = DB::getQueryLog();
            Log::info($query);

            return view('mechanical.viewVehicle', compact(
                'user',
                'draftVehicleDetails',
                'finalVehicleDetails',
                'fuelTypes',
                'vehTypes',
                'conditions',
                'vehMakers'
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

    public function addVehicle()
    {
        try {
            Log::info('Calling addVehicle() method of Mechanical controller');
            $user = Auth::user();
            $fuelTypes = AssetMasterFuelType::all();
            $vehTypes = AssetMasterVehicleType::all();
            $conditions = AssetMasterVehicleCondition::all();
            $vehMakers = AssetMasterVehicleMaker::all();
            $vehicleDetails = DB::table('mechanicals.asset_mech_vehicles_details_draft AS mvd')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'mvd.vehicle_condition', '=', 'vc.condition_cd')
                ->leftJoin('mechanicals.asset_master_fuel_types AS ft', 'mvd.fuel_type', '=', 'ft.fuel_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_types AS vt', 'mvd.vehicle_type', '=', 'vt.veh_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_makers AS vm', 'mvd.maker', '=', 'vm.maker_cd')
                ->select(
                    'mvd.*',
                    DB::raw('DATE(mvd.alloted_from) as alloted_from_date'),
                    'vc.condition_descr',
                    'ft.fuel_type_descr',
                    'vt.veh_type_descr',
                    'vm.maker_name'
                )
                ->where('mvd.sent_for_finalize', "N")
                ->Where('mvd.created_at_office_cd', $user->office)
                ->get();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('mechanical.addVahicles', compact(
                'user',
                'fuelTypes',
                'vehTypes',
                'conditions',
                'vehicleDetails',
                'vehMakers'
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

    public function storeVehicle(Request $request)
    {
        $validator = $request->validate([
            'vehicle_regn_no' => 'required|string|max:20',
            'vehicle_name' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:30',
            'engine_no' => 'required|string|max:30',
            'vehicle_type' => 'required|string|max:5',
            // 'seating_capacity' => 'required|integer',
            // 'no_of_wheels' => 'required|integer',
            // 'maker' => 'required|string|max:255',
            // 'model' => 'required|string|max:255',
            // 'fuel_type' => 'required|string|max:5',
            'date_of_purchase' => 'required|date',
            'purchase_cost' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/'
            ],
            // 'vehicle_condition' => 'required|string|max:5',
            // 'laden_weight' => 'required|numeric',
            // 'unladen_weight' => 'required|numeric',
            // 'alloted_to' => 'required|string|max:255',
            // 'alloted_from' => 'required|date',
            'remarks' => 'nullable',
        ]);
        try {
            Log::info("Calling method to store vehicle details.");
            $user = Auth::user();
            $random = rand(10000, 99999);
            $commonData = [
                'vehicle_asset_cd' => $random,
                'vehicle_regn_no' => $request->vehicle_regn_no,
                'chassis_no' => $request->chassis_no,
                'engine_no' => $request->engine_no,
                'vehicle_type' => $request->vehicle_type,
                'seating_capacity' => $request->seating_capacity,
                'no_of_wheels' => $request->no_of_wheels,
                'maker' => $request->maker,
                'model' => $request->model,
                'fuel_type' => $request->fuel_type,
                'date_of_purchase' => $request->date_of_purchase,
                'purchase_cost' => $request->purchase_cost,
                'vehicle_condition' => $request->vehicle_condition,
                'laden_weight' => $request->laden_weight,
                'unladen_weight' => $request->unladen_weight,
                'vehicle_name' => $request->vehicle_name,
                'created_at_office_cd' => $user->office,
                'created_by' => $user->id,
                'remarks' => $request->remarks,
                'alloted_to' => $request->alloted_to,
                'alloted_from' => $request->alloted_from,
            ];
            // get the maker checker status for sub assets
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('6');
            if ($makerCheckerStatus == 'Y')
                $status = AssetMechVehicalsDetailsDraft::create($commonData);
            else {
                $extraData = [
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),

                ];
                $status = AssetMechVehicalsDetail::create(array_merge($commonData, $extraData));
            }

            if ($status) {
                // upload doc
                $this->handleDocumentVehicle($request, $random);
                $query = DB::getQueryLog();
                Log::info($query);
                return redirect()->back()
                    ->with('success', 'Vehicle added successfully with code ' . $random);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to Insert Vehicle');
            }
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

    private function handleDocumentVehicle($request, $random)
    {
        $configPath = config('customconfigpath.VEHICLE_DOCS_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('workorder')) {
            $file = $request->file('workorder');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Work Order')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetVehicleDocumentDetail::create([
                'vehicle_asset_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('design_doc')) {
            $file = $request->file('design_doc');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Design Document')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_2.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetVehicleDocumentDetail::create([
                'vehicle_asset_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('sanction_order')) {
            $file = $request->file('sanction_order');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Sanction Order')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_3.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetVehicleDocumentDetail::create([
                'vehicle_asset_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('inspection_report')) {
            $file = $request->file('inspection_report');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Inspection Report (Last)')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_4.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetVehicleDocumentDetail::create([
                'vehicle_asset_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }
    }

    public function editDraftVehicleData(Request $request)
    {
        try {
            $asset_cd = $request->veh_asset_cd;
            $deletedBy = Auth::user()->id;
            $deletedTime = now();
            $vehDraftData = AssetMechVehicalsDetailsDraft::find($asset_cd);
            if ($vehDraftData) {
                $storeHistStatus = DB::table('mechanicals.asset_mech_vehicles_details_draft_hist')->insertUsing([
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
                    'remarks',
                    'alloted_to',
                    'alloted_from',
                    'sent_for_finalize',
                    'sent_for_finalize_on',
                    'sent_for_finalize_by',
                    'is_rejected',
                    'reason_of_rejection',
                    'date_of_rejection',
                    'rejected_by',
                    'created_at_office_cd',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'hist_created_by',
                    'hist_remarks',
                    'hist_created_at'
                ], function ($query) use ($asset_cd, $deletedTime, $deletedBy) {
                    $query->from('mechanicals.asset_mech_vehicles_details_draft')
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
                            'remarks',
                            'alloted_to',
                            'alloted_from',
                            'sent_for_finalize',
                            'sent_for_finalize_on',
                            'sent_for_finalize_by',
                            'is_rejected',
                            'reason_of_rejection',
                            'date_of_rejection',
                            'rejected_by',
                            'created_at_office_cd',
                            'created_by',
                            'created_at',
                            'updated_at',
                            DB::raw("'$deletedBy' as hist_created_by"),
                            DB::raw("'Edited by $deletedBy at $deletedTime' as hist_remarks"),
                            DB::raw("'$deletedTime' as hist_created_at")
                        );
                });

                if ($storeHistStatus) {
                    $vehDraftData->vehicle_asset_cd = $asset_cd;
                    $vehDraftData->vehicle_regn_no = $request->vehicle_regn_no;
                    $vehDraftData->chassis_no = $request->chassis_no;
                    $vehDraftData->engine_no = $request->engine_no;
                    $vehDraftData->vehicle_type = $request->vehicle_type;
                    $vehDraftData->seating_capacity = $request->seating_capacity;
                    $vehDraftData->no_of_wheels = $request->no_of_wheels;
                    $vehDraftData->maker = $request->maker;
                    // $vehDraftData->model = $request->model;
                    $vehDraftData->fuel_type = $request->fuel_type;
                    $vehDraftData->date_of_purchase = $request->date_of_purchase;
                    $vehDraftData->purchase_cost = $request->purchase_cost;
                    $vehDraftData->vehicle_condition = $request->vehicle_condition;
                    $vehDraftData->laden_weight = $request->laden_weight;
                    $vehDraftData->unladen_weight = $request->unladen_weight;
                    $vehDraftData->vehicle_name = $request->vehicle_name;
                    $vehDraftData->remarks = $request->remarks;
                    $vehDraftData->alloted_to = $request->alloted_to;
                    $vehDraftData->alloted_from = $request->alloted_from;

                    $status = $vehDraftData->save();
                    if ($status) {
                        LOG::info('Data Updated successfully');
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data Updated successfully'
                        ]);
                    } else {
                        LOG::info('Error to update the new data');
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Error to update the new data'
                        ]);
                    }
                } else {
                    LOG::info('Failed to copy the record in history table');
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to copy the record in history table'
                    ]);
                }
            } else {
                LOG::info("Building ID not found");
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Building ID not found'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error'
            ]);
        }
    }

    public function updateEquipment(Request $request)
    {
        try {
            if ((isset($request->equipment_id)) and (isset($request->_token))) {
                $equipment_id = $request->equipment_id;
                $equipment = AssetMechEquipmentDetailsDraft::findOrFail($equipment_id);
                $deletedBy = Auth::user()->id;
                $deletedTime = now();
                if ($equipment) {
                    $status = DB::table('mechanicals.asset_mech_equipment_details_draft_hist')->insertUsing([
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
                        'updated_at',
                        'sent_for_finalize',
                        'sent_for_finalize_on',
                        'sent_for_finalize_by',
                        'chassis_no',
                        'eng_no',
                        'fuel_type',
                        'equipment_type',
                        'is_rejected',
                        'reason_of_rejection',
                        'date_of_rejection',
                        'rejected_by',
                        'hist_created_by',
                        'hist_created_at',
                        'hist_remarks'
                    ], function ($query) use ($equipment_id, $deletedTime, $deletedBy) {
                        $query->from('mechanicals.asset_mech_equipment_details_draft')
                            ->where('euipment_cd', '=', $equipment_id)
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
                                'updated_at',
                                'sent_for_finalize',
                                'sent_for_finalize_on',
                                'sent_for_finalize_by',
                                'chassis_no',
                                'eng_no',
                                'fuel_type',
                                'equipment_type',
                                'is_rejected',
                                'reason_of_rejection',
                                'date_of_rejection',
                                'rejected_by',
                                DB::raw("'$deletedBy' as hist_created_by"),
                                DB::raw("'$deletedTime' as hist_created_at"),
                                DB::raw("'Updated by $deletedBy at $deletedTime' as hist_remarks"),
                            );
                    });
                    if ($status) {
                        $equipment->equipment_name = $request->equipment_name;
                        $equipment->serial_number = $request->serial_number;
                        $equipment->model_no = $request->model_no;
                        $equipment->purchase_year = $request->purchase_year;
                        $equipment->purchase_cost = $request->purchase_cost;
                        $equipment->equipment_condition_cd = $request->equipment_condition_cd;
                        $equipment->is_under_waranty = $request->edit_is_under_waranty;
                        $equipment->equipment_remarks = $request->remarks;
                        $status = $equipment->save();
                        if ($status) {
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Draft data updated successfully!'
                            ]);
                        } else {
                            LOG::info("Failed to update the equipment draft data!");
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Failed to update the equipment draft data!'
                            ]);
                        }
                    } else {
                        LOG::info("Failed to copy the record in history table!");
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Failed to copy the record in history table!'
                        ]);
                    }
                } else {
                    LOG::info("Equipment not found with this id!");
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Equipment not found with this id!'
                    ]);
                }
            } else {
                LOG::info("Something went wrong!");
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error!'
            ]);
        }
    }

    public function addEquipment()
    {
        try {
            Log::info('Calling addEquipment() method of Mechanical controller');
            $user = Auth::user();
            $conditions = AssetMasterVehicleCondition::all();
            $equipmentDetails = DB::table('mechanicals.asset_mech_equipment_details_draft AS med')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'med.equipment_condition_cd', '=', 'vc.condition_cd')
                ->select('med.*', 'vc.condition_descr')
                ->where('med.sent_for_finalize', "N")
                ->Where('med.created_at_office_cd', $user->office)
                ->get();
            $query = DB::getQueryLog();
            Log::info($query);

            return view('mechanical.addEquipment', compact(
                'user',
                'conditions',
                'equipmentDetails'
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

    public function storeEquipment(Request $request)
    {
        $user = Auth::user();
        Log::info("Calling method to store equipment details.");
        $validator = $request->validate([
            'equipment_name' => 'required|string|max:100',
            'serial_number' => 'required|string|max:50',
            'model_no' => 'required|string|max:50',
        ]);
        try {
            $random = rand(10000, 99999);
            $commonData = [
                'euipment_cd' => $random,
                'equipment_name' => $request->equipment_name,
                'serial_number' => $request->serial_number,
                'model_no' => $request->model_no,
                'purchase_year' => $request->purchase_year,
                'purchase_cost' => $request->purchase_cost,
                'equipment_condition_cd' => $request->equipment_condition_cd,
                'is_under_waranty' => $request->is_under_waranty,
                'created_at_office_cd' => $user->office,
                'equipment_remarks' => $request->remarks,
                'created_by' => $user->id,
            ];

            // get the maker checker status for sub assets
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('7');
            if ($makerCheckerStatus == 'Y')
                $status = AssetMechEquipmentDetailsDraft::create($commonData);
            else {
                $extraData = [
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),

                ];
                $status = AssetMechEquipmentDetail::create(array_merge($commonData, $extraData));
            }

            if ($status) {
                // upload doc
                $this->handleDocumentEquipment($request, $random);
                $query = DB::getQueryLog();
                Log::info($query);
                return redirect()->back()
                    ->with('success', 'Equipment data added successfully with code ' . $random);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to insert Equipment data');
            }
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

    private function handleDocumentEquipment($request, $random)
    {
        $configPath = config('customconfigpath.EQUIPMT_DOCS_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('workorder')) {
            $file = $request->file('workorder');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Work Order')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetEquipmentDocumentDetail::create([
                'euipment_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('design_doc')) {
            $file = $request->file('design_doc');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Design Document')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_2.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetEquipmentDocumentDetail::create([
                'euipment_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('sanction_order')) {
            $file = $request->file('sanction_order');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Sanction Order')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_3.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetEquipmentDocumentDetail::create([
                'euipment_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('inspection_report')) {
            $file = $request->file('inspection_report');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Inspection Report (Last)')->get()->first();
            $uniqueFileName = $random . '_' . $docCatg['doc_catg_cd'] . '_4.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetEquipmentDocumentDetail::create([
                'euipment_cd' => $random,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }
    }

    public function equipmentAbstract(Request $request)
    {
        try {
            Log::info("Inside euipmentAbstract Method of Mechanical Controller");
            $equipmentCondition = $request->input('equip_condition');
            $equipmentFuelType = $request->input('equip_fuel_type');
            $abstractBaseQuery = DB::table('mechanicals.asset_mech_equipment_details as equip')
                ->select(
                    'equip.equipment_type',
                    'equipType.equipment_type_descr',
                    DB::raw('COUNT(*) as equipment_count'),
                )
                ->leftJoin('mechanicals.asset_master_euipment_types as equipType', 'equip.equipment_type', '=', 'equipType.equipment_type_cd')
                ->groupBy('equip.equipment_type', 'equipType.equipment_type_descr');
            if ($equipmentCondition != 'A') {
                $abstractBaseQuery->where('equip.equipment_condition_cd', $equipmentCondition);
            }
            if ($equipmentFuelType != 'A') {
                $abstractBaseQuery->where('equip.fuel_type', $equipmentFuelType);
            }
            $equipmentSummary = $abstractBaseQuery->get();
            $query = DB::getQueryLog();
            Log::info($query);
            if ($equipmentSummary->count()) {
                return response()->json([
                    'status' => 200,
                    'message' => $equipmentSummary,
                ]);
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => 'Equipment not available!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function vehicleAbstract(Request $request)
    {
        try {
            Log::info("Inside vehicleAbstract Method of Mechanical Controller");
            $vehCondition = $request->input('veh_condition');
            $vehFuelType = $request->input('veh_fuel_type');
            $abstractBaseQuery = DB::table('mechanicals.asset_mech_vehicles_details as vehicle')
                ->select(
                    'vehicle.vehicle_type',
                    'vehType.veh_type_descr',
                    DB::raw('COUNT(*) as equipment_count'),
                )
                ->leftJoin('mechanicals.asset_master_vehicle_types as vehType', 'vehicle.vehicle_type', '=', 'vehType.veh_type_cd')
                ->groupBy('vehicle.vehicle_type', 'vehType.veh_type_descr');
            if ($vehCondition != 'A') {
                $abstractBaseQuery->where('vehicle.vehicle_condition', $vehCondition);
            }
            if ($vehFuelType != 'A') {
                $abstractBaseQuery->where('vehicle.fuel_type', $vehFuelType);
            }
            $vehicleSummary = $abstractBaseQuery->get();
            $query = DB::getQueryLog();
            Log::info($query);
            if ($vehicleSummary->count()) {
                return response()->json([
                    'status' => 200,
                    'message' => $vehicleSummary,
                ]);
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => 'Vehicle not available!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function destroyEquipment(Request $request)
    {
        try {
            if ($request->_token && $request->id) {
                $equipment = AssetMechEquipmentDetailsDraft::findOrFail($request->id);
                if ($equipment) {
                    $id = $request->id;
                    $deletedBy = Auth::user()->id;
                    $deletedTime = now();
                    $status = DB::table('mechanicals.asset_mech_equipment_details_draft_hist')->insertUsing([
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
                        'updated_at',
                        'sent_for_finalize',
                        'sent_for_finalize_on',
                        'sent_for_finalize_by',
                        'chassis_no',
                        'eng_no',
                        'fuel_type',
                        'equipment_type',
                        'is_rejected',
                        'reason_of_rejection',
                        'date_of_rejection',
                        'rejected_by',
                        'hist_created_by',
                        'hist_created_at',
                        'hist_remarks'
                    ], function ($query) use ($id, $deletedTime, $deletedBy) {
                        $query->from('mechanicals.asset_mech_equipment_details_draft')
                            ->where('euipment_cd', '=', $id)
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
                                'updated_at',
                                'sent_for_finalize',
                                'sent_for_finalize_on',
                                'sent_for_finalize_by',
                                'chassis_no',
                                'eng_no',
                                'fuel_type',
                                'equipment_type',
                                'is_rejected',
                                'reason_of_rejection',
                                'date_of_rejection',
                                'rejected_by',
                                DB::raw("'$deletedBy' as hist_created_by"),
                                DB::raw("'$deletedTime' as hist_created_at"),
                                DB::raw("'Deleted by $deletedBy at $deletedTime' as hist_remarks"),
                            );
                    });
                    if ($status) {
                        $equipment->delete();
                        return redirect()->back()->with('success', 'Equipment data deleted seccessfully!');
                    } else {
                        LOG::info("Failed to copy the draft data into the history table!");
                        return redirect()->back()->with('failed', 'Internal Server Error!');
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function destroyVehicle(Request $request)
    {
        try {
            if ($request->_token && $request->id) {
                $vehicle = AssetMechVehicalsDetailsDraft::findOrFail($request->id);
                if ($vehicle) {
                    $id = $request->id;
                    $deletedBy = Auth::user()->id;
                    $deletedTime = now();
                    $status = DB::table('mechanicals.asset_mech_vehicles_details_draft_hist')->insertUsing([
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
                        'sent_for_finalize',
                        'sent_for_finalize_on',
                        'sent_for_finalize_by',
                        'is_rejected',
                        'reason_of_rejection',
                        'date_of_rejection',
                        'rejected_by',
                        'hist_created_by',
                        'hist_created_at',
                        'hist_remarks'
                    ], function ($query) use ($id, $deletedTime, $deletedBy) {
                        $query->from('mechanicals.asset_mech_vehicles_details_draft')
                            ->where('vehicle_asset_cd', '=', $id)
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
                                'sent_for_finalize',
                                'sent_for_finalize_on',
                                'sent_for_finalize_by',
                                'is_rejected',
                                'reason_of_rejection',
                                'date_of_rejection',
                                'rejected_by',
                                DB::raw("'$deletedBy' as hist_created_by"),
                                DB::raw("'$deletedTime' as hist_created_at"),
                                DB::raw("'Deleted by $deletedBy at $deletedTime' as hist_remarks"),
                            );
                    });
                    if ($status) {
                        $vehicle->delete();
                        return redirect()->back()->with('success', 'Vehicle data deleted seccessfully!');
                    } else {
                        LOG::info("Failed to copy the draft data into the history table!");
                        return redirect()->back()->with('failed', 'Internal Server Error!');
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }
}
