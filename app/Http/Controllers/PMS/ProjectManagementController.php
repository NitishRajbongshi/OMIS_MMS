<?php

namespace App\Http\Controllers\PMS;

use Exception;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterDocumentCategory;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingLocation;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\DepartmentDetail;
use App\Models\PMS\PrmProjectType;
use App\Models\PMS\PrtContractorDetail;
use App\Models\PMS\PrtProjectDetail;
use App\Models\PMS\PrtProjectDetailsDraft;
use App\Models\PMS\PrtProjectDocumentDetail;
use App\Models\PMS\PrtProjectImageDetail;
use App\Models\PMS\PrtProjectSubAssetDetailsDraft;
use App\Models\PMS\PrtProjectWorkItemsDetail;
use App\Models\PMS\PrtProjectWorkSubItemsDetail;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetMasterRdType;
use App\Models\AssetMasterRoadOwner;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\Road\AssetRoadDocumentKmlFileDetails;
use App\Models\Road\Master\AssetMasterDeptOfState;

class ProjectManagementController extends Controller
{
    private const DB_CONNECTION = 'pgsql_pms'; // important for DB transaction
    public function index(Request $request)
    {
        session(['project_cd' => $request->id]);
        return redirect()->route('manage-project');
    }

    // Generate project code
    private function generateProjectCode($request, $makerCheckerStatus, $year, $prefix)
    {
        try {
            // if ($makerCheckerStatus === 'Y') {
            //     $lastProject = DB::table('projects.prt_project_details_draft')
            //         ->where('owner_dept_cd', $request->owner_dept_cd)
            //         ->whereYear('created_at', $year)
            //         ->orderByDesc('project_cd')
            //         ->value('project_cd');
            // } else {
            //     $lastProject = DB::table('projects.prt_project_details')
            //         ->where('owner_dept_cd', $request->owner_dept_cd)
            //         ->whereYear('created_at', $year)
            //         ->orderByDesc('project_cd')
            //         ->value('project_cd');
            // }

            // $lastProject = DB::table('projects.prt_project_details_draft')
            //     ->where('owner_dept_cd', $request->owner_dept_cd)
            //     ->whereYear('created_at', $year)
            //     ->orderByDesc('project_cd')
            //     ->value('project_cd');
            // $serial = 1;
            // if ($lastProject) {
            //     $lastSerial = (int) substr($lastProject, strrpos($lastProject, '_') + 1);
            //     $serial = $lastSerial + 1;
            // }
            // if ($serial <= 999) {
            //     $formattedSerial = str_pad($serial, 3, '0', STR_PAD_LEFT);
            // } else {
            //     $formattedSerial = (string) $serial;
            // }
            // $project_cd = $prefix . $formattedSerial . time();
            $project_cd = $prefix . time();
            return $project_cd;
        } catch (Throwable $e) {
            Log::error('Error to generate project code', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    private function handleSubAsset($request, $project_cd, $parent_asset_cd, $makerCheckerStatus)
    {
        $culvert = $request->culvert;
        $bridge = $request->bridge;
        $retain_wall = $request->retain_wall;
        $pavements = $request->pavements;

        $culvert_starts = $request->input('culvert_start', []);
        $bridge_starts = $request->input('bridge_start', []);
        $rtw_starts = $request->input('rtw_start', []);
        $pvm_starts = $request->input('pvm_start', []);

        try {
            // Get sub-asset codes
            $culvert_cd = DB::table('asset_master_road_sub_assets')
                ->where('sub_assets_descr', $culvert)
                ->value('sub_asset_cd');

            $bridge_cd = DB::table('asset_master_road_sub_assets')
                ->where('sub_assets_descr', $bridge)
                ->value('sub_asset_cd');

            $rtw_cd = DB::table('asset_master_road_sub_assets')
                ->where('sub_assets_descr', $retain_wall)
                ->value('sub_asset_cd');

            $pvm_cd = DB::table('asset_master_road_sub_assets')
                ->where('sub_assets_descr', $pavements)
                ->value('sub_asset_cd');

            // Insert culverts
            foreach ($culvert_starts as $index => $start_chainage) {
                $subAssetData = [
                    'project_cd' => $project_cd,
                    'parent_asset_cd' => $parent_asset_cd,
                    'sub_asset_type_cd' => $culvert_cd,
                    'sub_asset_sr_no' => $index + 1,
                    'start_chainage' => $start_chainage,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    // PrtProjectSubAssetDetail::create($subAssetData);
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                }
            }

            // Insert bridges
            foreach ($bridge_starts as $index => $start_chainage) {
                $subAssetData = [
                    'project_cd' => $project_cd,
                    'parent_asset_cd' => $parent_asset_cd,
                    'sub_asset_type_cd' => $bridge_cd,
                    'sub_asset_sr_no' => $index + 1,
                    'start_chainage' => $start_chainage,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    // PrtProjectSubAssetDetail::create($subAssetData);
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                }
            }

            // Insert retaining walls
            foreach ($rtw_starts as $index => $start_chainage) {
                $subAssetData = [
                    'project_cd' => $project_cd,
                    'parent_asset_cd' => $parent_asset_cd,
                    'sub_asset_type_cd' => $rtw_cd,
                    'sub_asset_sr_no' => $index + 1,
                    'start_chainage' => $start_chainage,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    // PrtProjectSubAssetDetail::create($subAssetData);
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                }
            }

            // Insert pavements
            foreach ($pvm_starts as $index => $start_chainage) {
                $subAssetData = [
                    'project_cd' => $project_cd,
                    'parent_asset_cd' => $parent_asset_cd,
                    'sub_asset_type_cd' => $pvm_cd,
                    'sub_asset_sr_no' => $index + 1,
                    'start_chainage' => $start_chainage,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    // PrtProjectSubAssetDetail::create($subAssetData);
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                }
            }
        } catch (Throwable $e) {
            Log::error('Error to handle sub assets', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    private function handleDocument($request, $randomCode, $project_cd)
    {
        try {
            $configPath = config('customconfigpath.PMS_DOCS_PATH');
            $configImagePath = config('customconfigpath.PMS_ASSET_IMAGES_PATH');
            $rootPath = config('filesystems.disks.external.root');

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $image) {
                    $randomNumber = mt_rand(100, 999);
                    // get image extension
                    $extension = $image->getClientOriginalExtension();
                    $uniqueFileName = 'pms_' . $randomCode . '_' . $randomNumber . '.' . $extension;
                    $folderPath = $configImagePath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $image->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    PrtProjectImageDetail::create([
                        'project_cd' => $project_cd,
                        'image_path' => $completeFilePath,
                        'file_type' => $extension,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id()
                    ]);
                }
            }

            // Code for Sanction Order
            // Do not change hasFile() value
            if ($request->hasFile('workOrder')) {
                $file = $request->file('workOrder'); // for Sanction Order

                // Get document category code for 'Sanction Order' using like operator
                $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                    ->where('doc_catg_descr', 'like', '%Sanction Order%')->get()->first();

                $extension = $file->getClientOriginalExtension();
                $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
                $folderPath = $configPath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                PrtProjectDocumentDetail::create([
                    'project_cd' => $project_cd,
                    'file_path' => $completeFilePath,
                    'file_type' => $extension,
                    'doc_catg' => $docCatg['doc_catg_cd'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            if ($request->hasFile('projectPlan')) {
                $file = $request->file('projectPlan');
                $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                    ->where('doc_catg_descr', 'like', '%Work Order%')->get()->first();
                $extension = $file->getClientOriginalExtension();
                $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_2.pdf';
                $folderPath = $configPath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                PrtProjectDocumentDetail::create([
                    'project_cd' => $project_cd,
                    'file_path' => $completeFilePath,
                    'file_type' => $extension,
                    'doc_catg' => $docCatg['doc_catg_cd'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            if ($request->hasFile('drpDocument')) {
                $file = $request->file('drpDocument');
                $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                    ->where('doc_catg_descr', 'like', '%Work Plan%')->get()->first();
                $extension = $file->getClientOriginalExtension();
                $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_4.pdf';
                $folderPath = $configPath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                PrtProjectDocumentDetail::create([
                    'project_cd' => $project_cd,
                    'file_path' => $completeFilePath,
                    'file_type' => $extension,
                    'doc_catg' => $docCatg['doc_catg_cd'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }

            if ($request->hasFile('designDoc')) {
                $file = $request->file('designDoc');
                $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                    ->where('doc_catg_descr', 'like', '%Project Agreement%')->get()->first();
                $extension = $file->getClientOriginalExtension();
                $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_3.pdf';
                $folderPath = $configPath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                PrtProjectDocumentDetail::create([
                    'project_cd' => $project_cd,
                    'file_path' => $completeFilePath,
                    'file_type' => $extension,
                    'doc_catg' => $docCatg['doc_catg_cd'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id()
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Error while handling project documents', [
                'project_cd' => $project_cd,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'exception' => $e,
            ]);

            throw $e;
        }
    }

    // Store work items
    private function storeWorkItems($request, $project_cd)
    {
        try {
            foreach ($request->work_items as $index => $item_cd) {
                $work_quantity = $request->work_qtys[$index] ?? null;
                $start_date = $request->estimateStartDate[$index] ?? null;
                $end_date = $request->estimateEndDate[$index] ?? null;

                $predArray = $request->predecessorSelect[$index] ?? [];
                $cleanPreds = array_filter($predArray);  // removes null values
                $predCodes = !empty($cleanPreds) ? implode(",", $cleanPreds) : null;

                $workItemsData = [
                    'project_cd' => $project_cd,
                    'item_cd' => $item_cd,
                    'quantity' => $work_quantity,
                    'predecessors_item_codes' => $predCodes,
                    'est_start_date' => $start_date,
                    'est_end_date' => $end_date,
                    'is_published' => 'Y',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ];

                PrtProjectWorkItemsDetail::create($workItemsData);
            }
        } catch (Throwable $e) {
            Log::error('Error to handle sub assets', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    // store sub items of work items
    private function storeWorkSubItems($subItemsArray, $project_cd)
    {
        try {
            foreach ($subItemsArray as $item_cd => $subItemList) {
                foreach ($subItemList as $subData) {
                    $subItemData = [
                        'project_cd' => $project_cd,
                        'item_cd' => $item_cd,
                        'sub_item_cd' => $subData['code'],
                        'quantity' => $subData['quantity'] ?? null,
                        'predecessors_sub_item_codes' => null,
                        'est_start_date' => $subData['start'] ?: null,
                        'est_end_date' => $subData['end'] ?: null,
                        'is_published' => 'Y',
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ];
                    PrtProjectWorkSubItemsDetail::create($subItemData);
                }
            }
        } catch (Throwable $e) {
            Log::error('Error to handle sub assets', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $department = DepartmentDetail::where('id', '=', $user->department)->get()->first();
        $officeDetails = session('userMapping');
        $circle_cd = $officeDetails->circle_cd;
        $division_cd = $officeDetails->division_cd;
        $officeType = $officeDetails->office_type_cd;

        if ($officeType === 'DO' || $officeType === 'SDO') {
            $div = DB::table('asset_master_divisions')->where('division_cd', '=', $division_cd)->get();
        } else if ($officeType === 'ZO') {
            $zone_cd = DB::table('asset_user_mappings')
                ->where('user_id', $user->id)
                ->value('zone_cd');

            $div = DB::table('asset_master_divisions')
                ->where('zone_cd', $zone_cd)
                ->get();
        } else if ($officeType === 'HQ' || $officeType === 'ECO' || $officeType === 'ADM') {
            $div = DB::table('asset_master_divisions')
                ->get();
        } else if ($officeType === 'CO') {
            $div = DB::table('asset_master_divisions')->where('circle_cd', '=', $circle_cd)->get();
        }

        $roads = DB::table('road_details')->get();
        $projectTypes = PrmProjectType::all();
        $constructors = PrtContractorDetail::all();
        $technologies = DB::table('projects.prm_project_technology_types')->get(); // Nitish
        $departmentDetails = AssetMasterDeptOfState::all();

        $types = ['NEW', 'UPG', 'MTN'];
        $projects = DB::table('projects.prt_project_details_draft as project_details')
            ->select([
                'project_details.project_cd',
                'project_details.project_name',
                'project_details.project_type_cd',
                'project_details.owner_dept_cd',
                'project_details.division_cd',
                'project_details.sub_division_cd',
                'project_details.parent_asset_cd',
                'project_details.project_start_date',
                'project_details.project_end_date',
                'project_details.est_proj_cost',
                'project_details.defect_liability_period',
                'project_details.work_order_amount',
                // New
                'project_details.work_order_no',
                'project_details.work_order_issue_date',
                'project_details.scheme_cd',
                // End
                'project_details.project_status_cd',
                'project_details.project_awarded_to',
                'project_details.site_eng_id',
                'project_details.site_incharge_name',
                'project_details.site_incharge_office_cd',
                'project_details.site_incharge_ph_no',
                'project_details.latitude',
                'project_details.longitude',
                'project_details.others',
                'project_details.reason_of_rejection',
                'project_type.proj_type_descr as project_type',
                'owner_dept.department_name as owner_department',
                'division.division_name',
                'sub_division.sub_div_name',
                'site_incharge.office_name as site_incharge_office_name',
                'project_status.project_status_descr as project_status',
                'contractor.contractors_name as contractor_name',
                'scheme.scheme_name'
            ])
            ->leftJoin(
                'projects.prm_project_types as project_type',
                'project_details.project_type_cd',
                '=',
                'project_type.proj_type_cd'
            )
            ->leftJoin(
                'public.department_details as owner_dept',
                'project_details.owner_dept_cd',
                '=',
                'owner_dept.id'
            )
            ->leftJoin(
                'public.asset_master_divisions as division',
                'project_details.division_cd',
                '=',
                'division.division_cd'
            )
            ->leftJoin(
                'public.asset_master_sub_divisions as sub_division',
                'project_details.sub_division_cd',
                '=',
                'sub_division.sub_div_cd'
            )
            ->leftJoin(
                'projects.prm_project_status as project_status',
                'project_details.project_status_cd',
                '=',
                'project_status.project_status_cd'
            )
            ->leftJoin(
                'projects.prm_site_incharge_office_details as site_incharge',
                'project_details.site_incharge_office_cd',
                '=',
                'site_incharge.office_cd'
            )
            ->leftJoin(
                'projects.prt_contractor_details as contractor',
                'project_details.project_awarded_to',
                '=',
                'contractor.regn_no'
            )
            ->leftJoin(
                'projects.prm_scheme_details as scheme',
                'project_details.scheme_cd',
                '=',
                'scheme.scheme_id'
            )
            ->where('sent_for_finalize', '=', 'N')
            ->where('owner_dept_cd', '=', $user->department)
            ->whereIn('project_type_cd', $types)
            ->orderBy('project_cd', 'desc')
            ->get();

        $draftProjects = $projects;

        if ($request->query('mode') == 'create') {
            session()->forget('project_cd');
        }

        //code by Pulak
        $project_cd = session('project_cd');
        if ($project_cd) {
            // EDIT MODE
            $project = $project[0] ?? null;
        } else {
            // CREATE MODE
            $project = null;
        }

        $siteOffices = DB::table('projects.prm_site_incharge_office_details')->get();
        $contractorCategories = DB::table('projects.prm_contractor_categories')->get();
        $districts = DB::table('public.asset_master_lgd_district')->select(['dist_code', 'dist_name'])->get();
        $states = DB::table('public.asset_master_lgd_state')->select(['state_code', 'state_name'])->get();
        $agencies = DB::table('projects.prm_funding_agency_details')->select(['funding_agency_id', 'agency_name'])->get();
        $schemes = DB::table('projects.prm_scheme_details')->select(['scheme_id', 'scheme_name'])->get();
        $workSubItems = DB::table('projects.prm_item_sub_item_of_work')->get();

        // check if the work item exist or not
        $workItems_exist = DB::table('projects.prt_project_work_items_details')
            ->distinct('project_cd')
            ->pluck('project_cd')
            ->toArray();

        $hasMaintenanceAssets = false;
        $hasUpgradationAssets = false;

        foreach ($draftProjects as $draft) {
            if (!$draft->others)
                continue;
            $others = json_decode($draft->others, true);
            if ($user->department == 14 || $user->department == 3) {
                if (!empty($others['maintenance'])) {
                    $mtn = $others['maintenance'];
                    if (!empty($mtn['asset_dtls']['asset_list'])) {
                        $hasMaintenanceAssets = true;
                    }
                    if (!empty($mtn['sub_asset_dtls']) && is_array($mtn['sub_asset_dtls'])) {
                        foreach ($mtn['sub_asset_dtls'] as $subAsset) {
                            if (!empty($subAsset['sub_asset_list'])) {
                                $hasMaintenanceAssets = true;
                                break;
                            }
                        }
                    }
                }
            }

            if ($user->department == 6) {
                if (!empty($others['maintenance'])) {
                    $mtn = $others['maintenance'];
                    if (!empty($mtn['building_id'])) {
                        $hasMaintenanceAssets = true;
                        break;
                    }
                }
            }

            if ($user->department == 15) {
                if (!empty($others['maintenance'])) {

                    $mtn = $others['maintenance'];

                    $hasVehicles = !empty($mtn['vehicles']);
                    $hasEquipments = !empty($mtn['equipments']);

                    if ($hasVehicles || $hasEquipments) {
                        $hasMaintenanceAssets = true;
                        break;
                    }
                }
            }
        }

        foreach ($draftProjects as $draft) {
            if (!$draft->others)
                continue;
            $others = json_decode($draft->others, true);
            if ($user->department == 14 || $user->department == 3) {
                if (!empty($others['upgradation'])) {
                    $upg = $others['upgradation'];
                    $hasExistingAssets = false;
                    $rows = $upg['upgraded_asset_dtls'] ?? [];
                    foreach ($rows as $row) {
                        $culverts = array_filter($row['culverts'] ?? []);
                        $bridges = array_filter($row['bridges'] ?? []);
                        $walls = array_filter($row['retaining_walls'] ?? []);


                        if (!empty($row['parent_asset_id']) || $culverts || $bridges || $walls) {
                            $hasExistingAssets = true;
                            break;
                        }
                    }

                    $newAsset = $upg['new_asset'] ?? null;
                    $hasNewAssets = false;
                    if (!empty($newAsset) && is_array($newAsset)) {
                        $hasNewAssets = (
                            !empty($newAsset['road_name']) ||
                            (float) ($newAsset['road_length'] ?? 0) > 0
                        );
                    }
                    $hasUpgradationAssets = $hasExistingAssets || $hasNewAssets;
                }
                $draft->hasUpgradationAssets = $hasUpgradationAssets;
            }

            if ($user->department == 6) {
                if (!empty($others['upgradation'])) {
                    $upg = $others['upgradation'];
                    if (!empty($upg['building_id'])) {
                        $hasUpgradationAssets = true;
                        break;
                    }
                }
            }

            if ($user->department == 15) {
                if (!empty($others['upgradation'])) {
                    $upg = $others['upgradation'];
                    $hasVehicles = !empty($upg['vehicles']);
                    $hasEquipments = !empty($upg['equipments']);
                    if ($hasVehicles || $hasEquipments) {
                        $hasUpgradationAssets = true;
                        break;
                    }
                }
            }
        }

        $roadCategories = AssetMasterRoadCategory::all();
        $roadTypes = AssetMasterRdType::all();
        $roadOwners = AssetMasterRoadOwner::all();

        // dynamically change view blade by user department
        $viewBlade = 'pms.create';
        if ($user->department == 6) {
            $viewBlade = 'pms.housing.create';
        }

        if ($user->department == 15) {
            $viewBlade = 'pms.mechanicals.create';
        }

        if ($user->department == 3) {
            $viewBlade = 'pms.national_highway.create';
        }

        return view($viewBlade, compact(
            'project',
            'project_cd',
            'projectTypes',
            'hasMaintenanceAssets',
            'hasUpgradationAssets',
            'technologies',
            'div',
            'districts',
            'states',
            'agencies',
            'schemes',
            'roadCategories',
            'roadTypes',
            'roadOwners',
            'department',
            'constructors',
            'siteOffices',
            'draftProjects',
            'workSubItems',
            'workItems_exist',
            'contractorCategories',
            'departmentDetails'
        ));
    }

    public function getOtherDetailsForNewWorks(Request $request, $projectId)
    {
        $user = auth()->user();
        $table = $request->table;

        if ($table == 'draft') {
            $draft = DB::table('projects.prt_project_details_draft')
                ->where('project_cd', $projectId)
                ->first();
        }
        if ($table == 'approved') {
            $draft = DB::table('projects.prt_project_details')
                ->where('project_cd', $projectId)
                ->first();
        }

        $others = json_decode($draft->others, true);

        if ($user->department == 14 || $user->department == 3) {

            $roadCategory = DB::table('asset_master_road_category')
                ->where('rd_catg_cd', $others['road_category'] ?? null)
                ->value('rd_catg_descr');

            $roadType = DB::table('asset_master_rd_type')
                ->where('rd_type_cd', $others['road_type'] ?? null)
                ->value('rd_type_descr');

            $roadOwner = DB::table('asset_master_road_owner')
                ->where('owner_cd', $others['road_owner'] ?? null)
                ->value('owner_name');

            $others['road_category_name'] = $roadCategory;
            $others['road_type_name'] = $roadType;
            $others['road_owner_name'] = $roadOwner;
            return response()->json([
                'status' => 'success',
                'data' => $others,
            ]);
        }

        if ($user->department == 6) {
            // if logic need further
            return response()->json([
                'status' => 'success',
                'data' => $others,
            ]);
        }
    }

    public function getUpgradationDetail(Request $request, $projectId)
    {
        $user = auth()->user();
        $table = $request->table;

        if ($table == 'draft') {
            $draft = DB::table('projects.prt_project_details_draft')
                ->where('project_cd', $projectId)
                ->first();
        }
        if ($table == 'approved') {
            $draft = DB::table('projects.prt_project_details')
                ->where('project_cd', $projectId)
                ->first();
        }

        $others = json_decode($draft->others, true);

        $upg = $others['upgradation'];

        if ($user->department == 14 || $user->department == 3) {

            $rows = $upg['upgraded_asset_dtls'] ?? [];

            $roadSequence = $upg['road_sequence'] ?? [];

            $getNames = function ($items, $table, $column, $valueField) {
                $names = [];

                foreach ($items ?? [] as $cd) {
                    $name = DB::table($table)
                        ->where($column, $cd)
                        ->value($valueField);

                    if ($name)
                        $names[] = $name;
                }

                return $names;
            };

            $responseRows = [];

            foreach ($rows as $row) {

                $roadName = DB::table('asset_road_details')
                    ->where('rd_system_id', $row['parent_asset_id'])
                    ->value('rd_name');

                $responseRows[] = [
                    'road_name' => $roadName,
                    'start_chainage' => $row['start_chainage'],
                    'end_chainage' => $row['end_chainage'],
                    'culverts' => $getNames($row['culverts'], 'asset_road_cdwork_details', 'rd_cdwork_cd', 'culvert_no'),
                    'bridges' => $getNames($row['bridges'], 'asset_road_bridge_details', 'rd_bridge_cd', 'bridge_name'),
                    'walls' => $getNames($row['retaining_walls'], 'asset_protection_wall_details', 'protection_wall_cd', 'protection_wall_cd'),
                ];
            }

            $newAsset = $upg['new_asset'] ?? null;

            $roadCategory = null;
            $roadType = null;
            $roadOwner = null;

            if ($newAsset) {

                $roadCategory = DB::table('asset_master_road_category')
                    ->where('rd_catg_cd', $newAsset['road_category'] ?? null)
                    ->value('rd_catg_descr');

                $roadType = DB::table('asset_master_rd_type')
                    ->where('rd_type_cd', $newAsset['road_type'] ?? null)
                    ->value('rd_type_descr');

                $roadOwner = DB::table('asset_master_road_owner')
                    ->where('owner_cd', $newAsset['road_owner'] ?? null)
                    ->value('owner_name');
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'roads' => $responseRows,
                    'road_sequence' => $roadSequence,
                    'road_category' => $roadCategory,
                    'road_type' => $roadType,
                    'road_owner' => $roadOwner,
                    'new_road_id' => $newAsset['road_id'] ?? null,
                    'new_road_name' => $newAsset['road_name'] ?? null,
                    'new_road_length' => $newAsset['road_length'] ?? 0,
                ]
            ]);
        }

        if ($user->department == 6) {

            $buildingId = $upg['building_id'] ?? null;

            $building = DB::table('buildings.asset_building_details as b')
                ->leftJoin('buildings.asset_master_building_locations as l', 'b.building_location_cd', '=', 'l.location_cd')
                ->leftJoin('buildings.asset_master_building_types as bt', 'b.building_type_cd', '=', 'bt.building_type_cd')
                ->leftJoin('asset_master_dept_of_state as d', 'b.asset_owning_dept_cd', '=', 'd.id')
                ->leftJoin('buildings.asset_master_building_class as bc', 'b.building_class_cd', '=', 'bc.building_class_cd')
                ->select(
                    'b.building_system_cd',
                    'b.building_type_cd',
                    'b.asset_owning_dept_cd',
                    'b.building_class_cd',
                    'b.qtr_no',
                    'b.bld_qtr_name',
                    'b.is_maintained_by_npwd',
                    'b.lat',
                    'b.lon',
                    'l.location_name',
                    'bt.building_type_descr',
                    'd.dept_name',
                    'bc.building_class_descr'
                )
                ->where('b.building_system_cd', $buildingId)
                ->first();

            if (!$building) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Building not found'
                ]);
            }

            $buildingTypeName = $building->building_type_descr;
            $deptName = $building->dept_name;
            $buildingClassName = $building->building_class_descr;

            if (!empty($upg['building_type_cd'])) {
                $buildingTypeName = DB::table('buildings.asset_master_building_types')
                    ->where('building_type_cd', $upg['building_type_cd'])
                    ->value('building_type_descr');
            }

            if (!empty($upg['owning_dept_cd'])) {
                $deptName = DB::table('asset_master_dept_of_state')
                    ->where('id', $upg['owning_dept_cd'])
                    ->value('dept_name');
            }

            if (!empty($upg['building_category_cd'])) {
                $buildingClassName = DB::table('buildings.asset_master_building_class')
                    ->where('building_class_cd', $upg['building_category_cd'])
                    ->value('building_class_descr');
            }

            $final = [
                'building_id' => $buildingId,

                'building_type_cd' => $upg['building_type_cd'] ?? $building->building_type_cd,
                'building_type_descr' => $buildingTypeName,

                'owning_dept_cd' => $upg['owning_dept_cd'] ?? $building->asset_owning_dept_cd,
                'dept_name' => $deptName,
                'is_maintained_by_npwd' => $upg['is_maintained_by_npwd'] ?? $building->is_maintained_by_npwd,
                'building_class_cd' => $upg['building_category_cd'] ?? $building->building_class_cd,
                'building_class_descr' => $buildingClassName,
                'lat' => $building->lat,
                'lon' => $building->lon,
                'location_name' => $building->location_name,
            ];

            if ($final['building_class_cd'] == 0) {
                $final['quarter_no'] = $upg['quarter_no'] ?? $building->qtr_no;
            } else {
                $final['building_name'] = $upg['building_name'] ?? $building->bld_qtr_name;
            }

            return response()->json([
                'status' => 'success',
                'data' => $final
            ]);
        }

        if ($user->department == 15) {

            $vehicles = collect();
            $equipments = collect();

            if (!empty($upg['vehicles'])) {
                $vehicles = DB::table('mechanicals.asset_mech_vehicles_details')
                    ->whereIn('vehicle_asset_cd', $upg['vehicles'])
                    ->get();
            }

            if (!empty($upg['equipments'])) {
                $equipments = DB::table('mechanicals.asset_mech_equipment_details')
                    ->whereIn('euipment_cd', $upg['equipments'])
                    ->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'vehicles' => $vehicles->values(),
                    'equipments' => $equipments->values()
                ]
            ]);
        }
    }

    public function getMaintenanceDetail(Request $request, $projectId)
    {
        $user = auth()->user();
        $table = $request->table;

        if ($table == 'draft') {
            $draft = DB::table('projects.prt_project_details_draft')
                ->where('project_cd', $projectId)
                ->first();
        }
        if ($table == 'approved') {
            $draft = DB::table('projects.prt_project_details')
                ->where('project_cd', $projectId)
                ->first();
        }

        $others = json_decode($draft->others, true);
        $mtn = $others['maintenance'];

        if ($user->department == 14 || $user->department == 3) {
            $roadIds = $mtn['asset_dtls']['asset_list'] ?? [];

            $roadNames = [];
            if (!empty($roadIds)) {
                $roadNames = DB::table('asset_road_details')
                    ->whereIn('rd_system_id', $roadIds)
                    ->pluck('rd_name')
                    ->toArray();
            }

            $getNames = function ($items, $table, $column, $valueField) {
                if (empty($items))
                    return [];

                return DB::table($table)
                    ->whereIn($column, $items)
                    ->pluck($valueField)
                    ->toArray();
            };

            $culverts = [];
            $bridges = [];
            $walls = [];

            foreach (($mtn['sub_asset_dtls'] ?? []) as $subAsset) {

                $type = $subAsset['sub_asset_type_cd'] ?? null;
                $list = $subAsset['sub_asset_list'] ?? [];

                switch ($type) {
                    case 0: // Culverts
                        $culverts = $getNames($list, 'asset_road_cdwork_details', 'rd_cdwork_cd', 'culvert_no');
                        break;

                    case 1: // Bridges
                        $bridges = $getNames($list, 'asset_road_bridge_details', 'rd_bridge_cd', 'bridge_name');
                        break;

                    case 16: // Walls
                        $walls = $getNames($list, 'asset_protection_wall_details', 'protection_wall_cd', 'protection_wall_cd');
                        break;
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'road_names' => $roadNames,
                    'culverts' => $culverts,
                    'bridges' => $bridges,
                    'walls' => $walls,
                ]
            ]);
        }

        if ($user->department == 6) {
            $buildingId = $mtn['building_id'] ?? null;
            return response()->json([
                'status' => 'success',
                'data' => [
                    'building_id' => $buildingId
                ]
            ]);
        }

        if ($user->department == 15) {

            $vehicles = collect();
            $equipments = collect();

            if (!empty($mtn['vehicles'])) {
                $vehicles = DB::table('mechanicals.asset_mech_vehicles_details')
                    ->whereIn('vehicle_asset_cd', $mtn['vehicles'])
                    ->get();
            }

            if (!empty($mtn['equipments'])) {
                $equipments = DB::table('mechanicals.asset_mech_equipment_details')
                    ->whereIn('euipment_cd', $mtn['equipments'])
                    ->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'vehicles' => $vehicles->values(),
                    'equipments' => $equipments->values()
                ]
            ]);
        }
    }
    // Function to store project-scheme-funding-mapping details: Nitish
    private function storeProjectSchemeFundingMapping(string $projectCd, float $workOrderAmount, int $scheme_id)
    {
        // Step0: Get all the scheme funding mapping records
        $schemeFundings = DB::connection(self::DB_CONNECTION)
            ->table("projects.prm_scheme_funding_mapping")
            ->select('id', 'funding_percentage')
            ->where('scheme_id', $scheme_id)
            ->get();

        if ($schemeFundings->isEmpty()) {
            // throw new \RuntimeException("No scheme funding mapping found for scheme_id: $scheme_id");
            Log::info("No scheme funding mapping is available for project id: " . $projectCd);
        }

        // Step1: Loop through all the scheme funding mapping records
        // Step2: Calculate the amount for each funding agency based on the percentage
        // Step3: Store the project-scheme-funding-mapping details
        foreach ($schemeFundings as $schemeFunding) {
            $amount = ($workOrderAmount * $schemeFunding->funding_percentage) / 100;
            DB::connection(self::DB_CONNECTION)
                ->table("projects.prt_project_scheme_funding_mapping")->insert([
                    'project_cd' => $projectCd,
                    'scheme_funding_id' => $schemeFunding->id,
                    'amount' => $amount,
                ]);
        }
    }
    // new store method after client review
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg|max:1024',
            'workOrder' => 'file|mimes:pdf|max:2048',
            'projectPlan' => 'file|mimes:pdf|max:2048',
            'drpDocument' => 'file|mimes:pdf|max:2048',
            'designDoc' => 'file|mimes:pdf|max:2048',
        ], [
            'images.*.image' => 'Each site image must be a valid image file.',
            'images.*.mimes' => 'Each site image must be a JPG or JPEG file.',
            'images.*.max' => 'Each site image must not be larger than 1 MB.',
        ]);

        if ($validator->fails()) {
            Log::warning('Project creation validation failed', [
                'user_id' => auth()->id(),
                'project_type' => $request->projectTypeSelect,
                'validation_errors' => $validator->errors()->toArray(),
                'uploaded_images' => collect($request->file('images', []))
                    ->map(fn($image) => [
                        'name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size_kb' => round($image->getSize() / 1024, 2),
                        'upload_error' => $image->getError(),
                    ])->values()->all(),
            ]);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // use the connection() to handle exception properly
        DB::connection(self::DB_CONNECTION)->beginTransaction();
        try {
            $userid = Auth::user()->id;
            $randomNumber = mt_rand(100, 999);
            $currentTime = time();
            $randomCode = $userid . $currentTime . $randomNumber;
            $parent_asset_cd = null;
            $department = $request->owner_dept_cd;
            $deptName = DB::table('department_details')->where('id', '=', $department)->value('dept_short_code');
            $year = date('Y');
            $prefix = 'PRJ_' . strtoupper($deptName) . '_' . $year . '_';

            // get technology type: Nitish
            $technologyType = DB::connection(self::DB_CONNECTION)
                ->table('prm_project_technology_types')
                ->where('tech_type_cd', $request->tech_type_cd)
                ->value('tech_type_descr');

            //saiful -- start
            $newRoadId = null;
            $newRoadName = null;
            $total_road_length = 0;
            $new_rd_catg_cd = null;
            //saiful -- end

            // Check for checker maker status
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('15'); // 15 is for PMS

            // generate project code
            $project_cd = $this->generateProjectCode($request, $makerCheckerStatus, $year, $prefix);

            $data = [
                'project_cd' => $project_cd,
                'project_name' => $request->project_name,
                'project_type_cd' => $request->projectTypeSelect,
                'owner_dept_cd' => $request->owner_dept_cd,
                'division_cd' => $request->division_cd,
                'sub_division_cd' => $request->sub_division_cd ?? null,
                'parent_asset_cd' => $parent_asset_cd,
                'project_start_date' => $request->project_start_date,
                'project_end_date' => $request->project_end_date,
                'est_proj_cost' => $request->est_proj_cost,
                'defect_liability_period' => $request->defect_liability_period,
                'work_order_amount' => $request->work_order_amount,
                'is_published' => "Y", // default value for published status Saiful
                'project_status_cd' => 1,
                'project_awarded_to' => $request->project_awarded_to,
                'site_eng_id' => null,
                'site_incharge_name' => null,
                'site_incharge_office_cd' => null,
                'site_incharge_ph_no' => null,
                'latitude' => $request->latitude ?? null,
                'longitude' => $request->longitude ?? null,
                'others' => json_encode([
                    // common info
                    'project_type' => $request->projectTypeSelect,
                    'tech_type_cd' => $request->tech_type_cd ?? 'null',
                    'tech_type_descr' => $technologyType,

                    // new technology info
                    'plastic_waste' => $request->plastic_waste ?? 'N',
                    'mixing_type' => $request->mixing_type ?? 'null',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $userid,
                'updated_by' => $userid,
                // New field added: nitish
                'work_order_no' => $request->work_order_no ?? null,
                'work_order_issue_date' => $request->work_order_issue_date ?? null,
                'scheme_cd' => $request->scheme_cd ?? null,
                //'funding_agency_cd' => $request->funding_agency_cd ?? null,
            ];

            if ($request->projectTypeSelect === 'UPG') {
                $others = json_decode($data['others'], true);

                if ($department == 14 || $department == 3) {
                    $roads = $request->input('roads', []);
                    $startChainages = $request->input('start_chainage', []);
                    $endChainages = $request->input('end_chainage', []);
                    $groupedRows = [];

                    for ($i = 0; $i < count($roads); $i++) {

                        $rowIndex = $i + 1;
                        $roadId = $roads[$i];
                        $currentStartChainage = $startChainages[$i] ?? null;
                        $currentEndChainage = $endChainages[$i] ?? null;

                        // ✅ Initialize if not exists
                        if (!isset($groupedRows[$roadId])) {
                            $groupedRows[$roadId] = [
                                'parent_asset_id' => $roadId,
                                'start_chainage' => $currentStartChainage,
                                'end_chainage' => $currentEndChainage,
                                'culverts' => [],
                                'bridges' => [],
                                'retaining_walls' => [],
                            ];
                        }

                        $groupedRows[$roadId]['culverts'] = array_merge(
                            $groupedRows[$roadId]['culverts'],
                            array_filter($request->input("culverts_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['bridges'] = array_merge(
                            $groupedRows[$roadId]['bridges'],
                            array_filter($request->input("bridges_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['retaining_walls'] = array_merge(
                            $groupedRows[$roadId]['retaining_walls'],
                            array_filter($request->input("walls_$rowIndex", []))
                        );
                    }

                    $rowsData = array_map(function ($row) {
                        return [
                            'parent_asset_id' => $row['parent_asset_id'],
                            'culverts' => array_values(array_unique($row['culverts'])),
                            'bridges' => array_values(array_unique($row['bridges'])),
                            'retaining_walls' => array_values(array_unique($row['retaining_walls'])),
                            'start_chainage' => $row['start_chainage'],
                            'end_chainage' => $row['end_chainage'],
                        ];
                    }, array_values($groupedRows));

                    $roadName = trim($request->new_road_name ?? '');
                    $roadLength = (float) ($request->road_length ?? 0);

                    // ✅ Check if ALL fields are empty
                    $isNewAssetEmpty =
                        $roadName === '' &&
                        $roadLength == 0 &&
                        $request->road_category === '' &&
                        $request->road_type === '' &&
                        $request->road_owner === '';

                    $newRoadId = $roadName !== '' ? (string) Str::uuid() : null;
                    //saiful -- Start
                    $newRoadName = $roadName;
                    $total_road_length = $request->total_road_length;
                    $new_rd_catg_cd = $request->road_category;
                    //saiful -- End
                    $upg = [
                        'upgraded_asset_dtls' => $rowsData,

                        'new_asset' => $isNewAssetEmpty ? null : [
                            'road_id' => $newRoadId,
                            'road_name' => $roadName !== '' ? $roadName : null,
                            'road_length' => $roadLength,
                            'road_category' => $request->road_category ?? '',
                            'road_type' => $request->road_type ?? '',
                            'road_owner' => $request->road_owner ?? '',
                        ],
                    ];

                    if ($request->input('ref_asset') === 'exist' && !empty($roads)) {
                        $upg['upgraded_roads'] = array_values(array_unique($roads));
                    }

                    $priorityRoads = $request->input('priority_roads', []);

                    $existingRoadIds = DB::table('asset_road_details')
                        ->pluck('rd_system_id')
                        ->toArray();

                    $priorityRoads = array_map(function ($item) use ($existingRoadIds, $newRoadId) {

                        if (!in_array($item, $existingRoadIds)) {
                            return $newRoadId;
                        }

                        return $item;
                    }, $priorityRoads);
                    //saiful -- Start
                    $first_upgraded_road_id = null;
                    //saiful -- End
                    if (!empty($priorityRoads)) {
                        $upg['road_sequence'] = [];

                        foreach (array_values($priorityRoads) as $index => $roadId) {
                            //saiful -- Start
                            if ($index == 0)
                                $first_upgraded_road_id = $roadId;
                            //saiful -- End
                            $upg['road_sequence'][] = [
                                'sequence' => $index + 1,
                                'road_id' => $roadId
                            ];
                        }
                    }
                    //saiful -- Start
                    $upg['merged_road_id'] = $newRoadId;
                    $upg['merged_road_name'] = $request->new_road_name ?? AssetRoadDetail::where('rd_system_id', $first_upgraded_road_id)->value('rd_name');
                    $upg['merged_road_type'] = $request->road_type ?? '';
                    $upg['merged_road_owner'] = $request->road_owner ?? '';
                    $upg['merged_road_length'] = (float) ($request->total_road_length ?? 0);
                    $upg['merged_road_category'] = $request->road_category ?? '';
                    //saiful -- End
                    $others['upgradation'] = $upg;
                }

                if ($department == 6) {

                    $buildingId = $request->input('upgBuildings');

                    $upg = [
                        'building_id' => $buildingId
                    ];

                    $building = DB::table('buildings.asset_building_details')
                        ->where('building_system_cd', $buildingId)
                        ->first();


                    $buildingType = $request->input('building_type_upg');
                    $owningDept = $request->input('owning_dept_upg');
                    $category = $request->input('buildingCategoryUpg');
                    $quarterNo = $request->input('quarter_no');
                    $isMaintained = $request->input('rdo_maintained_by_upg');

                    if (!empty($buildingType) && $buildingType != $building->building_type_cd) {
                        $upg['building_type_cd'] = $buildingType;
                    }

                    if (!empty($owningDept) && $owningDept != $building->asset_owning_dept_cd) {
                        $upg['owning_dept_cd'] = $owningDept;
                    }

                    //by dipshikha
                    if ($category !== null && $category !== '' && $category != $building->building_class_cd) {
                        $upg['building_category_cd'] = $category;
                    }
                    //end

                    if (!empty($isMaintained) && $isMaintained != $building->is_maintained_by_npwd) {
                        $upg['is_maintained_by_npwd'] = $isMaintained;
                    }

                    if (!empty($quarterNo)) {
                        if ($category == 0) {
                            if ($quarterNo != $building->qtr_no) {
                                $upg['quarter_no'] = $quarterNo;
                            }
                        } else {
                            if ($quarterNo != $building->bld_qtr_name) {
                                $upg['building_name'] = $quarterNo;
                            }
                        }
                    }

                    $others['upgradation'] = $upg;
                }

                if ($department == 15) {

                    $vehicles = array_filter($request->input('vehicle_type_cd', []));
                    $equipments = array_filter($request->input('equipment_type_cd', []));

                    $upg = [];

                    if (!empty($vehicles)) {
                        $upg['vehicles'] = array_values($vehicles);
                    }

                    if (!empty($equipments)) {
                        $upg['equipments'] = array_values($equipments);
                    }

                    $others['upgradation'] = $upg;
                }

                $data['others'] = json_encode($others);
            }


            if ($request->projectTypeSelect === 'MTN') {

                $others = json_decode($data['others'], true);

                if ($department == 14 || $department == 3) {

                    $rows = (int) $request->mnt_rowCount;

                    $roadData = [];
                    $culvertData = [];
                    $bridgeData = [];
                    $wallData = [];

                    for ($i = 1; $i <= $rows; $i++) {
                        $roads = array_filter($request->input("roads_mnt$i", []));
                        $culverts = array_filter($request->input("culverts_mnt_$i", []));
                        $bridges = array_filter($request->input("bridges_mnt_$i", []));
                        $walls = array_filter($request->input("walls_mnt_$i", []));

                        if (!empty($roads)) {
                            $roadData = array_merge($roadData, $roads);
                        }

                        if (!empty($culverts)) {
                            $culvertData = array_merge($culvertData, $culverts);
                        }

                        if (!empty($bridges)) {
                            $bridgeData = array_merge($bridgeData, $bridges);
                        }

                        if (!empty($walls)) {
                            $wallData = array_merge($wallData, $walls);
                        }
                    }

                    // Remove duplicates (optional but recommended)
                    $roadData = array_values(array_unique($roadData));
                    $culvertData = array_values(array_unique($culvertData));
                    $bridgeData = array_values(array_unique($bridgeData));
                    $wallData = array_values(array_unique($wallData));

                    $subAssets = [];

                    if (!empty($culvertData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 0,
                            "sub_asset_list" => $culvertData
                        ];
                    }

                    if (!empty($bridgeData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 1,
                            "sub_asset_list" => $bridgeData
                        ];
                    }

                    if (!empty($wallData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 16,
                            "sub_asset_list" => $wallData
                        ];
                    }

                    if (!empty($pavementData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 2,
                            "sub_asset_list" => $pavementData
                        ];
                    }

                    $assetDtls = null;

                    if (!empty($roadData)) {
                        $assetDtls = [
                            "asset_list" => $roadData,
                            "asset_type_cd" => 10
                        ];
                    }


                    $subAssetDtls = !empty($subAssets) ? $subAssets : null;


                    $others['maintenance'] = [
                        "asset_dtls" => $assetDtls,
                        "sub_asset_dtls" => $subAssetDtls
                    ];
                }

                if ($department == 6) {
                    $buildingId = $request->input('maintBuildings');
                    $mtn = [
                        'building_id' => $buildingId
                    ];

                    $others['maintenance'] = $mtn;
                }

                if ($department == 15) {

                    $vehicles = array_filter($request->input('vehicle_type_cd', []));
                    $equipments = array_filter($request->input('equipment_type_cd', []));

                    $mtn = [];

                    if (!empty($vehicles)) {
                        $mtn['vehicles'] = array_values($vehicles);
                    }

                    if (!empty($equipments)) {
                        $mtn['equipments'] = array_values($equipments);
                    }

                    $others['maintenance'] = $mtn;
                }

                $data['others'] = json_encode($others);
            }


            // new project for dept: R&B
            //by dipshikha
            if ($request->projectTypeSelect === 'NEW' && ($request->owner_dept_cd === '14' || $request->owner_dept_cd === '3')) {
                //end
                $newRoadId = 'ROAD_' . time() . '_' . rand(1000, 9999);
                $others = json_decode($data['others'], true);
                $others['new_road_id'] = $newRoadId;
                $others['new_road_name'] = $request->slnewRdNew;
                $others['new_road_length'] = $request->rdLength;
                $others['road_category'] = $request->road_category_new;
                $others['road_type'] = $request->road_type_new;
                $others['road_owner'] = $request->road_owner_new;
                $data['others'] = json_encode($others);
                //Saiful -- Start
                $newRoadName = $request->slnewRdNew;
                $total_road_length = $request->rdLength;
                $new_rd_catg_cd = $request->road_category_new;
                //Saiful -- End
            }

            // new project for dept: Housing
            if ($request->projectTypeSelect === 'NEW' && $request->owner_dept_cd === '6') {
                $buildingClass = AssetMasterBuildingClass::select('building_class_descr')
                    ->where('building_class_cd', $request->building_class_cd)
                    ->first();
                $buildingLocation = AssetMasterBuildingLocation::select('location_name')
                    ->where('location_cd', $request->building_location_cd)
                    ->first();
                $buildingType = AssetMasterBuildingType::select('building_type_descr')
                    ->where('building_type_cd', $request->building_type_cd)
                    ->first();
                $buildingOwner = AssetMasterDeptOfState::select('dept_name')
                    ->where('id', $request->owning_dept)
                    ->first();

                $newBuildingId = 'BLD_' . time() . '_' . rand(1000, 9999);
                $others = json_decode($data['others'], true);
                $others['new_building_id'] = $newBuildingId;
                $others['new_building_maintain_by_npwd'] = $request->maintained_by;
                $others['new_building_lat'] = $request->asset_geo_location_lat;
                $others['new_building_lng'] = $request->asset_geo_location_lng;
                $others['new_building_class_cd'] = $request->building_class_cd;
                $others['new_building_class_name'] = $buildingClass->building_class_descr;
                $others['new_building_location_cd'] = $request->building_location_cd;
                $others['new_building_location_name'] = $buildingLocation->location_name;
                $others['qtr_no'] = $request->quarter_no;
                $others['bld_qtr_name'] = $request->building_name;
                $others['building_type_cd'] = $request->building_type_cd;
                $others['building_type_name'] = $buildingType->building_type_descr;
                $others['asset_owning_dept_cd'] = $request->owning_dept;
                $others['owning_dept_name'] = $buildingOwner->dept_name;
                $data['others'] = json_encode($others);
            }

            if ($makerCheckerStatus === 'Y') {
                $extraData = [
                    'sent_for_finalize' => 'N',
                ];
                $data = array_merge($data, $extraData);
                $user = Auth::user();
                $office_cd = $user->office;
                $rootPath = config('filesystems.disks.external.root');

                $project = PrtProjectDetailsDraft::create($data);
            } else {
                // $extraData = [
                //     'approved_by' => $userid,
                //     'approved_at' => now()
                // ];
                // $project = PrtProjectDetail::create(array_merge($extraData, $data));

                // save in the draft table to avoid conflicts with the main table
                // since, it is important to must go through the maker checker process
                $extraData = [
                    'sent_for_finalize' => 'N',
                ];
                $data = array_merge($data, $extraData);
                $user = Auth::user();
                $office_cd = $user->office;
                $rootPath = config('filesystems.disks.external.root');
                $project = PrtProjectDetailsDraft::create($data);
            }

            if ($project) {
                $this->handleDocument($request, $randomCode, $project_cd);
                $this->storeProjectSchemeFundingMapping($project_cd, $request->work_order_amount, $request->scheme_cd);

                DB::connection(self::DB_CONNECTION)->commit();

                if ($request->hasFile('road_kml_file')) {
                    $file = $request->file('road_kml_file');
                    $uniqueFileName = $project_cd . '.kml';
                    $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/KML_FILES';

                    if (!Storage::exists($folderPath)) {
                        Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $project_cd . '.geojson';
                    AssetRoadDocumentKmlFileDetails::create([
                        'rd_system_id' => $project_cd,
                        'file_path' => $completeFilePath,
                        'geojson_file_path' => $geojson_file_path,
                        'file_type' => 'kml',
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'project_cd' => $project_cd,
                        'created_at_office_cd' => $office_cd,
                    ]);

                    //Saiful -- Start -- After ulpoad the kml File, convert that file into geojson -- Start
                    //Call POST API To convert the KML file to Shape File
                    $kml_file_api = config('customconfigpath.KML_FILE_CONVERT_API');
                    // Create a Guzzle HTTP client
                    $client = new Client();

                    // Make a POST request to the API
                    if ($request->division_cd !== null) {
                        $divDetails = DB::table('asset_master_divisions')
                            ->select('div_short_code', 'division_cd', 'division_name')
                            ->where('division_cd', $request->division_cd)
                            ->get()->first();
                        $divShrtCd = $divDetails->div_short_code;
                        $division_name = $divDetails->division_name;
                    }
                    $categoryDetails = DB::table('asset_master_road_category')
                        ->select('rd_catg_short_code', 'rd_catg_descr')
                        ->where('rd_catg_cd', $new_rd_catg_cd)
                        ->get()->first();

                    if ($categoryDetails)
                        $rd_catg_descr = $categoryDetails->rd_catg_descr;

                    $response = $client->request('POST', $kml_file_api, [
                        'json' => [
                            'uid' => auth()->id(),
                            'kml_file_path' => $completeFilePath,
                            'road_id' => $project_cd,
                            'road_name' => $newRoadName,
                            'road_length' => $total_road_length,
                            'road_category' => $rd_catg_descr,
                            'division_cd' => $request->division_cd,
                            'division_name' => $division_name
                        ]
                    ]);


                    // $json_data= json_decode($response->getBody()->getContents());
                    $json_data = json_decode($response->getBody());
                    if ($json_data->status == False) {
                        DB::rollback();
                        return redirect()->back()
                            ->with('failed', 'Failed to add road details. Uploaded KML File does not have Roads Layer Structure!!!!')
                            ->with('kmlFormatIssue', 'KML File Not In Proper Format');
                    } else {
                        DB::table('asset_road_details_draft')
                            ->where('rd_system_id', $newRoadId)
                            ->update([
                                'lat' => $json_data->center_lat,
                                'lng' => $json_data->center_lng
                            ]); // this query never get executed as road id does not exist in draft table
                    }
                }
                //Saiful -- End -- After ulpoad the kml File, convert that file into geojson -- End
                return redirect()->back()->with('success', 'Project details saved successfully!');
            } else {
                DB::connection(self::DB_CONNECTION)->rollBack();
                return redirect()->back()->with('error', 'Project details failed to save!!!');
            }
        } catch (QueryException $e) {
            DB::connection(self::DB_CONNECTION)->rollBack();
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
            DB::connection(self::DB_CONNECTION)->rollBack();
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function update(Request $request)
    {

        DB::beginTransaction();
        try {
            $userid = Auth::user()->id;
            //saiful -- start
            $newRoadId = null;
            $newRoadName = null;
            $total_road_length = 0;
            $new_rd_catg_cd = null;
            //saiful -- end
            // ✅ IMPORTANT: get existing project
            $project_cd = $request->project_cd;

            $technologyType = DB::connection(self::DB_CONNECTION)
                ->table('prm_project_technology_types')
                ->where('tech_type_cd', $request->tech_type_cd)
                ->value('tech_type_descr');

            $project = PrtProjectDetailsDraft::where('project_cd', $project_cd)->first();

            if (!$project) {
                return redirect()->back()->with('error', 'Project not found!');
            }

            $others = json_decode($project->others, true) ?? [];
            $others['tech_type_cd'] = $request->tech_type_cd;
            $others['tech_type_descr'] = $technologyType;


            $data = [
                'project_name' => $request->project_name,
                'project_type_cd' => $request->projectTypeSelect,
                'owner_dept_cd' => $request->owner_dept_cd,
                'division_cd' => $request->division_cd,
                'sub_division_cd' => $request->sub_division_cd ?? null,
                'project_start_date' => $request->project_start_date,
                'project_end_date' => $request->project_end_date,
                'est_proj_cost' => $request->est_proj_cost,
                'defect_liability_period' => $request->defect_liability_period,
                'work_order_amount' => $request->work_order_amount,
                'is_published' => "Y",
                'project_awarded_to' => $request->project_awarded_to,
                'others' => json_encode([
                    // common info
                    'project_type' => $request->projectTypeSelect,
                ]),
                //by dipshikha
                'work_order_no' => $request->work_order_no,
                'work_order_issue_date' => $request->work_order_issue_date,
                'scheme_cd' => $request->scheme_cd,
                //end
                'latitude' => $request->latitude ?? null,
                'longitude' => $request->longitude ?? null,
                'updated_at' => now(),
                'updated_by' => $userid,
            ];


            if ($request->projectTypeSelect === 'UPG') {
                //by dipshikha
                if ($request->owner_dept_cd == 14 || $request->owner_dept_cd == 3) {
                    //end
                    $roads = $request->input('roads', []);
                    $startChainages = $request->input('start_chainage', []);
                    $endChainages = $request->input('end_chainage', []);
                    $groupedRows = [];

                    for ($i = 0; $i < count($roads); $i++) {

                        $rowIndex = $i + 1;
                        $roadId = $roads[$i];
                        $currentStartChainage = $startChainages[$i] ?? null;
                        $currentEndChainage = $endChainages[$i] ?? null;

                        if (!isset($groupedRows[$roadId])) {
                            $groupedRows[$roadId] = [
                                'parent_asset_id' => $roadId,
                                'culverts' => [],
                                'bridges' => [],
                                'retaining_walls' => [],
                                'start_chainage' => $currentStartChainage,
                                'end_chainage' => $currentEndChainage,
                            ];
                        }


                        $groupedRows[$roadId]['culverts'] = array_merge(
                            $groupedRows[$roadId]['culverts'],
                            array_filter($request->input("culverts_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['bridges'] = array_merge(
                            $groupedRows[$roadId]['bridges'],
                            array_filter($request->input("bridges_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['retaining_walls'] = array_merge(
                            $groupedRows[$roadId]['retaining_walls'],
                            array_filter($request->input("walls_$rowIndex", []))
                        );
                    }

                    $rowsData = array_values(array_map(function ($row) {
                        return [
                            'parent_asset_id' => $row['parent_asset_id'],
                            'culverts' => array_values(array_unique($row['culverts'])),
                            'bridges' => array_values(array_unique($row['bridges'])),
                            'retaining_walls' => array_values(array_unique($row['retaining_walls'])),
                            'start_chainage' => $row['start_chainage'],
                            'end_chainage' => $row['end_chainage'],
                        ];
                    }, $groupedRows));


                    $roadName = trim($request->new_road_name ?? '');
                    $roadLength = (float) ($request->road_length ?? 0);

                    $upg = [
                        'upgraded_asset_dtls' => $rowsData,
                        'new_asset' => null
                    ];

                    if ($roadName !== '' || $roadLength > 0 || $request->road_category || $request->road_type || $request->road_owner) {

                        $newRoadId = $roadName !== '' ? (string) Str::uuid() : null;

                        $upg['new_asset'] = [

                            'road_id' => $newRoadId,
                            'road_name' => $roadName ?: null,
                            'road_length' => $roadLength,
                            'road_category' => $request->road_category ?? '',
                            'road_type' => $request->road_type ?? '',
                            'road_owner' => $request->road_owner ?? '',
                        ];

                        $priorityRoads = $request->input('priority_roads', []);

                        $existingRoadIds = DB::table('asset_road_details')
                            ->pluck('rd_system_id')
                            ->toArray();

                        $priorityRoads = array_map(function ($item) use ($existingRoadIds, $newRoadId) {

                            if (!in_array($item, $existingRoadIds)) {
                                return $newRoadId;
                            }

                            return $item;
                        }, $priorityRoads);
                        $first_upgraded_road_id = null;
                        if (!empty($priorityRoads)) {
                            $upg['road_sequence'] = [];

                            foreach (array_values($priorityRoads) as $index => $roadId) {
                                //saiful -- Start
                                if ($index == 0)
                                    $first_upgraded_road_id = $roadId;
                                //saiful -- End
                                $upg['road_sequence'][] = [
                                    'sequence' => $index + 1,
                                    'road_id' => $roadId
                                ];
                            }
                        }
                    }

                    if ($request->input('ref_asset') === 'exist' && !empty($roads)) {
                        $upg['upgraded_roads'] = array_values(array_unique($roads));
                    } else {
                        if (AssetRoadDocumentKmlFileDetails::where('project_cd', $project_cd)->exists()) {
                            AssetRoadDocumentKmlFileDetails::where('project_cd', $project_cd)->delete();
                        }
                    }

                    //saiful -- Start
                    $upg['merged_road_id'] = $newRoadId;
                    $upg['merged_road_name'] = $roadName ?? AssetRoadDetail::where('rd_system_id', $first_upgraded_road_id)->value('rd_name');
                    $upg['merged_road_type'] = $request->road_type ?? '';
                    $upg['merged_road_owner'] = $request->road_owner ?? '';
                    $upg['merged_road_length'] = (float) ($request->total_road_length ?? 0);
                    $upg['merged_road_category'] = $request->road_category ?? '';
                    //saiful -- End


                    $others['upgradation'] = $upg;
                    //saiful -- Start
                    $newRoadName = $roadName;
                    $total_road_length = $request->total_road_length;
                    $new_rd_catg_cd = $request->road_category;
                    //saiful -- End
                }

                if ($request->owner_dept_cd == 6) {

                    $buildingId = $request->input('upgBuildings');

                    $upg = [
                        'building_id' => $buildingId
                    ];

                    $building = DB::table('buildings.asset_building_details')
                        ->where('building_system_cd', $buildingId)
                        ->first();


                    $buildingType = $request->input('building_type_upg');
                    $owningDept = $request->input('owning_dept_upg');
                    $category = $request->input('buildingCategoryUpg');
                    $quarterNo = $request->input('quarter_no');
                    $isMaintained = $request->input('rdo_maintained_by_upg');

                    if (!empty($buildingType) && $buildingType != $building->building_type_cd) {
                        $upg['building_type_cd'] = $buildingType;
                    }

                    if (!empty($owningDept) && $owningDept != $building->asset_owning_dept_cd) {
                        $upg['owning_dept_cd'] = $owningDept;
                    }

                    if ($category !== null && $category !== '' && $category != $building->building_class_cd) {
                        $upg['building_category_cd'] = $category;
                    }

                    if (!empty($isMaintained) && $isMaintained != $building->is_maintained_by_npwd) {
                        $upg['is_maintained_by_npwd'] = $isMaintained;
                    }

                    if (!empty($quarterNo)) {
                        if ($category == 0) {
                            if ($quarterNo != $building->qtr_no) {
                                $upg['quarter_no'] = $quarterNo;
                            }
                        } else {
                            if ($quarterNo != $building->bld_qtr_name) {
                                $upg['building_name'] = $quarterNo;
                            }
                        }
                    }

                    $others['upgradation'] = $upg;
                }

                if ($request->owner_dept_cd == 15) {

                    $vehicles = array_filter($request->input('vehicle_type_cd', []));
                    $equipments = array_filter($request->input('equipment_type_cd', []));

                    $upg = [];

                    if (!empty($vehicles)) {
                        $upg['vehicles'] = array_values($vehicles);
                    }

                    if (!empty($equipments)) {
                        $upg['equipments'] = array_values($equipments);
                    }

                    $others['upgradation'] = $upg;
                }
            }

            if ($request->projectTypeSelect === 'MTN') {

                //by dipshikha
                if ($request->owner_dept_cd == 14 || $request->owner_dept_cd == 3) {
                    //end
                    $rows = (int) $request->mnt_rowCount;

                    $roadData = [];
                    $culvertData = [];
                    $bridgeData = [];
                    $wallData = [];
                    $pavementData = [];

                    for ($i = 1; $i <= $rows; $i++) {

                        $roadData = array_merge($roadData, array_filter($request->input("roads_mnt$i", [])));
                        $culvertData = array_merge($culvertData, array_filter($request->input("culverts_mnt_$i", [])));
                        $bridgeData = array_merge($bridgeData, array_filter($request->input("bridges_mnt_$i", [])));
                        $wallData = array_merge($wallData, array_filter($request->input("walls_mnt_$i", [])));
                        $pavementData = array_merge($pavementData, array_filter($request->input("pavements_mnt_$i", [])));
                    }



                    $subAssetDtls = [];

                    if (!empty($culvertData)) {
                        $subAssetDtls[] = [
                            "sub_asset_type_cd" => 0,
                            "sub_asset_list" => array_values(array_unique($culvertData))
                        ];
                    }

                    if (!empty($bridgeData)) {
                        $subAssetDtls[] = [
                            "sub_asset_type_cd" => 1,
                            "sub_asset_list" => array_values(array_unique($bridgeData))
                        ];
                    }

                    if (!empty($wallData)) {
                        $subAssetDtls[] = [
                            "sub_asset_type_cd" => 16,
                            "sub_asset_list" => array_values(array_unique($wallData))
                        ];
                    }

                    if (!empty($pavementData)) {
                        $subAssetDtls[] = [
                            "sub_asset_type_cd" => 2,
                            "sub_asset_list" => array_values(array_unique($pavementData))
                        ];
                    }

                    $others['maintenance'] = [
                        "asset_dtls" => !empty($roadData) ? [
                            "asset_list" => array_values(array_unique($roadData)),
                            "asset_type_cd" => 10
                        ] : null,

                        "sub_asset_dtls" => !empty($subAssetDtls) ? $subAssetDtls : null
                    ];
                }

                if ($request->owner_dept_cd == 6) {
                    $buildingId = $request->input('maintBuildings');
                    $mtn = [
                        'building_id' => $buildingId
                    ];

                    $others['maintenance'] = $mtn;
                }

                if ($request->owner_dept_cd == 15) {

                    $vehicles = array_filter($request->input('vehicle_type_cd', []));
                    $equipments = array_filter($request->input('equipment_type_cd', []));

                    $mtn = [];

                    if (!empty($vehicles)) {
                        $mtn['vehicles'] = array_values($vehicles);
                    }

                    if (!empty($equipments)) {
                        $mtn['equipments'] = array_values($equipments);
                    }

                    $others['maintenance'] = $mtn;
                }
            }

            //by dipshikha
            if ($request->projectTypeSelect === 'NEW' && ($request->owner_dept_cd === '14' || $request->owner_dept_cd === '3')) {
                //end
                $others['new_road_id'] = $others['new_road_id'] ?? ('ROAD_' . time() . '_' . rand(1000, 9999));
                $others['new_road_name'] = $request->slnewRdNew;
                $others['new_road_length'] = $request->rdLength;
                $others['road_category'] = $request->road_category_new;
                $others['road_type'] = $request->road_type_new;
                $others['road_owner'] = $request->road_owner_new;
                //Saiful -- Start
                $newRoadName = $request->slnewRdNew;
                $total_road_length = $request->rdLength;
                $new_rd_catg_cd = $request->road_category_new;
                //Saiful -- End
            }

            if ($request->projectTypeSelect === 'NEW' && $request->owner_dept_cd === '6') {
                $newBuildingId = 'BLD_' . time() . '_' . rand(1000, 9999);
                $others['new_building_id'] = $newBuildingId;
                $others['new_building_maintain_by_npwd'] = $request->rdo_maintained_by;
                $others['new_building_lat'] = $request->asset_geo_location_lat;
                $others['new_building_lng'] = $request->asset_geo_location_lng;
                $others['new_building_class_cd'] = $request->building_class_cd;
                $others['new_building_location_cd'] = $request->building_location_cd;
            }

            $data['others'] = json_encode($others);

            $project->update($data);

            if ($request->deleted_images) {
                $deletedImages = json_decode($request->deleted_images, true);

                if (!empty($deletedImages)) {
                    DB::table('projects.prt_project_images_details')
                        ->whereIn('id', $deletedImages)
                        ->delete();
                }
            }

            if ($request->deleted_documents) {
                $deletedDocuments = json_decode($request->deleted_documents, true);

                if (!empty($deletedDocuments)) {
                    DB::table('projects.prt_project_document_details')
                        ->whereIn('id', $deletedDocuments)
                        ->delete();
                }
            }

            if ($request->hasFile('images') || $request->hasFile('workOrder') || $request->hasFile('projectPlan') || $request->hasFile('drpDocument') || $request->hasFile('designDoc')) {
                $randomNumber = mt_rand(100, 999);
                $currentTime = time();
                $randomCode = Auth::id() . $currentTime . $randomNumber;
                $this->handleDocument($request, $randomCode, $project_cd);
            }

            $user = Auth::user();
            $office_cd = $user->office;
            $rootPath = config('filesystems.disks.external.root');

            if ($request->hasFile('road_kml_file')) {
                AssetRoadDocumentKmlFileDetails::where('project_cd', $project_cd)->delete();
                $file = $request->file('road_kml_file');
                $uniqueFileName = $project_cd . '.kml';
                $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/KML_FILES';

                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $project_cd . '.geojson';
                AssetRoadDocumentKmlFileDetails::create([
                    'rd_system_id' => $project_cd,
                    'file_path' => $completeFilePath,
                    'geojson_file_path' => $geojson_file_path,
                    'file_type' => 'kml',
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'project_cd' => $project_cd,
                    'created_at_office_cd' => $office_cd,
                ]);


                //Saiful -- Start -- After ulpoad the kml File, convert that file into geojson -- Start
                //Call POST API To convert the KML file to Shape File
                $kml_file_api = config('customconfigpath.KML_FILE_CONVERT_API');
                // Create a Guzzle HTTP client
                $client = new Client();

                // Make a POST request to the API
                if ($request->division_cd !== null) {
                    $divDetails = DB::table('asset_master_divisions')
                        ->select('div_short_code', 'division_cd', 'division_name')
                        ->where('division_cd', $request->division_cd)
                        ->get()->first();
                    $divShrtCd = $divDetails->div_short_code;
                    $division_name = $divDetails->division_name;
                }
                $categoryDetails = DB::table('asset_master_road_category')
                    ->select('rd_catg_short_code', 'rd_catg_descr')
                    ->where('rd_catg_cd', $new_rd_catg_cd)
                    ->get()->first();

                if ($categoryDetails)
                    $rd_catg_descr = $categoryDetails->rd_catg_descr;

                $response = $client->request('POST', $kml_file_api, [
                    'json' => [
                        'uid' => auth()->id(),
                        'kml_file_path' => $completeFilePath,
                        'road_id' => $project_cd,
                        'road_name' => $newRoadName,
                        'road_length' => $total_road_length,
                        'road_category' => $rd_catg_descr,
                        'division_cd' => $request->division_cd,
                        'division_name' => $division_name
                    ]
                ]);


                // $json_data= json_decode($response->getBody()->getContents());
                $json_data = json_decode($response->getBody());
                if ($json_data->status == False) {
                    DB::rollback();
                    return redirect()->back()
                        ->with('failed', 'Failed to add road details. Uploaded KML File does not have Roads Layer Structure!!!!')
                        ->with('kmlFormatIssue', 'KML File Not In Proper Format');
                } else {
                    DB::table('asset_road_details_draft')
                        ->where('rd_system_id', $newRoadId)
                        ->update([
                            'lat' => $json_data->center_lat,
                            'lng' => $json_data->center_lng
                        ]); // this query never get executed as road id does not exist in draft table
                }
                //Saiful -- End -- After ulpoad the kml File, convert that file into geojson -- End
            }

            DB::commit();

            return redirect()->back()->with('success', 'Project updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Update Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Update failed!');
        }
    }

    public function getDraftedRoadsKml(Request $request, $project_cd)
    {
        try {

            $division_cd = null;
            $division_name = null;
            $draftRoadsGeoJsonFilePath = null;
            $final_geojson_file_path_with_file_name = null;
            $draft_road_data = null;
            $approved_divisions_road_data = null;
            $lat = null;
            $lng = null;

            $rootPath = config('filesystems.disks.external.root');

            if ($request->verified == 'verified') {

                $objProjectDetails = DB::table('projects.prt_project_details as pd')
                    ->select('pd.division_cd')
                    ->where('pd.project_cd', $project_cd)
                    ->first();
            } else {

                $objProjectDetails = DB::table('projects.prt_project_details_draft as pd')
                    ->select('pd.division_cd')
                    ->where('pd.project_cd', $project_cd)
                    ->first();
            }

            if (!$objProjectDetails) {
                return [
                    "status" => false,
                    "coordinates" => "Project details not found"
                ];
            }

            $division_cd = $objProjectDetails->division_cd;

            $draftRoadsGeoJsonFilePath = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/ROADS/ROAD_WISE_GEO_JSON_FILES/' . $project_cd . '.geojson';

            if (!empty($division_cd)) {
                $final_geojson_file_path_with_file_name = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/ROADS/DIVISION_WISE_FINAL_GEO_JSON_FILES/' . $division_cd . '.geojson';
            }

            if (File::exists($draftRoadsGeoJsonFilePath)) {
                $draft_road_data = File::get($draftRoadsGeoJsonFilePath);
            }

            if (!empty($final_geojson_file_path_with_file_name) && File::exists($final_geojson_file_path_with_file_name)) {
                $approved_divisions_road_data = File::get($final_geojson_file_path_with_file_name);
            }

            return [
                'status' => true,
                "approved_geojson_data" => $approved_divisions_road_data,
                'draft_road_geojson_data' => $draft_road_data,
                'div_lat' => null,
                'div_lng' => null
            ];
        } catch (Exception $e) {

            Log::error($e->getMessage());

            return [
                'status' => false,
                'coordinates' => 'Some technical issue'
            ];
        }
    }

    public function getRequestedRoadsKml(Request $request, $project_cd)
    {
        try {

            $division_cd = null;
            $division_name = null;
            $draftRoadsGeoJsonFilePath = null;
            $final_geojson_file_path_with_file_name = null;
            $draft_road_data = null;
            $approved_divisions_road_data = null;
            $lat = null;
            $lng = null;

            $rootPath = config('filesystems.disks.external.root');



            $objProjectDetails = DB::table('projects.prt_project_details as pd')
                ->select('pd.division_cd')
                ->where('pd.project_cd', $project_cd)
                ->first();



            if (!$objProjectDetails) {
                return [
                    "status" => false,
                    "coordinates" => "Project details not found"
                ];
            }

            $division_cd = $objProjectDetails->division_cd;

            $draftRoadsGeoJsonFilePath = $request->filePath;
            if (!empty($division_cd)) {
                $final_geojson_file_path_with_file_name = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/ROADS/DIVISION_WISE_FINAL_GEO_JSON_FILES/' . $division_cd . '.geojson';
            }

            if (File::exists($draftRoadsGeoJsonFilePath)) {
                $draft_road_data = File::get($draftRoadsGeoJsonFilePath);
            }

            if (!empty($final_geojson_file_path_with_file_name) && File::exists($final_geojson_file_path_with_file_name)) {
                $approved_divisions_road_data = File::get($final_geojson_file_path_with_file_name);
            }

            return [
                'status' => true,
                "approved_geojson_data" => $approved_divisions_road_data,
                'draft_road_geojson_data' => $draft_road_data,
                'div_lat' => null,
                'div_lng' => null
            ];
        } catch (Exception $e) {

            Log::error($e->getMessage());

            return [
                'status' => false,
                'coordinates' => 'Some technical issue'
            ];
        }
    }

    public function sendPMSDetailsFinalization(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $project_cd = $request->input('assetList');
                $status = DB::table('projects.prt_project_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('project_cd', $project_cd)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Bridge Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Throwable $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function view(Request $request)
    {
        // dd($request->all());
        $project_cd = $request->input('project_cd');
        $assetLists = AssetMasterRoadSubAsset::select('sub_asset_cd', 'sub_assets_descr')->get();
        if ($project_cd) {
            $project = DB::table('projects.prt_project_details_draft as project_details')
                ->select([
                    'project_details.project_cd',
                    'project_details.project_name',
                    'project_details.project_type_cd',
                    'project_details.owner_dept_cd',
                    'project_details.division_cd',
                    'project_details.sub_division_cd',
                    'project_details.parent_asset_cd',
                    'project_details.project_start_date',
                    'project_details.project_end_date',
                    'project_details.est_proj_cost',
                    'project_details.defect_liability_period',
                    'project_details.work_order_amount',
                    // New
                    'project_details.work_order_no',
                    'project_details.work_order_issue_date',
                    'project_details.scheme_cd',
                    // End
                    'project_details.project_status_cd',
                    'project_details.project_awarded_to',
                    'project_details.site_eng_id',
                    'project_details.site_incharge_name',
                    'project_details.site_incharge_office_cd',
                    'project_details.site_incharge_ph_no',
                    'project_details.latitude',
                    'project_details.longitude',
                    'project_details.others',
                    'project_details.reason_of_rejection',
                    'project_type.proj_type_descr as project_type',
                    'owner_dept.department_name as owner_department',
                    'division.division_name',
                    'sub_division.sub_div_name',
                    'site_incharge.office_name as site_incharge_office_name',
                    'project_status.project_status_descr as project_status',
                    'contractor.contractors_name as contractor_name',
                    'scheme.scheme_name'
                ])
                ->leftJoin(
                    'projects.prm_project_types as project_type',
                    'project_details.project_type_cd',
                    '=',
                    'project_type.proj_type_cd'
                )
                ->leftJoin(
                    'public.department_details as owner_dept',
                    'project_details.owner_dept_cd',
                    '=',
                    'owner_dept.id'
                )
                ->leftJoin(
                    'public.asset_master_divisions as division',
                    'project_details.division_cd',
                    '=',
                    'division.division_cd'
                )
                ->leftJoin(
                    'public.asset_master_sub_divisions as sub_division',
                    'project_details.sub_division_cd',
                    '=',
                    'sub_division.sub_div_cd'
                )
                ->leftJoin(
                    'projects.prm_project_status as project_status',
                    'project_details.project_status_cd',
                    '=',
                    'project_status.project_status_cd'
                )
                ->leftJoin(
                    'projects.prm_site_incharge_office_details as site_incharge',
                    'project_details.site_incharge_office_cd',
                    '=',
                    'site_incharge.office_cd'
                )
                ->leftJoin(
                    'projects.prt_contractor_details as contractor',
                    'project_details.project_awarded_to',
                    '=',
                    'contractor.regn_no'
                )
                ->leftJoin(
                    'projects.prm_scheme_details as scheme',
                    'project_details.scheme_cd',
                    '=',
                    'scheme.scheme_id'
                )
                ->where('project_details.project_cd', '=', $project_cd)
                ->first();


            $department = $project->owner_dept_cd;

            $items = DB::table('projects.prt_project_work_items_details AS d')
                ->leftJoin('projects.prm_item_of_work AS w', 'd.item_cd', '=', 'w.item_cd')
                ->leftJoin('projects.prm_item_units AS unit', 'w.unit_cd', '=', 'unit.unit_cd')
                ->select([
                    'd.id as wid_id',
                    'w.item_name AS name',
                    'unit.unit_descr AS unit',
                    'd.quantity AS qty',
                    'd.item_cd AS item_cd'
                ])
                ->where('d.project_cd', $project_cd)
                ->get();

            foreach ($items as $item) {

                $item->sub_items = DB::table('projects.prt_project_work_sub_items_details AS ps')
                    ->leftJoin(
                        'projects.prm_item_sub_item_of_work AS s',
                        'ps.sub_item_cd',
                        '=',
                        's.sub_item_cd'
                    )
                    ->where('ps.project_cd', $project_cd)
                    ->where('ps.item_cd', $item->item_cd)
                    ->pluck('s.sub_item_name');

                $item->work_plans = DB::table('projects.prt_project_work_plan_details as workPlan')
                    ->leftJoin('projects.prm_item_of_work as item', 'workPlan.wid_precedence_item_cd', '=', 'item.item_cd')
                    ->where('workPlan.project_cd', $project_cd)
                    ->where('workPlan.wid_id', $item->wid_id)
                    ->get([
                        'workPlan.plan_start_date',
                        'workPlan.plan_end_date',
                        'workPlan.wid_precedence_item_cd',
                        'item.item_name as precedence_item_name',
                    ]);
            }

            $schemeCd = $project->scheme_cd ?? null;
            $fundingAgencies = [];

            //by dipshikha
            $work_order_amount = $project->work_order_amount ?? 0;
            //end by dipshikha

            if ($schemeCd) {
                $fundingAgencies = DB::table('projects.prm_scheme_funding_mapping as sfm')
                    ->select(
                        'sfm.funding_agency_id',
                        'fad.agency_name',
                        'sfm.funding_percentage'
                    )
                    ->join('projects.prm_funding_agency_details as fad', 'sfm.funding_agency_id', '=', 'fad.funding_agency_id')
                    ->where('sfm.scheme_id', $schemeCd)
                    ->orderBy('sfm.funding_agency_id', 'asc')
                    ->get()
                    //by dipshikha
                    ->map(function ($agency) use ($work_order_amount) {

                        $percentage = (float) $agency->funding_percentage;

                        $agency->funding_amount =
                            ($work_order_amount * $percentage) / 100;

                        return $agency;
                    });
                //end by dipshikha
            }

            //by dipshikha
            $basePath = config('filesystems.disks.external.root');

            $images = DB::table('projects.prt_project_images_details')
                ->where('project_cd', $project_cd)
                ->get()
                ->map(function ($img) use ($basePath) {

                    $fullPath = str_replace('\\', '/', $img->image_path);
                    $basePathClean = str_replace('\\', '/', $basePath);

                    $relativePath = ltrim(str_replace($basePathClean, '', $fullPath), '/');

                    return [
                        'id' => $img->id,
                        'image_url' => url('asset-management/uploaded_docs/' . $relativePath),
                    ];
                });


            $documents = DB::table('projects.prt_project_document_details as d')
                ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
                ->where('d.project_cd', $project_cd)
                ->select(
                    'd.id',
                    'd.doc_catg',
                    'd.file_path',
                    'c.doc_catg_descr'
                )
                ->get()
                ->map(function ($doc) use ($basePath) {

                    $fullPath = str_replace('\\', '/', $doc->file_path);
                    $basePathClean = str_replace('\\', '/', $basePath);

                    $relativePath = ltrim(str_replace($basePathClean, '', $fullPath), '/');

                    return [
                        'id' => $doc->id,
                        'doc_catg' => $doc->doc_catg,
                        'label' => $doc->doc_catg_descr ?? $doc->doc_catg,
                        'file_url' => url('asset-management/uploaded_docs/' . $relativePath),
                    ];
                });

            $others = json_decode($project->others, true);

            $roadCategoryName = null;
            $roadTypeName = null;
            $roadOwnerName = null;
            $building = null;
            $vehicleDetails = [];
            $equipmentDetails = [];



            if ($department === 14 || $department === 3) {
                if (($others['project_type'] ?? null) === 'NEW') {
                    $roadCategoryCd = $others['road_category'] ?? null;
                    $roadTypeCd = $others['road_type'] ?? null;
                    $roadOwnerCd = $others['road_owner'] ?? null;
                } elseif (($others['project_type'] ?? null) === 'UPG') {
                    $newAsset = $others['upgradation']['new_asset'] ?? [];
                    $roadCategoryCd = $newAsset['road_category'] ?? null;
                    $roadTypeCd = $newAsset['road_type'] ?? null;
                    $roadOwnerCd = $newAsset['road_owner'] ?? null;
                }

                // Category
                if (!empty($roadCategoryCd)) {
                    $roadCategoryName = DB::table('asset_master_road_category')
                        ->where('rd_catg_cd', $roadCategoryCd)
                        ->value('rd_catg_descr');
                }

                // Type
                if ($roadTypeCd !== null && $roadTypeCd !== '') {
                    $roadTypeName = DB::table('asset_master_rd_type')
                        ->where('rd_type_cd', $roadTypeCd)
                        ->value('rd_type_descr');
                }

                // Owner
                if ($roadOwnerCd !== null && $roadOwnerCd !== '') {
                    $roadOwnerName = DB::table('asset_master_road_owner')
                        ->where('owner_cd', $roadOwnerCd)
                        ->value('owner_name');
                }
            } else if ($department === 6) {
                if (($others['project_type'] ?? null) === 'UPG') {
                    $upg = $others['upgradation'] ?? [];
                    if (!empty($upg['building_id'])) {
                        $building = DB::table('buildings.asset_building_details')
                            ->where('building_system_cd', $upg['building_id'])
                            ->first();

                        $building->building_type_cd = $upg['building_type_cd'] ?? $building->building_type_cd;
                        $building->building_type_name = DB::table('buildings.asset_master_building_types')
                            ->where('building_type_cd', $building->building_type_cd)
                            ->value('building_type_descr');

                        // Owning Department
                        $building->asset_owning_dept_cd = $upg['owning_dept_cd'] ?? $building->asset_owning_dept_cd;
                        $building->asset_owning_dept_name = DB::table('asset_master_dept_of_state')
                            ->where('id', $building->asset_owning_dept_cd)
                            ->value('dept_name');

                        // Building Category
                        $building->building_class_cd = $upg['building_category_cd'] ?? $building->building_class_cd;
                        $building->building_class_name = DB::table('buildings.asset_master_building_class')
                            ->where('building_class_cd', $building->building_class_cd)
                            ->value('building_class_descr');

                        // Building Location
                        $building->building_location_name = DB::table('buildings.asset_master_building_locations')
                            ->where('location_cd', $building->building_location_cd)
                            ->value('location_name');

                        // Maintained By NPWD
                        $building->is_maintained_by_npwd = $upg['is_maintained_by_npwd'] ?? $building->is_maintained_by_npwd;

                        // Quarter No / Building Name
                        if (isset($upg['quarter_no'])) {
                            $building->qtr_no = $upg['quarter_no'];
                        }

                        if (isset($upg['building_name'])) {
                            $building->bld_qtr_name = $upg['building_name'];
                        }
                    }
                } else if (($others['project_type'] ?? null) === 'MTN') {
                    $mtn = $others['maintenance'] ?? [];
                    if (!empty($mtn['building_id'])) {
                        $building = DB::table('buildings.asset_building_details')
                            ->where('building_system_cd', $mtn['building_id'])
                            ->first();

                        $building->building_type_name = DB::table('buildings.asset_master_building_types')
                            ->where('building_type_cd', $building->building_type_cd)
                            ->value('building_type_descr');

                        $building->asset_owning_dept_name = DB::table('asset_master_dept_of_state')
                            ->where('id', $building->asset_owning_dept_cd)
                            ->value('dept_name');

                        $building->building_class_name = DB::table('buildings.asset_master_building_class')
                            ->where('building_class_cd', $building->building_class_cd)
                            ->value('building_class_descr');

                        // Building Location
                        $building->building_location_name = DB::table('buildings.asset_master_building_locations')
                            ->where('location_cd', $building->building_location_cd)
                            ->value('location_name');
                    }
                }
            } else if ($department === 15) {
                if (($others['project_type'] ?? null) === 'UPG' || ($others['project_type'] ?? null) === 'MTN') {

                    $assetData = [];

                    if (($others['project_type'] ?? null) === 'UPG') {
                        $assetData = $others['upgradation'] ?? [];
                    } elseif (($others['project_type'] ?? null) === 'MTN') {
                        $assetData = $others['maintenance'] ?? [];
                    }

                    if (!empty($assetData['vehicles'])) {
                        foreach ($assetData['vehicles'] as $vehicleId) {
                            $vehicle = DB::table('mechanicals.asset_mech_vehicles_details as v')
                                ->where('v.vehicle_asset_cd', $vehicleId)
                                ->leftJoin(
                                    'mechanicals.asset_master_vehicle_types as vt',
                                    'v.vehicle_type',
                                    '=',
                                    'vt.veh_type_cd'
                                )
                                ->leftJoin(
                                    'mechanicals.asset_master_vehicle_makers as vm',
                                    'v.maker',
                                    '=',
                                    'vm.maker_cd'
                                )
                                ->leftJoin(
                                    'mechanicals.asset_master_fuel_types as ft',
                                    'v.fuel_type',
                                    '=',
                                    'ft.fuel_type_cd'
                                )
                                ->leftJoin(
                                    'mechanicals.asset_master_vehicle_conditions as vc',
                                    'v.vehicle_condition',
                                    '=',
                                    'vc.condition_cd'
                                )
                                ->select(
                                    'v.vehicle_asset_cd',
                                    'v.vehicle_name',
                                    'v.vehicle_regn_no',
                                    'v.chassis_no',
                                    'vt.veh_type_descr',
                                    'v.engine_no',
                                    'v.seating_capacity',
                                    'v.no_of_wheels',
                                    'vm.maker_name',
                                    'v.model',
                                    'ft.fuel_type_descr',
                                    'v.date_of_purchase',
                                    'v.purchase_cost',
                                    'vc.condition_descr',
                                    'v.laden_weight',
                                    'v.unladen_weight',
                                    'v.alloted_to',
                                    'v.alloted_from'
                                )
                                ->first();

                            if ($vehicle) {
                                $vehicleDetails[] = $vehicle;
                            }
                        }
                    }

                    if (!empty($assetData['equipments'])) {
                        foreach ($assetData['equipments'] as $equipmentId) {
                            $equipment = DB::table('mechanicals.asset_mech_equipment_details as v')
                                ->where('v.euipment_cd', $equipmentId)
                                ->leftJoin(
                                    'mechanicals.asset_master_equipment_conditions as vc',
                                    'v.equipment_condition_cd',
                                    '=',
                                    'vc.condition_cd'
                                )
                                ->select(
                                    'v.euipment_cd',
                                    'v.equipment_name',
                                    'v.serial_number',
                                    'v.purchase_year',
                                    'v.model_no',
                                    'v.purchase_cost',
                                    DB::raw("
                                        CASE
                                            WHEN v.is_under_waranty = 'Y' THEN 'Yes'
                                            WHEN v.is_under_waranty = 'N' THEN 'No'
                                            ELSE 'NA'
                                        END as is_under_waranty
                                    "),
                                    'vc.condition_descr'
                                )
                                ->first();

                            if ($equipment) {
                                $equipmentDetails[] = $equipment;
                            }
                        }
                    }
                }
            }

            //end by dipshikha

            if ($project)
                return view('pms.view', compact('project', 'items', 'assetLists', 'fundingAgencies', 'images', 'documents', 'roadCategoryName', 'roadTypeName', 'roadOwnerName', 'building', 'vehicleDetails', 'equipmentDetails'));
        }
    }

    function showFundingAgencies(Request $request)
    {
        $work_order_amt = $request->work_order_amount;
        $scheme_cd = $request->scheme_cd;
        $fundingAgencies = DB::table('projects.prm_scheme_funding_mapping as sfm')
            ->select(
                'sfm.funding_agency_id',
                'fad.agency_name',
                'sfm.funding_percentage'
            )
            ->join('projects.prm_funding_agency_details as fad', 'sfm.funding_agency_id', '=', 'fad.funding_agency_id')
            ->where('sfm.scheme_id', $scheme_cd)
            ->orderBy('sfm.funding_agency_id', 'asc')
            ->get();
        //done by dipshikha
        $fundingAgencies = $fundingAgencies->map(function ($agency) use ($work_order_amt) {

            $percentage = (float) $agency->funding_percentage;

            $agency->funding_amount =
                round(($work_order_amt * $percentage) / 100, 2);

            return $agency;
        });
        //end
        if ($fundingAgencies) {
            return response()->json([
                'status' => 200,
                'message' => 'Data fetch successfully!',
                'result' => $fundingAgencies
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Failed to fetch data!',
                'result' => null
            ]);
        }
    }


    public function store_old(Request $request)
    {
        $validation = $request->validate([
            'images.*' => 'image|mimes:jpeg,jpg|max:1024',
            'workOrder' => 'file|mimes:pdf|max:2048',
            'projectPlan' => 'file|mimes:pdf|max:2048',
            'drpDocument' => 'file|mimes:pdf|max:2048',
            'designDoc' => 'file|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $userid = Auth::user()->id;
            $randomNumber = mt_rand(100, 999);
            $currentTime = time();
            $randomCode = $userid . $currentTime . $randomNumber;
            $parent_asset_cd = null;
            $department = $request->owner_dept_cd;
            $deptName = DB::table('department_details')->where('id', '=', $department)->value('dept_short_code');
            $year = date('Y');
            $prefix = 'PRJ_' . strtoupper($deptName) . '_' . $year . '_';

            // Check for checker maker status
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('15'); // 15 is for PMS

            // generate project code
            $project_cd = $this->generateProjectCode($request, $makerCheckerStatus, $year, $prefix);

            $data = [
                'project_cd' => $project_cd,
                'project_name' => $request->project_name,
                'project_type_cd' => $request->projectTypeSelect,
                'owner_dept_cd' => $request->owner_dept_cd,
                'division_cd' => $request->division_cd,
                'sub_division_cd' => $request->sub_division_cd ?? null,
                'parent_asset_cd' => $parent_asset_cd,
                'project_start_date' => $request->project_start_date,
                'project_end_date' => $request->project_end_date,
                'est_proj_cost' => $request->est_proj_cost,
                'defect_liability_period' => $request->defect_liability_period,
                'work_order_amount' => $request->work_order_amount,
                'is_published' => "N",
                'project_status_cd' => 1,
                'project_awarded_to' => $request->project_awarded_to,
                'site_eng_id' => null,
                'site_incharge_name' => null,
                'site_incharge_office_cd' => null,
                'site_incharge_ph_no' => null,
                'latitude' => $request->latitude ?? null,
                'longitude' => $request->longitude ?? null,
                'others' => json_encode([
                    // common info
                    'project_type' => $request->projectTypeSelect,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $userid,
                'updated_by' => $userid,
                // New field added: nitish
                'work_order_no' => $request->work_order_no ?? null,
                'work_order_issue_date' => $request->work_order_issue_date ?? null,
                'scheme_cd' => $request->scheme_cd ?? null,
                //'funding_agency_cd' => $request->funding_agency_cd ?? null,
            ];

            if ($request->projectTypeSelect === 'UPG') {
                $others = json_decode($data['others'], true);

                if ($department == 14 || $department == 3) {

                    $roads = $request->input('roads', []);
                    $groupedRows = [];

                    for ($i = 0; $i < count($roads); $i++) {

                        $rowIndex = $i + 1;
                        $roadId = $roads[$i];

                        // ✅ Initialize if not exists
                        if (!isset($groupedRows[$roadId])) {
                            $groupedRows[$roadId] = [
                                'parent_asset_id' => $roadId,
                                'culverts' => [],
                                'bridges' => [],
                                'retaining_walls' => [],
                                'pavements' => [],
                                'new_culverts' => 0,
                                'new_bridges' => 0,
                                'new_retaining_walls' => 0,
                                'new_pavements' => 0,
                            ];
                        }

                        $groupedRows[$roadId]['culverts'] = array_merge(
                            $groupedRows[$roadId]['culverts'],
                            array_filter($request->input("culverts_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['bridges'] = array_merge(
                            $groupedRows[$roadId]['bridges'],
                            array_filter($request->input("bridges_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['retaining_walls'] = array_merge(
                            $groupedRows[$roadId]['retaining_walls'],
                            array_filter($request->input("walls_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['pavements'] = array_merge(
                            $groupedRows[$roadId]['pavements'],
                            array_filter($request->input("pavements_$rowIndex", []))
                        );

                        $groupedRows[$roadId]['new_culverts'] += (int) $request->input("new_culverts_$rowIndex", 0);
                        $groupedRows[$roadId]['new_bridges'] += (int) $request->input("new_bridges_$rowIndex", 0);
                        $groupedRows[$roadId]['new_retaining_walls'] += (int) $request->input("new_walls_$rowIndex", 0);
                        $groupedRows[$roadId]['new_pavements'] += (int) $request->input("new_pavements_$rowIndex", 0);
                    }

                    $rowsData = array_map(function ($row) {
                        return [
                            'parent_asset_id' => $row['parent_asset_id'],
                            'culverts' => array_values(array_unique($row['culverts'])),
                            'bridges' => array_values(array_unique($row['bridges'])),
                            'retaining_walls' => array_values(array_unique($row['retaining_walls'])),
                            'pavements' => array_values(array_unique($row['pavements'])),
                            'new_culverts' => $row['new_culverts'],
                            'new_bridges' => $row['new_bridges'],
                            'new_retaining_walls' => $row['new_retaining_walls'],
                            'new_pavements' => $row['new_pavements'],
                        ];
                    }, array_values($groupedRows));

                    $roadName = trim($request->new_road_name ?? '');
                    $roadLength = (float) ($request->road_length ?? 0);
                    $newCulverts = (int) ($request->new_culverts ?? 0);
                    $newBridges = (int) ($request->new_bridges ?? 0);
                    $newWalls = (int) ($request->new_retaining_walls ?? 0);
                    $newPavements = (int) ($request->new_pavements ?? 0);

                    // ✅ Check if ALL fields are empty
                    $isNewAssetEmpty =
                        $roadName === '' &&
                        $roadLength == 0 &&
                        $newCulverts == 0 &&
                        $newBridges == 0 &&
                        $newWalls == 0 &&
                        $newPavements == 0;

                    $upg = [
                        'upgraded_asset_dtls' => $rowsData,

                        'new_asset' => $isNewAssetEmpty ? null : [
                            'road_id' => $roadName !== '' ? (string) Str::uuid() : null,
                            'road_name' => $roadName !== '' ? $roadName : null,
                            'road_length' => $roadLength,

                            'culverts' => $newCulverts,
                            'bridges' => $newBridges,
                            'retaining_walls' => $newWalls,
                            'pavements' => $newPavements,
                        ],
                    ];

                    if ($request->input('ref_asset') === 'exist' && !empty($roads)) {
                        $upg['upgraded_roads'] = array_values(array_unique($roads));
                    }

                    $others['upgradation'] = $upg;
                }

                if ($department == 6) {

                    $buildingId = $request->input('upgBuildings');

                    $upg = [
                        'building_id' => $buildingId
                    ];

                    $building = DB::table('buildings.asset_building_details')
                        ->where('building_system_cd', $buildingId)
                        ->first();


                    $buildingType = $request->input('building_type_upg');
                    $owningDept = $request->input('owning_dept_upg');
                    $category = $request->input('buildingCategoryUpg');
                    $quarterNo = $request->input('quarter_no');
                    $isMaintained = $request->input('rdo_maintained_by_upg');

                    if (!empty($buildingType) && $buildingType != $building->building_type_cd) {
                        $upg['building_type_cd'] = $buildingType;
                    }

                    if (!empty($owningDept) && $owningDept != $building->asset_owning_dept_cd) {
                        $upg['owning_dept_cd'] = $owningDept;
                    }

                    if (!empty($category) && $category != $building->building_class_cd) {
                        $upg['building_category_cd'] = $category;
                    }

                    if (!empty($isMaintained) && $isMaintained != $building->is_maintained_by_npwd) {
                        $upg['is_maintained_by_npwd'] = $isMaintained;
                    }

                    if (!empty($quarterNo)) {
                        if ($category == 0) {
                            if ($quarterNo != $building->qtr_no) {
                                $upg['quarter_no'] = $quarterNo;
                            }
                        } else {
                            if ($quarterNo != $building->bld_qtr_name) {
                                $upg['building_name'] = $quarterNo;
                            }
                        }
                    }

                    $others['upgradation'] = $upg;
                }

                if ($department == 15) {

                    $vehicles = array_filter($request->input('vehicle_type_cd', []));
                    $equipments = array_filter($request->input('equipment_type_cd', []));

                    $upg = [];

                    if (!empty($vehicles)) {
                        $upg['vehicles'] = array_values($vehicles);
                    }

                    if (!empty($equipments)) {
                        $upg['equipments'] = array_values($equipments);
                    }

                    $others['upgradation'] = $upg;
                }

                $data['others'] = json_encode($others);
            }


            if ($request->projectTypeSelect === 'MTN') {

                $others = json_decode($data['others'], true);

                if ($department == 14 || $department == 3) {

                    $rows = (int) $request->mnt_rowCount;

                    $roadData = [];
                    $culvertData = [];
                    $bridgeData = [];
                    $wallData = [];
                    $pavementData = [];

                    for ($i = 1; $i <= $rows; $i++) {
                        $roads = array_filter($request->input("roads_mnt$i", []));
                        $culverts = array_filter($request->input("culverts_mnt_$i", []));
                        $bridges = array_filter($request->input("bridges_mnt_$i", []));
                        $walls = array_filter($request->input("walls_mnt_$i", []));
                        $pavements = array_filter($request->input("pavements_mnt_$i", []));

                        if (!empty($roads)) {
                            $roadData = array_merge($roadData, $roads);
                        }

                        if (!empty($culverts)) {
                            $culvertData = array_merge($culvertData, $culverts);
                        }

                        if (!empty($bridges)) {
                            $bridgeData = array_merge($bridgeData, $bridges);
                        }

                        if (!empty($walls)) {
                            $wallData = array_merge($wallData, $walls);
                        }

                        if (!empty($pavements)) {
                            $pavementData = array_merge($pavementData, $pavements);
                        }
                    }

                    // Remove duplicates (optional but recommended)
                    $roadData = array_values(array_unique($roadData));
                    $culvertData = array_values(array_unique($culvertData));
                    $bridgeData = array_values(array_unique($bridgeData));
                    $wallData = array_values(array_unique($wallData));
                    $pavementData = array_values(array_unique($pavementData));

                    $subAssets = [];

                    if (!empty($culvertData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 0,
                            "sub_asset_list" => $culvertData
                        ];
                    }

                    if (!empty($bridgeData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 1,
                            "sub_asset_list" => $bridgeData
                        ];
                    }

                    if (!empty($wallData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 16,
                            "sub_asset_list" => $wallData
                        ];
                    }

                    if (!empty($pavementData)) {
                        $subAssets[] = [
                            "sub_asset_type_cd" => 2,
                            "sub_asset_list" => $pavementData
                        ];
                    }

                    $assetDtls = null;

                    if (!empty($roadData)) {
                        $assetDtls = [
                            "asset_list" => $roadData,
                            "asset_type_cd" => 10
                        ];
                    }


                    $subAssetDtls = !empty($subAssets) ? $subAssets : null;


                    $others['maintenance'] = [
                        "asset_dtls" => $assetDtls,
                        "sub_asset_dtls" => $subAssetDtls
                    ];
                }

                if ($department == 6) {
                    $buildingId = $request->input('maintBuildings');
                    $mtn = [
                        'building_id' => $buildingId
                    ];

                    $others['maintenance'] = $mtn;
                }

                if ($department == 15) {

                    $vehicles = array_filter($request->input('vehicle_type_cd', []));
                    $equipments = array_filter($request->input('equipment_type_cd', []));

                    $mtn = [];

                    if (!empty($vehicles)) {
                        $mtn['vehicles'] = array_values($vehicles);
                    }

                    if (!empty($equipments)) {
                        $mtn['equipments'] = array_values($equipments);
                    }

                    $others['maintenance'] = $mtn;
                }

                $data['others'] = json_encode($others);
            }


            // new project for dept: R&B
            //by dipshikha
            if ($request->projectTypeSelect === 'NEW' && ($request->owner_dept_cd === '14' || $request->owner_dept_cd === '3')) {
                //end
                $newRoadId = 'ROAD_' . time() . '_' . rand(1000, 9999);
                $others = json_decode($data['others'], true);
                $others['new_road_id'] = $newRoadId;
                $others['new_road_name'] = $request->slnewRdNew;
                $others['new_road_length'] = $request->rdLength;
                $others['number_of_culvert'] = $request->new_work_culvert_count;
                $others['number_of_bridge'] = $request->new_work_bridge_count;
                $others['number_of_retain_wall'] = $request->new_work_retain_wall_count;
                $others['number_of_pavement'] = $request->new_work_pavement_count;
                $data['others'] = json_encode($others);
            }

            // new project for dept: Housing
            if ($request->projectTypeSelect === 'NEW' && $request->owner_dept_cd === '6') {
                $buildingClass = AssetMasterBuildingClass::select('building_class_descr')
                    ->where('building_class_cd', $request->building_class_cd)
                    ->first();
                $buildingLocation = AssetMasterBuildingLocation::select('location_name')
                    ->where('location_cd', $request->building_location_cd)
                    ->first();

                $newBuildingId = 'BLD_' . time() . '_' . rand(1000, 9999);
                $others = json_decode($data['others'], true);
                $others['new_building_id'] = $newBuildingId;
                $others['new_building_maintain_by_npwd'] = $request->maintained_by;
                $others['new_building_lat'] = $request->asset_geo_location_lat;
                $others['new_building_lng'] = $request->asset_geo_location_lng;
                $others['new_building_class_cd'] = $request->building_class_cd;
                $others['new_building_class_name'] = $buildingClass->building_class_descr;
                $others['new_building_location_cd'] = $request->building_location_cd;
                $others['new_building_location_name'] = $buildingLocation->location_name;
                $data['others'] = json_encode($others);
            }

            if ($makerCheckerStatus === 'Y') {
                $extraData = [
                    'sent_for_finalize' => 'N',
                ];
                $data = array_merge($data, $extraData);
                $status = PrtProjectDetailsDraft::create($data);
                if ($status) {
                    $this->handleDocument($request, $randomCode, $project_cd);
                    DB::commit();
                    return redirect()->back()->with('success', 'Project details saved successfully!');
                } else {
                    return redirect()->back()->with('error', 'Project details failed to save!!!');
                }
            } else {
                // $extraData = [
                //     'approved_by' => $userid,
                //     'approved_at' => now()
                // ];
                // $status = PrtProjectDetail::create(array_merge($extraData, $data));

                // save in the draft table to avoid conflicts with the main table
                // since, it is important to must go through the maker checker process
                $extraData = [
                    'sent_for_finalize' => 'N',
                ];
                $data = array_merge($data, $extraData);
                $status = PrtProjectDetailsDraft::create($data);

                if ($status) {
                    $this->handleDocument($request, $randomCode, $project_cd);
                    DB::commit();
                    return redirect()->back()->with('success', 'Project details saved successfully!');
                } else {
                    return redirect()->back()->with('error', 'Project details failed to save!!!');
                }
            }
        } catch (QueryException $e) {
            DB::rollBack();
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
            DB::rollBack();
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }
}
