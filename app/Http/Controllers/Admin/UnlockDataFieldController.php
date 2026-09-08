<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterZone;
use App\Models\AssetMasterCircle;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterSubDivision;
use App\Models\AssetUnlockDetails;
use App\Models\AssetUnlockSupportiveDocuments;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UnlockDataFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function loadInitialUnlockPage()
    {
        try {
            $initPage = true;
            $zoneDetails = AssetMasterZone::all();
            $circleDetails = AssetMasterCircle::all();
            $divisionDetails = AssetMasterDivision::all();
            $subDivisionDetails = AssetMasterSubDivision::all();
            $road_categories = DB::table('asset_master_road_category as catg_m')
                ->select('catg_m.*')->orderBy('rd_catg_descr', 'asc')->get();
            return view(
                "admin.ListDataToUnlock",
                compact(
                    'initPage',
                    'zoneDetails',
                    'circleDetails',
                    'divisionDetails',
                    'subDivisionDetails',
                    'road_categories'
                )
            );
        } catch (Exception $e) {
            Log::error("Error Is: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function filterRoadAndBridgesToUnlock(Request $request)
    {
        try {
            DB::enableQueryLog();
            $initPage = false;

            $selected_rd_catgs = array($request->rd_catg_rnb);
            if ($request->rd_catg_rnb == "A") {
                $qry_rd_catgs = DB::table('asset_master_road_category as t')
                    ->select("t.rd_catg_cd")->get();
                foreach ($qry_rd_catgs as $item) {
                    array_push($selected_rd_catgs, $item->rd_catg_cd);
                }
            }
            Log::info("selected_rd_catgs: " . json_encode($selected_rd_catgs));
            $baseQuery = DB::table('asset_road_details')
                ->select(
                    'asset_road_details.rd_system_id',
                    'asset_road_details.rd_number',
                    'asset_road_details.rd_name',
                    'asset_road_details.road_length',
                    'asset_road_details.district_name',
                    'asset_road_details.block_name',
                    'asset_road_details.division_name',
                    'asset_master_road_category.rd_catg_descr',
                    'asset_master_rd_type.rd_type_descr',
                    'asset_master_road_owner.owner_name'
                )
                ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                ->orderByDesc('updated_at')
                ->whereIn('rd_system_id', function ($query) use ($request) {
                    $selected_zone_cds = array($request->zone_cd);
                    if ($request->zone_cd == "A") {
                        $qry_zn_cd = DB::table('asset_master_zones as zone_m')
                            ->select("zone_m.zone_cd")->where('zone_m.dept_cd', '=', '14')->get();
                        foreach ($qry_zn_cd as $item) {
                            array_push($selected_zone_cds, $item->zone_cd);
                        }
                        Log::info("selected_zone_cds:  " . json_encode($selected_zone_cds));
                    }
                    $query->select('rd_system_id')
                        ->distinct()
                        ->from('asset_road_chainage_mappings')
                        ->whereIn('zone_cd', $selected_zone_cds);
                })
                ->whereIn('rd_system_id', function ($query) use ($request) {
                    $selected_circle_cds = array($request->circle_cd);
                    if ($request->circle_cd == "A") {
                        $qry_crcl_cd = DB::table('asset_master_circles as t')
                            ->select("t.circle_cd")->where('t.dept_cd', '=', '14')->get();
                        foreach ($qry_crcl_cd as $item) {
                            array_push($selected_circle_cds, $item->circle_cd);
                        }
                    }
                    $query->select('rd_system_id')
                        ->distinct()
                        ->from('asset_road_chainage_mappings')
                        ->whereIn('circle_cd', $selected_circle_cds);
                })
                ->whereIn('rd_system_id', function ($query) use ($request) {
                    $selected_division_cds = array($request->division_cd);
                    if ($request->division_cd == "A") {
                        $qry_div_cd = DB::table('asset_master_divisions as t')
                            ->select("t.division_cd")->where('t.dept_cd', '=', '14')->get();
                        foreach ($qry_div_cd as $item) {
                            array_push($selected_division_cds, $item->division_cd);
                        }
                    }
                    $query->select('rd_system_id')
                        ->distinct()
                        ->from('asset_road_chainage_mappings')
                        ->whereIn('division_cd', $selected_division_cds);
                })
                ->whereIn('rd_system_id', function ($query) use ($request) {
                    $selected_sub_division_cds = array($request->sub_division_cd);
                    if ($request->sub_division_cd == "A") {
                        $qry_sub_div_cd = DB::table('asset_master_sub_divisions as t')
                            ->select("t.sub_div_cd")->where('t.dept_cd', '=', '14')->get();
                        foreach ($qry_sub_div_cd as $item) {
                            array_push($selected_sub_division_cds, $item->sub_div_cd);
                        }
                    }
                    $query->select('rd_system_id')
                        ->distinct()
                        ->from('asset_road_chainage_mappings')
                        ->whereIn('division_cd', $selected_sub_division_cds);
                })
                ->whereIn('rd_category_cd', $selected_rd_catgs);

            $roadDetails = $baseQuery->get();

            $query = DB::getQueryLog();
            Log::info(end($query));

            $zoneDetails = AssetMasterZone::all();
            $circleDetails = AssetMasterCircle::all();
            $divisionDetails = AssetMasterDivision::all();
            $subDivisionDetails = AssetMasterSubDivision::all();
            $qry_unlock_validity = DB::table('asset_master_unlock_data_validity as val_m')
                ->select('val_m.validity_in_days')
                ->where('val_m.sub_asset_cd', '10')
                ->get()->first();
            $unlock_validity = $qry_unlock_validity->validity_in_days;
            $todays_date = Carbon::now();
            $last_date_of_validity = Carbon::now()->addDays($unlock_validity);
            $last_date_of_validity = $last_date_of_validity->format('Y-m-d');
            $road_categories = DB::table('asset_master_road_category as catg_m')
                ->select('catg_m.*')->orderBy('rd_catg_descr', 'asc')->get();
            return view(
                "admin.ListDataToUnlock",
                compact(
                    'initPage',
                    'roadDetails',
                    'zoneDetails',
                    'circleDetails',
                    'divisionDetails',
                    'subDivisionDetails',
                    'road_categories',
                    'unlock_validity',
                    'last_date_of_validity'
                )
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error');
        }
    }


    public function viewAssetToUnlock(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info("Inside viewAssetToUnlock Method");
            $asset_type_cd = $request->asset_type_cd;
            $asset_cd = $request->asset_cd;


            $qry_unlock_validity = DB::table('asset_master_unlock_data_validity as val_m')
                ->select('val_m.validity_in_days')
                ->where('val_m.sub_asset_cd', '10')
                ->get()->first();
            $unlock_validity = $qry_unlock_validity->validity_in_days;
            $todays_date = Carbon::now();
            $last_date_of_validity = Carbon::now()->addDays($unlock_validity);
            $last_date_of_validity = $last_date_of_validity->format('Y-m-d');


            if ($asset_type_cd == "10") //State Roads
            {
                $asset_title = "Roads (SR)";
                $tblData = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.*',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        'asset_master_office_types.office_type_desc',
                        'office_details.office_name'
                    )
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->join('asset_master_office_types', 'asset_road_details.road_created_at_office_type', '=', 'asset_master_office_types.office_type_cd')
                    ->join('office_details', 'asset_road_details.road_created_at_office_cd', '=', 'office_details.id')
                    ->where('rd_system_id', $asset_cd)
                    ->get()->first();

                return view(
                    "admin.viewUnlockedData",
                    compact(
                        'tblData',
                        'unlock_validity',
                        'last_date_of_validity',
                        'asset_title',
                        'asset_type_cd'
                    )
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error');
        }
    }


    public function saveunlockdata(Request $request)
    {
        try {
            $date = Carbon::now();
            $formatedDate = $date->format('YmdHis');
            $arr_unlock_fields = [];
            if ($request->asset_type_cd == "10") {
                if (isset($_POST['rd_category_cd']))
                    array_push($arr_unlock_fields, "rd_category_cd");
                if (isset($_POST['rd_number']))
                    array_push($arr_unlock_fields, "rd_number");
                if (isset($_POST['rd_name']))
                    array_push($arr_unlock_fields, "rd_name");
                if (isset($_POST['rd_type_cd']))
                    array_push($arr_unlock_fields, "rd_type_cd");
                if (isset($_POST['road_length']))
                    array_push($arr_unlock_fields, "road_length");
                if (isset($_POST['rd_owner_cd']))
                    array_push($arr_unlock_fields, "rd_owner_cd");
                if (isset($_POST['district_name']))
                    array_push($arr_unlock_fields, "district_name");
                if (isset($_POST['block_name']))
                    array_push($arr_unlock_fields, "block_name");
                if (isset($_POST['division_name']))
                    array_push($arr_unlock_fields, "division_name");

                $unlock_req_dtls = [
                    'asset_name' => $request->asset_name,
                    'asset_type_cd' => $request->asset_type_cd,
                    'asset_cd' => $request->asset_cd,
                    'unlock_fields' => $arr_unlock_fields,
                    'unlocked_by' => Auth::user()->id,
                    'unlocked_on' => date('Y-m-d H:i:s')
                ];
                $unlockDtlsJson = ["unlock_dtls" => $unlock_req_dtls];
                $data = [
                    'asset_name' => $request->asset_name,
                    'asset_cd' => $request->asset_cd,
                    'asset_type_cd' => $request->asset_type_cd,
                    'unlock_details' => json_encode($unlock_req_dtls),
                    'unlock_valid_for' => $request->txt_no_of_days,
                    'unlock_valid_upto' => $request->txt_unlock_valid_upto,
                    'unlock_assigned_office_cd' => 7,
                    'unlock_assigned_user_id' => 35,
                    'unlock_created_by' => Auth::user()->id,
                    'unlock_status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            $tblUnlockDtls = AssetUnlockDetails::Create($data);
            if ($tblUnlockDtls) {
                $configPath = config('customconfigpath.UNLOCK_DATA_SUPPORTING_DOCS_PATH');
                $rootPath = config('filesystems.disks.external.root');
                Log::info("configPath:  " . $configPath);
                Log::info("rootPath:  " . $rootPath);
                Log::info("has file:  " . isset($_POST['firstFile']));
                if ($request->hasFile('firstFile')) {
                    Log::info("Has First File to upload");
                    $file = $request->file('firstFile');

                    $filename = $_FILES["firstFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["firstFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_1_' . $formatedDate . "." . $fileTypeWithoutSeperator;


                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    AssetUnlockSupportiveDocuments::create([
                        'unlock_cd' => $tblUnlockDtls->unlock_cd,
                        'file_path' => $completeFilePath,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ]);
                }


                if ($request->hasFile('secondFile')) {
                    $file = $request->file('secondFile');

                    // $uniqueFileName = "secondFile" . '_' . $tender_code . $formatedDate . '.pdf';

                    $filename = $_FILES["secondFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["secondFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_2_' . $formatedDate . "." . $fileTypeWithoutSeperator;


                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    AssetUnlockSupportiveDocuments::create([
                        'unlock_cd' => $tblUnlockDtls->unlock_cd,
                        'file_path' => $completeFilePath,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ]);
                }

                if ($request->hasFile('thirdFile')) {
                    $file = $request->file('thirdFile');

                    // $uniqueFileName = "thirdFile" . '_' . $tender_code . $formatedDate . '.pdf';

                    $filename = $_FILES["thirdFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["thirdFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_3_' . $formatedDate . "." . $fileTypeWithoutSeperator;


                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    AssetUnlockSupportiveDocuments::create([
                        'unlock_cd' => $tblUnlockDtls->unlock_cd,
                        'file_path' => $completeFilePath,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ]);
                }

                if ($request->hasFile('fourthFile')) {
                    $file = $request->file('fourthFile');
                    $filename = $_FILES["fourthFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["fourthFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_4_' . $formatedDate . "." . $fileTypeWithoutSeperator;

                    $folderPath = $configPath . now()->year;

                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                    }

                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    $completeFilePath = $rootPath . '/' . $filePath;

                    AssetUnlockSupportiveDocuments::create([
                        'unlock_cd' => $tblUnlockDtls->unlock_cd,
                        'file_path' => $completeFilePath,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id
                    ]);
                }
            }



            $initPage = true;
            $zoneDetails = AssetMasterZone::all();
            $circleDetails = AssetMasterCircle::all();
            $divisionDetails = AssetMasterDivision::all();
            $subDivisionDetails = AssetMasterSubDivision::all();
            $road_categories = DB::table('asset_master_road_category as catg_m')
                ->select('catg_m.*')->orderBy('rd_catg_descr', 'asc')->get();

            if ($tblUnlockDtls) {
                // return view(
                //     "admin.ListDataToUnlock",
                //     compact(
                //         'initPage',
                //         'zoneDetails',
                //         'circleDetails',
                //         'divisionDetails',
                //         'subDivisionDetails',
                //         'road_categories'
                //     )
                // )->with('success', 'Road Data Unlocked Successfully');
                return redirect()->route('unlockPage')->with('success', 'Road Data Unlocked Successfully');
            } else {
                return redirect()->route('unlockPage')->with('error', 'Road Data Could Not be Unlocked!!!! Please try Again Laer');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error');
        }
    }


    public function loadUnlockedDataToUpdate()
    {
        try {
            DB::enableQueryLog();
            $dept_cd = session('user_dept_cd');
            if ($dept_cd == "14") //Roads Dept
            {
                $asset_name = "Roads (SR)";
                $distinctRoadIds = AssetUnlockDetails::select('asset_cd', 'unlock_cd')->where("asset_type_cd", "10")
                    ->where("unlock_status", "1")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->distinct()->get();
                $query = DB::getQueryLog();
                Log::info(end($query));
                $arrRoadids = [];
                $arrUnlockCDs = [];
                $jsonUnlockCDs = null;
                $arrFinalData = [];

                foreach ($distinctRoadIds as $item) {
                    $unlockDetailsForRoads = DB::table("asset_unlock_details as tbl_unlock")
                        ->select("tbl_unlock.*")
                        ->where("tbl_unlock.asset_cd", $item->asset_cd)
                        ->where("tbl_unlock.unlock_cd", $item->unlock_cd)
                        ->where("unlock_status", "1")
                        ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                        ->orderBy("tbl_unlock.created_at")
                        ->get();
                    $arr_fields_to_update = [];

                    $query = DB::getQueryLog();
                    Log::info(end($query));
                    $arrUnlockCDs = [];
                    foreach ($unlockDetailsForRoads as $item1) {
                        array_push($arrUnlockCDs, $item1->unlock_cd);
                        $unlock_dtls = $item1->unlock_details;
                        $json_unlock_fields = json_decode($unlock_dtls);
                        $arr_unlock_fields = $json_unlock_fields->unlock_fields;
                        foreach ($arr_unlock_fields as $x) {
                            if (in_array($x, $arr_fields_to_update) != 1)
                                array_push($arr_fields_to_update, $x);
                        }
                        Log::info(json_encode($arr_fields_to_update));
                        $tblData = DB::table('asset_road_details')
                            ->select(
                                'asset_road_details.rd_system_id',
                                'asset_road_details.rd_number',
                                'asset_road_details.rd_name',
                                'asset_road_details.road_length',
                                'asset_road_details.district_name',
                                'asset_road_details.block_name',
                                'asset_road_details.division_name',
                                'asset_master_road_category.rd_catg_descr',
                                'asset_master_rd_type.rd_type_descr',
                                'asset_master_road_owner.owner_name',
                                'asset_master_office_types.office_type_desc',
                                'office_details.office_name'
                            )
                            ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                            ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                            ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                            ->join('asset_master_office_types', 'asset_road_details.road_created_at_office_type', '=', 'asset_master_office_types.office_type_cd')
                            ->join('office_details', 'asset_road_details.road_created_at_office_cd', '=', 'office_details.id')
                            ->where('rd_system_id', trim($item->asset_cd))
                            ->get()->first();
                        $query = DB::getQueryLog();
                        Log::info(end($query));

                        $data = [
                            "asset_cd" => $tblData->rd_system_id,
                            "tblData" => $tblData,
                            "unlock_valid_upto" => $item1->unlock_valid_upto,
                            "arrUnlockCDs" => $arrUnlockCDs,
                            "arr_fields_to_update" => $arr_fields_to_update,

                        ];
                    }
                    array_push($arrFinalData, $data);
                }
                Log::info(json_encode($arrFinalData));



                $unlockDetailsForCulvert = DB::table("asset_unlock_details as tbl_unlock")
                    ->select("tbl_unlock.*")->where("tbl_unlock.asset_type_cd", "0")
                    ->where("tbl_unlock.unlock_status", "1")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->orderBy("tbl_unlock.created_at")
                    ->get();

                $unlockDetailsForBridge = DB::table("asset_unlock_details as tbl_unlock")
                    ->select("tbl_unlock.*")->where("tbl_unlock.asset_type_cd", "1")
                    ->where("tbl_unlock.unlock_status", "1")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->orderBy("tbl_unlock.created_at")
                    ->get();

                $unlockDetailsForPavements = DB::table("asset_unlock_details as tbl_unlock")
                    ->select("tbl_unlock.*")->where("tbl_unlock.asset_type_cd", "2")
                    ->where("tbl_unlock.unlock_status", "1")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->orderBy("tbl_unlock.created_at")
                    ->get();

                $unlockDetailsForSurfaceTypes = DB::table("asset_unlock_details as tbl_unlock")
                    ->select("tbl_unlock.*")->where("tbl_unlock.asset_type_cd", "3")
                    ->where("tbl_unlock.unlock_status", "1")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->orderBy("tbl_unlock.created_at")
                    ->get();

                $unlockDetailsForHabitation = DB::table("asset_unlock_details as tbl_unlock")
                    ->select("tbl_unlock.*")->where("tbl_unlock.asset_type_cd", "4")
                    ->where("tbl_unlock.unlock_status", "1")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->orderBy("tbl_unlock.created_at")
                    ->get();

                $unlockDetailsForPCI = DB::table("asset_unlock_details as tbl_unlock")
                    ->select("tbl_unlock.*")->where("tbl_unlock.asset_type_cd", "4")
                    ->where("tbl_unlock.unlock_status", "9")
                    ->where("unlock_valid_upto", ">=", Carbon::now()->format('Y-m-d H:i:s'))
                    ->orderBy("tbl_unlock.created_at")
                    ->get();

                $query = DB::getQueryLog();
                Log::info(end($query));
                return view(
                    "admin.ListUnlockedDataToUpdate",
                    compact(
                        'arrFinalData',
                        'unlockDetailsForCulvert',
                        'unlockDetailsForBridge',
                        'unlockDetailsForPavements',
                        'unlockDetailsForSurfaceTypes',
                        'unlockDetailsForHabitation',
                        'unlockDetailsForPCI',
                        'asset_name'
                    )
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function editUnlockedAssetData(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info("Inside editUnlockedAssetData Method");
            $asset_type_cd = $request->asset_type_cd;
            $asset_cd = $request->asset_cd;
            $arr_fields_to_update = $request->hdnFieldsToUnlock;
            $arrUnlockCDs = $request->arrUnlockCDs;
            $qry_unlock_validity = DB::table('asset_master_unlock_data_validity as val_m')
                ->select('val_m.validity_in_days')
                ->where('val_m.sub_asset_cd', '10')
                ->get()->first();
            $unlock_validity = $qry_unlock_validity->validity_in_days;
            $todays_date = Carbon::now();
            $last_date_of_validity = Carbon::now()->addDays($unlock_validity);
            $last_date_of_validity = $last_date_of_validity->format('Y-m-d');


            if ($asset_type_cd == "10") //State Roads
            {
                $asset_title = "Roads (SR)";
                $tblData = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.*',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        'asset_master_office_types.office_type_desc',
                        'office_details.office_name'
                    )
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->join('asset_master_office_types', 'asset_road_details.road_created_at_office_type', '=', 'asset_master_office_types.office_type_cd')
                    ->join('office_details', 'asset_road_details.road_created_at_office_cd', '=', 'office_details.id')
                    ->where('rd_system_id', $asset_cd)
                    ->get()->first();

                $road_types = DB::table('asset_master_rd_type as tbl_m')
                    ->select('tbl_m.*')->orderBy('rd_type_descr', 'asc')->get();
                $road_categories = DB::table('asset_master_road_category as catg_m')
                    ->select('catg_m.*')->orderBy('rd_catg_descr', 'asc')->get();
                $rd_owner_m = DB::table('asset_master_road_owner as tb_m')
                    ->select('tb_m.*')->orderBy('owner_name', 'asc')->get();
                return view(
                    "admin.UpdateUnlockedData",
                    compact(
                        'tblData',
                        'road_categories',
                        'road_types',
                        'rd_owner_m',
                        'unlock_validity',
                        'last_date_of_validity',
                        'asset_title',
                        'asset_type_cd',
                        'arr_fields_to_update',
                        'arrUnlockCDs'
                    )
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function saveUnlockedAssetDetails(Request $request)
    {
        try {
            Log::info("Inside saveUnlockedAssetDetails");
            $asset_type_cd = $request->asset_type_cd;
            $asset_cd = $request->asset_cd;
            $fields_to_update = str_replace("[", "", $request->fields_to_update);
            $fields_to_update = str_replace("]", "", $fields_to_update);
            $fields_to_update = str_replace("\"", "", $fields_to_update);
            $arr_fields_to_update = explode(",", $fields_to_update);
            $strUnlockCDs = str_replace("[", "", $request->arrUnlockCDs);
            $strUnlockCDs = str_replace("]", "", $strUnlockCDs);
            $strUnlockCDs = str_replace("\"", "", $strUnlockCDs);
            $arrUnlockCDs = explode(",", $strUnlockCDs);

            $uid = Auth::user()->id;
            $currentTime = now();
            if ($asset_type_cd == "10") {
                $tblRecord = AssetRoadDetail::where('rd_system_id', $asset_cd)->first();
                if ($tblRecord) {
                    $status_move_data = DB::table('asset_road_details_hist')->insertUsing([
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'road_type',
                        'district_name',
                        'block_name',
                        'lng',
                        'lat',
                        'created_at',
                        'updated_at',
                        'updated_by',
                        'created_by',
                        'remarks'
                    ], function ($query) use ($asset_cd, $uid, $currentTime) {
                        $query->from('asset_road_details')
                            ->where('rd_system_id', '=', $asset_cd)
                            ->select(
                                'rd_system_id',
                                'rd_category_cd',
                                'rd_number',
                                'rd_name',
                                'rd_type_cd',
                                'road_length',
                                'rd_owner_cd',
                                'road_type',
                                'district_name',
                                'block_name',
                                'lng',
                                'lat',
                                'created_at',
                                DB::raw("'$currentTime' as updated_at"),
                                DB::raw("'$uid' as updated_by"),
                                'created_by',
                                DB::raw("'Modify Unlocked Data' as remarks")
                            );
                    });
                    if ($status_move_data > 0) {
                        Log::info("Road Asset Data copied to History Table with rd_system_cd: " . $asset_cd);
                        foreach ($arr_fields_to_update as $item) {
                            $tblRecord->$item = $request->$item;
                        }

                        $status = $tblRecord->save();
                    }



                    if ($status) {
                        AssetUnlockDetails::wherein('unlock_cd', $arrUnlockCDs)->update(['unlock_status' => 2]);
                        return redirect()->route('loadUnlockedDataToUpdate')->with('success', 'Road Data Updated Successfully, Road Id:' . $tblRecord->rd_system_id);
                    } else
                        return redirect()->route('loadUnlockedDataToUpdate')->with('error', 'Road Data Could not be Updated');
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return view('error');
        }
    }
}
