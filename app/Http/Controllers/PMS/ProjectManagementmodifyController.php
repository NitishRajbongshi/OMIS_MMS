<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\DepartmentDetail;
use Exception;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingLocation;
use GuzzleHttp\Client;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Support\Str;
use App\Models\PMS\PrmProjectType;
use App\Models\PMS\PrtContractorDetail;
use App\Models\PMS\PrtProjectDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AssetMasterDocumentCategory;
use App\Models\PMS\PrtProjectDocumentDetail;
use Illuminate\Support\Facades\Storage;
use App\Models\PMS\Master\PrmItemOfWork;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetMasterRdType;
use App\Models\AssetMasterRoadOwner;
use App\Models\Road\AssetRoadDocumentKmlFileDetails;

class ProjectManagementmodifyController extends Controller
{
	private const DB_CONNECTION = 'pgsql_pms';
    // function to show list of verified projects
    public function index()
    {
        $user = Auth::user();

        $types = ['NEW', 'UPG', 'MTN'];
        $projects = DB::table('projects.prt_project_details as project_details')
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
				//by dipshikha
                'project_details.work_order_no',
                'project_details.work_order_issue_date',
                'project_details.scheme_cd',
                'scheme.scheme_name',
                //end
                'project_details.project_status_cd',
                'project_details.project_awarded_to',
                'project_details.site_eng_id',
                'project_details.site_incharge_name',
                'project_details.site_incharge_office_cd',
                'project_details.site_incharge_ph_no',
                'project_details.latitude',
                'project_details.longitude',
                'project_details.others',
                'project_type.proj_type_descr as project_type',
                'owner_dept.department_name as owner_department',
                'division.division_name',
                'sub_division.sub_div_name',
                'site_incharge.office_name as site_incharge_office_name',
                'project_status.project_status_descr as project_status',
                'contractor.contractors_name as contractor_name',
                'latest_mod.status_cd as latest_mod_status',
                'latest_mod.reason as latest_reason',
                'latest_mod.remarks as latest_remarks',
                'latest_mod.reject_reason as latest_reject_reason',
                'latest_mod.created_at as latest_mod_created_at',
            ])
			//by dipshikha
            ->leftJoin(
                'projects.prm_scheme_details as scheme',
                'project_details.scheme_cd',
                '=',
                'scheme.scheme_id'
            )
            //end
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
           ->leftJoinSub(
                DB::table('projects.prt_project_modification_request_details as m1')
                    ->select(
                        'm1.project_cd',
                        'm1.status_cd',
                        'm1.reason',
                        'm1.remarks',
                        'm1.reject_reason',
                        'm1.requested_on',
                        'm1.approved_on',
                        'm1.rejected_on',
                        'm1.created_at'
                    )
                    ->whereRaw('m1.request_id = (
                        SELECT m2.request_id
                        FROM projects.prt_project_modification_request_details m2
                        WHERE m2.project_cd = m1.project_cd
                        ORDER BY m2.created_at DESC, m2.request_id DESC
                        LIMIT 1
                    )'),
                'latest_mod',
                'project_details.project_cd',
                '=',
                'latest_mod.project_cd'
            )
            ->orderBy('project_details.project_cd', 'desc')
            ->whereIn('project_details.project_type_cd', $types)
            ->where('project_details.owner_dept_cd', $user->department)
            ->where('project_details.project_status_cd', '!=', 2)
			->whereRaw("
                CURRENT_DATE >= (
                    project_details.project_start_date
                    + (project_details.project_end_date - project_details.project_start_date) / 2
                )
            ")			
            ->get();
			//by dipshikha
        foreach ($projects as $project) {

            $workOrderAmount = (float) ($project->work_order_amount ?? 0);

            $fundingAgencies = DB::table('projects.prm_scheme_funding_mapping as sfm')
                ->select(
                    'fad.agency_name',
                    'sfm.funding_percentage',
                    DB::raw("
                        ROUND(
                            ({$workOrderAmount} * sfm.funding_percentage) / 100,
                            2
                        ) as funding_amount
                    ")
                )
                ->join(
                    'projects.prm_funding_agency_details as fad',
                    'sfm.funding_agency_id',
                    '=',
                    'fad.funding_agency_id'
                )
                ->where('sfm.scheme_id', $project->scheme_cd)
                ->orderBy('sfm.funding_agency_id', 'asc')
                ->get();

            $project->funding_agencies = $fundingAgencies;
        }
        //end

        $project_code_culvert = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '0')
            ->pluck('project_cd')
            ->toArray();
        $project_code_bridge = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '1')
            ->pluck('project_cd')
            ->toArray();
        $project_code_rtw = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '16')
            ->pluck('project_cd')
            ->toArray();
        $project_code_pvm = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '2')
            ->pluck('project_cd')
            ->toArray();
        $workItems_exist = DB::table('projects.prt_project_work_items_details')
            ->distinct('project_cd')
            ->pluck('project_cd')
            ->toArray();

        $hasMaintenanceAssets = false;
        $hasUpgradationAssets = false;


        foreach ($projects as $draft) {
            if (!$draft->others) continue;

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


        foreach ($projects as $draft) {
            if (!$draft->others) continue;

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
                        $pavements = array_filter($row['pavements'] ?? []);

                        $newCulverts = (int) ($row['new_culverts'] ?? 0);
                        $newBridges = (int) ($row['new_bridges'] ?? 0);
                        $newWalls = (int) ($row['new_retaining_walls'] ?? 0);
                        $newPavements = (int) ($row['new_pavements'] ?? 0);

                        if (!empty($row['parent_asset_id']) || $culverts || $bridges || $walls || $pavements || $newCulverts > 0 || $newBridges > 0 || $newWalls > 0 || $newPavements > 0) {
                            $hasExistingAssets = true;
                            break;
                        }
                    }

                    $newAsset = $upg['new_asset'] ?? null;

                    $hasNewAssets = false;

                    if (!empty($newAsset) && is_array($newAsset)) {
                        $hasNewAssets = (
                            !empty($newAsset['road_name']) ||
                            (float) ($newAsset['road_length'] ?? 0) > 0 ||
                            (int) ($newAsset['culverts'] ?? 0) > 0 ||
                            (int) ($newAsset['bridges'] ?? 0) > 0 ||
                            (int) ($newAsset['pavements'] ?? 0) > 0 ||
                            (int) ($newAsset['retaining_walls'] ?? 0) > 0
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

        return view(
            "pms.modify.requestForModificationProject",
            compact(
                'projects',
                'project_code_culvert',
                'project_code_rtw',
                'project_code_pvm',
                'project_code_bridge',
                'workItems_exist',
                'hasMaintenanceAssets',
                'hasUpgradationAssets',
            )
        );
    }

    public function history($project_cd)
    {
        $project = DB::table('projects.prt_project_details')
            ->where('project_cd', $project_cd)
            ->first();

        $history = DB::table('projects.prt_project_modification_request_details as mod')
            ->select(
                'mod.*',
                'approved_user.name as approved_by_name',
                'rejected_user.name as rejected_by_name'
            )
            ->leftJoin('users as approved_user', 'mod.approved_by', '=', 'approved_user.id')
            ->leftJoin('users as rejected_user', 'mod.rejected_by', '=', 'rejected_user.id')
            ->where('mod.project_cd', $project_cd)
            ->orderBy('mod.created_at', 'desc')
            ->get();

        return view(
            'pms.modify.projectModificationHistory',
            compact('project', 'history')
        );
    }

    public function requestModification(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'project_cd' => 'required',
                'reason' => 'required|string',
                'supporting_document' => 'required|file|mimes:pdf|max:2048',
            ]);


            $changesApplied = [];

            $changesApplied['project_info'] = array_filter([
                'project_name'            => $request->project_name_nv,
                'project_end_date'        => $request->project_end_date_nv,
                'project_awarded_to'      => $request->project_awarded_to,
                'est_proj_cost'           => $request->est_proj_cost_nv,
                'defect_liability_period' => $request->defect_liability_period_nv,
                'work_order_amount'       => $request->work_order_amount_nv,
                'work_order_no'           => $request->work_order_no_nv,
                'work_order_issue_date'   => $request->work_order_issue_date_nv,
                'scheme_cd'               => $request->scheme_cd,
            ], fn($value) => !is_null($value));

            if ($request->projectTypeSelectHidden === 'NEW' && ($request->owner_dept_cd_hidden === '14' || $request->owner_dept_cd_hidden === '3')) {
                $others = array_filter([
                    'new_road_name'   => $request->slnewRdNew_nv,
                    'new_road_length' => $request->rdLength_nv,
                    'road_category'   => $request->road_category_new,
                    'road_type'       => $request->road_type_new,
                    'road_owner'      => $request->road_owner_new,
                ], function ($value) {
                    return $value !== null && $value !== '';
                });

                $newRoadName = $request->slnewRdNew_nv;
                $total_road_length = $request->rdLength_nv;
                $new_rd_catg_cd = $request->road_category_new;

                if (!empty($others)) {
                    $changesApplied['others'] = $others;
                }
            }

            if ($request->projectTypeSelectHidden === 'MTN' && ($request->owner_dept_cd_hidden == '14' || $request->owner_dept_cd_hidden == '3')) {

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

                $assetDtls = null;

                if (!empty($roadData)) {
                    $assetDtls = [
                        "asset_type_cd" => 10,
                        "asset_list" => $roadData
                    ];
                }

                $maintenance = array_filter([
                    "asset_dtls" => $assetDtls,
                    "sub_asset_dtls" => !empty($subAssets) ? $subAssets : null
                ], fn($value) => !is_null($value));

                if (!empty($maintenance)) {
                    $changesApplied['others']['maintenance'] = $maintenance;
                }
            }

            if ($request->projectTypeSelectHidden === 'UPG' && ($request->owner_dept_cd_hidden == '14' || $request->owner_dept_cd_hidden == '3')) {
                $roads = $request->input('roads', []);
                $startChainages = $request->input('start_chainage', []);
                $endChainages = $request->input('end_chainage', []);
                $groupedRows = [];

                for ($i = 0; $i < count($roads); $i++) {

                    $rowIndex = $i + 1;
                    $roadId = $roads[$i];

                    if (!isset($groupedRows[$roadId])) {
                        $groupedRows[$roadId] = [
                            'parent_asset_id' => $roadId,
                            'start_chainage' => $startChainages[$i] ?? null,
                            'end_chainage' => $endChainages[$i] ?? null,
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

                $roadName = trim($request->new_road_name_nv ?? '');
                $roadLength = (float) ($request->road_length_nv ?? 0);

                $isNewAssetEmpty =
                    $roadName === '' &&
                    $roadLength == 0 &&
                    empty($request->road_category) &&
                    empty($request->road_type) &&
                    empty($request->road_owner);

                $newRoadId = $roadName !== '' ? (string) Str::uuid() : null;
                $newRoadName = $roadName;
                $total_road_length = $request->total_road_length;
                $new_rd_catg_cd = $request->road_category;

                $upg = [
                    'upgraded_asset_dtls' => $rowsData,
                    'new_asset' => $isNewAssetEmpty ? null : (
                        $request->input('ref_asset') === 'exist'
                        ? [
                            'road_id' => $newRoadId,
                            'road_name' => $roadName ?: null,
                            'road_length' => $roadLength,
                            'road_category' => $request->road_category,
                            'road_type' => $request->road_type,
                            'road_owner' => $request->road_owner,
                        ]
                        : [
                            'road_id' => null,
                            'road_name' => null,
                            'road_length' => 0,
                            'road_category' => '',
                            'road_type' => '',
                            'road_owner' => '',
                        ]
                    ),
                ];



                $priorityRoads = $request->input('priority_roads', []);

                $existingRoadIds = DB::table('asset_road_details')
                    ->pluck('rd_system_id')
                    ->toArray();

                $priorityRoads = array_map(function ($item) use ($existingRoadIds, $newRoadId) {

                    if (in_array($item, $existingRoadIds)) {
                        return $item;
                    }

                    return $newRoadId;

                }, $priorityRoads);

                $priorityRoads = array_values(array_filter($priorityRoads));

                $firstUpgradedRoadId = null;

                if (!empty($priorityRoads)) {
                    foreach (array_values($priorityRoads) as $index => $roadId) {

                        if ($index === 0) {
                            $firstUpgradedRoadId = $roadId;
                        }

                        $upg['road_sequence'][] = [
                            'sequence' => $index + 1,
                            'road_id' => $roadId,
                        ];
                    }
                }

                if ($request->input('ref_asset') === 'exist' && !empty($roads)) {
                    $upg['upgraded_roads'] = array_values(array_unique($roads));
                    $upg['merged_road_id'] = $newRoadId;
                    $upg['merged_road_name'] = $request->new_road_name ?: AssetRoadDetail::where('rd_system_id', $firstUpgradedRoadId)->value('rd_name');
                    $upg['merged_road_type'] = $request->road_type;
                    $upg['merged_road_owner'] = $request->road_owner;
                    $upg['merged_road_length'] = (float) ($request->total_road_length ?? 0);
                    $upg['merged_road_category'] = $request->road_category;
                } else {
                    $upg['merged_road_id'] = null;
                    $upg['merged_road_name'] = null;
                    $upg['merged_road_type'] = '';
                    $upg['merged_road_owner'] = '';
                    $upg['merged_road_length'] = 0;
                    $upg['merged_road_category'] = '';
                }
                $changesApplied['others']['upgradation'] = $upg;
            }

            if ($request->hasFile('road_kml_file')) {

                $file = $request->file('road_kml_file');
                $uniqueFileName = $request->project_cd.'_'.time() . '.kml';
                $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/KML_FILES';
                $rootPath = config('filesystems.disks.external.root');

                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                }


                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                $completeFilePath = $rootPath . '/' . $filePath;

                $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $request->project_cd.'_'.time() . '.geojson';
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
                        'road_id' => $uniqueFileName,
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
                        ]);
                }


                $changesApplied['others']['road_kml_file'] = $completeFilePath;
                $changesApplied['others']['road_geojson_file'] = $geojson_file_path;

            }

			if ($request->projectTypeSelectHidden === 'NEW' && ($request->owner_dept_cd_hidden === '6')) {
                $buildingClass = AssetMasterBuildingClass::select('building_class_descr')
                    ->where('building_class_cd', $request->building_class_cd)
                    ->first();
                $buildingLocation = AssetMasterBuildingLocation::select('location_name')
                    ->where('location_cd', $request->building_location_cd)
                    ->first();

                $project = DB::table('projects.prt_project_details')
                    ->where('project_cd', $request->project_cd)
                    ->first();

                $existingOthers = [];

                if ($project && !empty($project->others)) {
                    $existingOthers = json_decode($project->others, true) ?? [];
                }
                $others = [
                    'new_building_maintain_by_npwd' => $request->filled('rdo_maintained_by')
                        ? $request->rdo_maintained_by
                        : ($existingOthers['new_building_maintain_by_npwd'] ?? null),

                    'new_building_lat' => $request->filled('asset_geo_location_lat')
                        ? $request->asset_geo_location_lat
                        : ($existingOthers['new_building_lat'] ?? null),

                    'new_building_lng' => $request->filled('asset_geo_location_lng')
                        ? $request->asset_geo_location_lng
                        : ($existingOthers['new_building_lng'] ?? null),

                    'new_building_class_cd' => $request->filled('building_class_cd')
                        ? $request->building_class_cd
                        : ($existingOthers['new_building_class_cd'] ?? null),

                    'new_building_class_name' => $request->filled('building_class_cd')
                        ? ($buildingClass->building_class_descr ?? null)
                        : ($existingOthers['new_building_class_name'] ?? null),

                    'new_building_location_cd' => $request->filled('building_location_cd')
                        ? $request->building_location_cd
                        : ($existingOthers['new_building_location_cd'] ?? null),

                    'new_building_location_name' => $request->filled('building_location_cd')
                        ? ($buildingLocation->location_name ?? null)
                        : ($existingOthers['new_building_location_name'] ?? null),
                ];

                $others = array_filter($others, function ($value) {
                    return $value !== null && $value !== '';
                });

                if (!empty($others)) {
                    $changesApplied['others'] = $others;
                }
            }
            if ($request->projectTypeSelectHidden === 'UPG' && $request->owner_dept_cd_hidden == '6'){

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
                $changesApplied['others']['upgradation'] = $upg;
            }
            if ($request->projectTypeSelectHidden === 'MTN' && $request->owner_dept_cd_hidden == '6'){
                $buildingId = $request->input('maintBuildings');
                $mtn = [
                    'building_id' => $buildingId
                ];
                $maintenance = $mtn;

                if (!empty($maintenance)) {
                    $changesApplied['others']['maintenance'] = $maintenance;
                }
            }

            if ($request->projectTypeSelectHidden === 'UPG' && $request->owner_dept_cd_hidden == '15'){
                $vehicles = array_filter($request->input('vehicle_type_cd', []));
                $equipments = array_filter($request->input('equipment_type_cd', []));

                $upg = [];

                if (!empty($vehicles)) {
                    $upg['vehicles'] = array_values($vehicles);
                }

                if (!empty($equipments)) {
                    $upg['equipments'] = array_values($equipments);
                }

                if (!empty($upg)) {
                    $changesApplied['others']['upgradation'] = $upg;
                }
            }

            if ($request->projectTypeSelectHidden === 'MTN' && $request->owner_dept_cd_hidden == '15'){
                $vehicles = array_filter($request->input('vehicle_type_cd', []));
                $equipments = array_filter($request->input('equipment_type_cd', []));

                $mtn = [];

                if (!empty($vehicles)) {
                    $mtn['vehicles'] = array_values($vehicles);
                }

                if (!empty($equipments)) {
                    $mtn['equipments'] = array_values($equipments);
                }

                if (!empty($mtn)) {
                    $changesApplied['others']['maintenance'] = $mtn;
                }
            }

			$technologyType = DB::connection(self::DB_CONNECTION)
                ->table('prm_project_technology_types')
                ->where('tech_type_cd', $request->tech_type_cd)
                ->value('tech_type_descr');

            $changesApplied['others']['tech_type_cd'] = $request->tech_type_cd;
            $changesApplied['others']['tech_type_descr'] = $technologyType;
            $changesApplied['work_items_json']= $request->work_items_json;

            $requestId = time() . rand(1000, 9999);

            DB::table('projects.prt_project_modification_request_details')->insert([
                'request_id'      => $requestId,
                'project_cd'      => $request->project_cd,
                'requested_by'    => Auth::id(),
                'reason'          => $request->reason,
                'status_cd'       => 0,
                'changes_applied' => json_encode($changesApplied),
                'requested_on'    => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();

            $this->handleDocument($request,$requestId,$request->project_cd);

            return redirect()->route('project.request.list')->with('success', 'Modification request submitted successfully!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Modification Request failed!');
        }
    }

    public function pendingProjects()
    {
        $user = Auth::user();

        $pendingProjects = DB::table('projects.prt_project_modification_request_details as mr')
            ->join('projects.prt_project_details as pd', 'mr.project_cd', '=', 'pd.project_cd')

            ->leftJoin(
                'projects.prm_project_types as pt',
                'pd.project_type_cd',
                '=',
                'pt.proj_type_cd'
            )
            ->leftJoin(
                'public.department_details as dept',
                'pd.owner_dept_cd',
                '=',
                'dept.id'
            )
            ->leftJoin(
                'public.asset_master_divisions as division',
                'pd.division_cd',
                '=',
                'division.division_cd'
            )
            ->leftJoin(
                'public.asset_master_sub_divisions as sub_division',
                'pd.sub_division_cd',
                '=',
                'sub_division.sub_div_cd'
            )
            ->leftJoin(
                'projects.prt_contractor_details as contractor',
                'pd.project_awarded_to',
                '=',
                'contractor.regn_no'
            )
           ->leftJoin('projects.prt_project_document_details as doc', function ($join) {
                $join->on('mr.request_id', '=', 'doc.request_id')
                    ->where('doc.doc_catg', 'MO');
            })
            ->select([
                'mr.request_id',
                'mr.reason',
                'mr.requested_on',
                'mr.requested_by',
                'mr.changes_applied',
                'contractor.contractors_name as contractor_name',
                'doc.file_path as supporting_document',

                'pd.*',

                'pt.proj_type_descr as project_type',
                'dept.department_name as owner_department',
                'division.division_name',
                'sub_division.sub_div_name',
            ])
            ->where('mr.status_cd', 0)
            ->where('pd.owner_dept_cd', $user->department)
            ->orderBy('mr.requested_on', 'desc')
            ->get();

        return view(
            'pms.modify.pendingApprovalProject',
            compact(
                'pendingProjects'
            )
        );
    }



    public function rejectModification(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'request_id' => 'required',
                'reject_reason' => 'required|string|max:255',
            ]);

            $updated = DB::table('projects.prt_project_modification_request_details')
                ->where('request_id', $request->request_id)
                ->update([
                    'status_cd'        => 4,
                    'rejected_by'   => Auth::id(),
                    'reject_reason' => $request->reject_reason,
                    'rejected_on'   => now(),
                    'updated_at'    => now(),
                ]);

            if (!$updated) {
                DB::rollBack();

                return response()->json([
                    'status' => 404,
                    'message' => 'Request not found.'
                ]);
            }

            DB::table('projects.prt_project_document_details')
            ->where('request_id', $request->request_id)
            ->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Modification request rejected successfully.'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function approveModification(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'request_id' => 'required',
                'approve_remark' => 'required|string|max:255',
            ]);

            $updated = DB::table('projects.prt_project_modification_request_details')
                ->where('request_id', $request->request_id)
                ->update([
                    'status_cd'        => 1,
                    'approved_by'   => Auth::id(),
                    'remarks' => $request->approve_remark,
                    'approved_on'   => now(),
                    'updated_at'    => now(),
                ]);

            if (!$updated) {
                DB::rollBack();

                return response()->json([
                    'status' => 404,
                    'message' => 'Request not found.'
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Modification request approved successfully.'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function modificationDetails($request_cd)
    {
        $assetLists = AssetMasterRoadSubAsset::select('sub_asset_cd', 'sub_assets_descr')->get();
        if ($request_cd) {
            $project_cd = DB::table('projects.prt_project_modification_request_details')
                ->where('request_id', $request_cd)
                ->value('project_cd');
            $project = DB::table('projects.prt_project_details as project_details')
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


            $schemeCd = $project->scheme_cd ?? null;
            $fundingAgencies = [];
            $work_order_amount = $project->work_order_amount ?? 0;
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

            $others = json_decode($project->others, true);

            $roadCategoryName = null;
            $roadTypeName = null;
            $roadOwnerName = null;
			$building = null;
            $vehicleDetails = [];
            $equipmentDetails = [];

            if($project->owner_dept_cd === 14 || $project->owner_dept_cd === 3){
                if (($others['project_type'] ?? null) === 'NEW' || ($others['project_type'] ?? null) === 'UPG') {
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
                }
			}

            if($project->owner_dept_cd === 6){
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
                }
                if (($others['project_type'] ?? null) === 'MTN') {
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
            }

            if($project->owner_dept_cd === 15){
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

            $modificationRequest = DB::table('projects.prt_project_modification_request_details')
                ->where('request_id', $request_cd)
                ->first();

            if (!$modificationRequest) {
                abort(404);
            }

            $changesApplied = json_decode($modificationRequest->changes_applied, true);
            $projectInfo = $changesApplied['project_info'] ?? [];
            $othersInfo = $changesApplied['others'] ?? [];

            $requestedContractorName = null;

            if (!empty($projectInfo['project_awarded_to'])) {
                $requestedContractorName = DB::table('projects.prt_contractor_details')
                    ->where('regn_no', $projectInfo['project_awarded_to'])
                    ->value('contractors_name');
            }


            $requestedSchemeName = null;

            if (!empty($projectInfo['scheme_cd'])) {
                $requestedSchemeName = DB::table('projects.prm_scheme_details')
                    ->where('scheme_id', $projectInfo['scheme_cd'])
                    ->value('scheme_name');
            }

			$requestedTechTypeName = null;

            if (!empty($othersInfo['tech_type_cd'])) {
                $requestedTechTypeName = $othersInfo['tech_type_descr'];
            }

            $requestedRoadCategoryName = null;
            $requestedRoadTypeName = null;
            $requestedRoadOwnerName = null;
			$requestedBuilding = null;
            $requestedVehicleDetails = [];
            $requestedEquipmentDetails = [];


            if($project->owner_dept_cd === 14 || $project->owner_dept_cd === 3){
            if (($others['project_type'] ?? null) === 'NEW' || ($others['project_type'] ?? null) === 'UPG') {
                if (($others['project_type'] ?? null) === 'NEW') {

                    $roadCategoryCd = $othersInfo['road_category'] ?? null;
                    $roadTypeCd = $othersInfo['road_type'] ?? null;
                    $roadOwnerCd = $othersInfo['road_owner'] ?? null;

                } elseif (($others['project_type'] ?? null) === 'UPG') {
                    $newAsset = $othersInfo['upgradation']['new_asset'] ?? [];
                    $roadCategoryCd = $newAsset['road_category'] ?? null;
                    $roadTypeCd = $newAsset['road_type'] ?? null;
                    $roadOwnerCd = $newAsset['road_owner'] ?? null;
                }

                // Category
                if (!empty($roadCategoryCd)) {
                    $requestedRoadCategoryName = DB::table('asset_master_road_category')
                        ->where('rd_catg_cd', $roadCategoryCd)
                        ->value('rd_catg_descr');
                }

                // Type
                if ($roadTypeCd !== null && $roadTypeCd !== '') {
                    $requestedRoadTypeName = DB::table('asset_master_rd_type')
                        ->where('rd_type_cd', $roadTypeCd)
                        ->value('rd_type_descr');
                }

                // Owner
                if ($roadOwnerCd !== null && $roadOwnerCd !== '') {
                    $requestedRoadOwnerName = DB::table('asset_master_road_owner')
                        ->where('owner_cd', $roadOwnerCd)
                        ->value('owner_name');
                }
            }
			}

            if($project->owner_dept_cd === 6){
                if (($others['project_type'] ?? null) === 'UPG') {
                    $upg = $othersInfo['upgradation'] ?? [];
                    if (!empty($upg['building_id'])) {
                        $requestedBuilding = DB::table('buildings.asset_building_details')
                            ->where('building_system_cd', $upg['building_id'])
                            ->first();

                        $requestedBuilding->building_type_cd = $upg['building_type_cd'] ?? $requestedBuilding->building_type_cd;
                        $requestedBuilding->building_type_name = DB::table('buildings.asset_master_building_types')
                            ->where('building_type_cd', $requestedBuilding->building_type_cd)
                            ->value('building_type_descr');

                        // Owning Department
                        $requestedBuilding->asset_owning_dept_cd = $upg['owning_dept_cd'] ?? $requestedBuilding->asset_owning_dept_cd;
                        $requestedBuilding->asset_owning_dept_name = DB::table('asset_master_dept_of_state')
                            ->where('id', $requestedBuilding->asset_owning_dept_cd)
                            ->value('dept_name');

                        // Building Category
                        $requestedBuilding->building_class_cd = $upg['building_category_cd'] ?? $requestedBuilding->building_class_cd;
                        $requestedBuilding->building_class_name = DB::table('buildings.asset_master_building_class')
                            ->where('building_class_cd', $requestedBuilding->building_class_cd)
                            ->value('building_class_descr');

                        // Building Location
                        $requestedBuilding->building_location_name = DB::table('buildings.asset_master_building_locations')
                            ->where('location_cd', $requestedBuilding->building_location_cd)
                            ->value('location_name');

                        // Maintained By NPWD
                        $requestedBuilding->is_maintained_by_npwd = $upg['is_maintained_by_npwd'] ?? $requestedBuilding->is_maintained_by_npwd;

                        // Quarter No / Building Name
                        if (isset($upg['quarter_no'])) {
                            $requestedBuilding->qtr_no = $upg['quarter_no'];
                        }

                        if (isset($upg['building_name'])) {
                            $requestedBuilding->bld_qtr_name = $upg['building_name'];
                        }

                    }
                }
                if (($others['project_type'] ?? null) === 'MTN') {
                    $mtn = $othersInfo['maintenance'] ?? [];
                    if (!empty($mtn['building_id'])) {
                        $requestedBuilding = DB::table('buildings.asset_building_details')
                            ->where('building_system_cd', $mtn['building_id'])
                            ->first();

                        $requestedBuilding->building_type_name = DB::table('buildings.asset_master_building_types')
                            ->where('building_type_cd', $requestedBuilding->building_type_cd)
                            ->value('building_type_descr');

                        $requestedBuilding->asset_owning_dept_name = DB::table('asset_master_dept_of_state')
                            ->where('id', $requestedBuilding->asset_owning_dept_cd)
                            ->value('dept_name');

                        $requestedBuilding->building_class_name = DB::table('buildings.asset_master_building_class')
                            ->where('building_class_cd', $requestedBuilding->building_class_cd)
                            ->value('building_class_descr');

                        // Building Location
                        $requestedBuilding->building_location_name = DB::table('buildings.asset_master_building_locations')
                            ->where('location_cd', $requestedBuilding->building_location_cd)
                            ->value('location_name');
                    }
                }
            }

            if($project->owner_dept_cd === 15){
                if (($others['project_type'] ?? null) === 'UPG' || ($others['project_type'] ?? null) === 'MTN') {

                    $assetData = [];

                    if (($others['project_type'] ?? null) === 'UPG') {
                        $assetData = $othersInfo['upgradation'] ?? [];
                    } elseif (($others['project_type'] ?? null) === 'MTN') {
                        $assetData = $othersInfo['maintenance'] ?? [];
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
                                $requestedVehicleDetails[] = $vehicle;
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
                                $requestedEquipmentDetails[] = $equipment;
                            }
                        }
                    }
                }
            }



            $requestedFundingAgencies = collect();

            $requestedSchemeId = $projectInfo['scheme_cd'] ?? $project->scheme_cd;
            $requestedWorkOrderAmount = $projectInfo['work_order_amount'] ?? $project->work_order_amount;

            if ($requestedSchemeId) {

                $requestedFundingAgencies = DB::table('projects.prm_scheme_funding_mapping as sfm')
                    ->select(
                        'sfm.funding_agency_id',
                        'fad.agency_name',
                        'sfm.funding_percentage'
                    )
                    ->join(
                        'projects.prm_funding_agency_details as fad',
                        'sfm.funding_agency_id',
                        '=',
                        'fad.funding_agency_id'
                    )
                    ->where('sfm.scheme_id', $requestedSchemeId)
                    ->orderBy('sfm.funding_agency_id')
                    ->get()
                    ->map(function ($agency) use ($requestedWorkOrderAmount) {

                        $percentage = (float) $agency->funding_percentage;

                        $agency->funding_amount =
                            ($requestedWorkOrderAmount * $percentage) / 100;

                        return $agency;
                    });
            }

            $modificationDocument = DB::table('projects.prt_project_document_details as d')
                ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
                ->where('d.request_id', $request_cd)
                ->where('d.doc_catg', 'MO')
                ->select(
                    'd.id',
                    'd.doc_catg',
                    'd.file_path',
                    'd.created_at',
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


			// Requested documents for this request
            $documentsRequested = DB::table('projects.prt_project_document_details as d')
                ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
                ->where('d.request_id', $request_cd)
                ->where('d.doc_catg', '!=', 'MO')
                ->selectRaw('DISTINCT ON (d.doc_catg)
                    d.id,
					d.project_cd,
                    d.doc_catg,
                    d.file_path,
                    d.created_at,
                    c.doc_catg_descr
                ')
                ->orderBy('d.doc_catg')
                ->orderByDesc('d.created_at')
                ->get()
                ->map(function ($doc) use ($basePath) {

                    $fullPath = str_replace('\\', '/', $doc->file_path);
                    $basePathClean = str_replace('\\', '/', $basePath);
                    $relativePath = ltrim(str_replace($basePathClean, '', $fullPath), '/');

                    return [
                        'id' => $doc->id,
						'project_cd' => $doc->project_cd,
                        'doc_catg' => $doc->doc_catg,
                        'label' => $doc->doc_catg_descr ?? $doc->doc_catg,
                        'file_url' => url('asset-management/uploaded_docs/' . $relativePath),
                        'created_at' => $doc->created_at,
                    ];
                });

            $documents = collect();

            foreach ($documentsRequested as $requestedDoc) {

				// Find the immediate previous document for this category
                $previousDoc = DB::table('projects.prt_project_document_details as d')
                    ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
                    ->where('d.project_cd', $project_cd)
                    ->where('d.doc_catg', $requestedDoc['doc_catg'])
                    ->where('d.created_at', '<', $requestedDoc['created_at'])
                    ->orderByDesc('d.created_at')
                    ->select(
                        'd.id',
                        'd.doc_catg',
                        'd.file_path',
                        'd.created_at',
                        'c.doc_catg_descr'
                    )
                    ->first();

				// If previous exists, show it
                if ($previousDoc) {

                    $fullPath = str_replace('\\', '/', $previousDoc->file_path);
                    $basePathClean = str_replace('\\', '/', $basePath);
                    $relativePath = ltrim(str_replace($basePathClean, '', $fullPath), '/');

                    $documents->push([
                        'id' => $previousDoc->id,
                        'doc_catg' => $previousDoc->doc_catg,
                        'label' => $previousDoc->doc_catg_descr ?? $previousDoc->doc_catg,
                        'file_url' => url('asset-management/uploaded_docs/' . $relativePath),
                        'created_at' => $previousDoc->created_at,
                    ]);
                }
            }


            // Now add categories which DO NOT have a document for this request
            $latestDocuments = DB::table('projects.prt_project_document_details as d')
                ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
                ->where('d.project_cd', $project_cd)
                ->where('d.doc_catg', '!=', 'MO')
                ->selectRaw('DISTINCT ON (d.doc_catg)
                    d.id,
                    d.doc_catg,
                    d.file_path,
                    d.created_at,
                    c.doc_catg_descr
                ')
                ->orderBy('d.doc_catg')
                ->orderByDesc('d.created_at')
                ->get();

            foreach ($latestDocuments as $latestDoc) {

                // Skip if this category already has a request document
                $exists = $documentsRequested->firstWhere('doc_catg', $latestDoc->doc_catg);

                if ($exists) {
                    continue;
                }

                $fullPath = str_replace('\\', '/', $latestDoc->file_path);
                $basePathClean = str_replace('\\', '/', $basePath);
                $relativePath = ltrim(str_replace($basePathClean, '', $fullPath), '/');

                $documents->push([
                    'id' => $latestDoc->id,
                    'doc_catg' => $latestDoc->doc_catg,
                    'label' => $latestDoc->doc_catg_descr ?? $latestDoc->doc_catg,
                    'file_url' => url('asset-management/uploaded_docs/' . $relativePath),
                    'created_at' => $latestDoc->created_at,
                ]);
            }

            //end by dipshikha

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

            if ($project)
                return view('pms.modify.modificationDetails', compact(
                'project',
                'fundingAgencies',
                'requestedFundingAgencies',
                'requestedContractorName',
                'requestedSchemeName',
                'requestedVehicleDetails',
                'requestedEquipmentDetails',
                'vehicleDetails',
                'equipmentDetails',
                'documents',
				'items',
				'building',
                'requestedBuilding',
                'assetLists',
                'requestedTechTypeName',
                'documentsRequested',
                'modificationRequest',
                'modificationDocument',
                'roadCategoryName',
                'requestedRoadCategoryName',
                'requestedRoadTypeName',
                'requestedRoadOwnerName',
                'roadTypeName',
                'roadOwnerName'
            ));
        }
    }

    public function approvedEdit($project_cd)
    {
        $user = Auth::user();
        $projectTypes = PrmProjectType::all();
        $department = DepartmentDetail::where('id', '=', $user->department)->get()->first();
        $constructors = PrtContractorDetail::all();
        $contractorCategories = DB::table('projects.prm_contractor_categories')->get();
        $districts = DB::table('public.asset_master_lgd_district')->select('dist_code', 'dist_name')->get();
        $states = DB::table('public.asset_master_lgd_state')->select('state_code', 'state_name')->get();
		//by dipshikha
        $schemes = DB::table('projects.prm_scheme_details')->select(['scheme_id', 'scheme_name'])->get();
        //end
        $officeDetails = session('userMapping');
        $circle_cd = $officeDetails->circle_cd;
        $division_cd = $officeDetails->division_cd;
        $officeType = $officeDetails->office_type_cd;
		$technologies = DB::table('projects.prm_project_technology_types')->get(); // Nitish

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
        $officeType = $officeDetails->office_type_cd;
        $circle_cd = $officeDetails->circle_cd;
        $userDept = $user->department;
        $itemOfWorks = PrmItemOfWork::where('dept_cd', $userDept)->get();
        $roadCategories = AssetMasterRoadCategory::all();
        $roadTypes = AssetMasterRdType::all();
        $roadOwners = AssetMasterRoadOwner::all();


        $project = DB::table('projects.prt_project_details as p')
        ->leftJoin('public.asset_master_divisions as d', 'd.division_cd', '=', 'p.division_cd')
        ->leftJoin('public.asset_master_sub_divisions as s', 's.sub_div_cd', '=', 'p.sub_division_cd')
        ->leftJoin('projects.prm_project_types as t', 't.proj_type_cd', '=', 'p.project_type_cd')
        ->select(
            'p.*',
            'd.division_name',
            's.sub_div_name',
            't.proj_type_descr'
        )
        ->where('p.project_cd', $project_cd)
        ->first();


        $project->others = json_decode($project->others, true) ?? [];

        if ($project->owner_dept_cd == '6') {

            if (($project->project_type_cd ?? null) === 'MTN') {

                $maintenance = $project->others['maintenance'] ?? [];
                $buildingCd = $maintenance['building_id'] ?? null;

                $buildingDetails = null;

                if ($buildingCd) {

                    $buildingDetails = DB::table('buildings.asset_building_details as b')
                        ->leftJoin('buildings.asset_master_building_locations as l', 'b.building_location_cd', '=', 'l.location_cd')
                        ->leftJoin('buildings.asset_master_building_types as bt', 'b.building_type_cd', '=', 'bt.building_type_cd')
                        ->leftJoin('asset_master_dept_of_state as dept', 'b.asset_owning_dept_cd', '=', 'dept.id')
                        ->leftJoin('buildings.asset_master_building_class as bc', 'b.building_class_cd', '=', 'bc.building_class_cd')
                        ->select(
                            'b.building_system_cd',
                            'b.is_maintained_by_npwd',
                            'b.lat',
                            'b.lon',
                            'l.location_name',
                            'bt.building_type_descr',
                            'bc.building_class_descr',
                            'b.building_class_cd',
                            'dept.dept_name',
                            DB::raw("
                                CASE
                                    WHEN b.building_class_cd = '0' THEN b.qtr_no
                                    WHEN b.building_class_cd IN ('1','2') THEN b.bld_qtr_name
                                END as building_name
                            ")
                        )
                        ->where('b.building_system_cd', $buildingCd)
                        ->first();
                }

                $project->others['maintenance_data'] = [
                    'building_id' => $buildingCd,
                    'building_details' => $buildingDetails,
                ];
            }

            if (($project->project_type_cd ?? null)  === 'UPG') {
                $upg = $project->others['upgradation'] ?? [];
                $buildingId = $upg['building_id'] ?? null;

                $buildingDetails = null;

                if ($buildingId) {

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

                    if ($building) {

                        // Override values if present in draft
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

                        if (isset($upg['building_category_cd'])) {
                            $buildingClassName = DB::table('buildings.asset_master_building_class')
                                ->where('building_class_cd', $upg['building_category_cd'])
                                ->value('building_class_descr');
                        }

                        $buildingDetails = [
                            'building_id' => $buildingId,

                            'building_type_cd' => $upg['building_type_cd'] ?? $building->building_type_cd,
                            'building_type_descr' => $buildingTypeName,

                            'owning_dept_cd' => $upg['owning_dept_cd'] ?? $building->asset_owning_dept_cd,
                            'dept_name' => $deptName,

                            'is_maintained_by_npwd' => $upg['is_maintained_by_npwd'] ?? $building->is_maintained_by_npwd,

                            'building_class_cd' => $upg['building_category_cd'] ?? $building->building_class_cd,
                            'actual_building_class_cd' => $building->building_class_cd,
                            'building_class_descr' => $buildingClassName,

                            'lat' => $building->lat,
                            'lon' => $building->lon,
                            'location_name' => $building->location_name,
                        ];

                        if ($buildingDetails['building_class_cd'] == 0) {
                            $buildingDetails['quarter_no'] = $upg['quarter_no'] ?? $building->qtr_no;
                        } else {
                            $buildingDetails['building_name'] = $upg['building_name'] ?? $building->bld_qtr_name;
                        }
                    }
                }

                $project->others['upgradation_data'] = [
                    'building_details' => $buildingDetails,
                ];
            }
        }

        $basePath = config('filesystems.disks.external.root');

        $documents = DB::table('projects.prt_project_document_details as d')
            ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
            ->where('d.project_cd', $project_cd)
            ->where('d.doc_catg', '!=', 'MO')
            ->selectRaw('DISTINCT ON (d.doc_catg)
                d.id,
                d.doc_catg,
                d.file_path,
                d.created_at,
                c.doc_catg_descr
            ')
            ->orderBy('d.doc_catg')
            ->orderByDesc('d.created_at')
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


        $kmlFile = AssetRoadDocumentKmlFileDetails::where('project_cd', $project_cd)->first();
        $project->kml_file = $kmlFile ? [
            'id' => $kmlFile->id,
            'file_path' => $kmlFile->file_path,
            'geojson_file_path' => $kmlFile->geojson_file_path,
        ] : null;


		$projectWorkItems = DB::table('projects.prt_project_work_items_details AS d')
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

        foreach ($projectWorkItems as $item) {

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

        foreach ($itemOfWorks as $workItem) {
            $workItem->sub_items = DB::table('projects.prm_item_sub_item_of_work')
                ->select('sub_item_cd', 'sub_item_name')
                ->where('item_cd', $workItem->item_cd)
                ->get();
        }


        return view('pms.modify.editApprovedProject', compact(
            'project',
            'department',
            'projectTypes',
			//by dipshikha
            'schemes',
            //end
            'districts',
			'projectWorkItems',
            'technologies',
            'itemOfWorks',
            'constructors',
            'div',
            'states',
            'roadCategories',
            'roadTypes',
            'roadOwners',
            'project_cd',
            'contractorCategories',
            'documents',
        ));
    }

    public function update(Request $request)
    {

        DB::beginTransaction();

        try {
            $userid = Auth::user()->id;
            $office_cd = Auth::user()->office;

            $request->validate([
                'request_id' => 'required',
                'approve_reason' => 'required|string|max:255',
            ]);

            $updated = DB::table('projects.prt_project_modification_request_details')
                ->where('request_id', $request->request_id)
                ->update([
                    'status_cd'        => 2,
                    'approved_by'   => Auth::id(),
                    'remarks' => $request->approve_reason,
                    'approved_on'   => now(),
                    'updated_at'    => now(),
                ]);

            if (!$updated) {
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Request not found.'
                ]);
            }

            $modificationRequest = DB::table('projects.prt_project_modification_request_details')
                ->where('request_id', $request->request_id)
                ->first();


            $projectCd = $modificationRequest->project_cd;
            $project = PrtProjectDetail::where('project_cd', $projectCd)->first();

            $changesApplied = json_decode($modificationRequest->changes_applied, true);
            $projectInfo = $changesApplied['project_info'] ?? [];
            $othersInfo = $changesApplied['others'] ?? [];
			$othersInfo['project_type'] = $project->project_type_cd;
			$workItemsChanges = json_decode($changesApplied['work_items_json'] ?? '{}', true);
            $deletedItems = $workItemsChanges['deleted'] ?? [];
            $addedItems = $workItemsChanges['added'] ?? [];

            $updateData = [];

            if (array_key_exists('project_name', $projectInfo)) {
                $updateData['project_name'] = $projectInfo['project_name'];
            }

            if (array_key_exists('project_end_date', $projectInfo)) {
                $updateData['project_end_date'] = $projectInfo['project_end_date'];
            }

            if (array_key_exists('est_proj_cost', $projectInfo)) {
                $updateData['est_proj_cost'] = $projectInfo['est_proj_cost'];
            }

            if (array_key_exists('defect_liability_period', $projectInfo)) {
                $updateData['defect_liability_period'] = $projectInfo['defect_liability_period'];
            }

            if (array_key_exists('project_awarded_to', $projectInfo)) {
                $updateData['project_awarded_to'] = $projectInfo['project_awarded_to'];
            }

            if (array_key_exists('work_order_amount', $projectInfo)) {
                $updateData['work_order_amount'] = $projectInfo['work_order_amount'];
            }

            if (array_key_exists('work_order_no', $projectInfo)) {
                $updateData['work_order_no'] = $projectInfo['work_order_no'];
            }

            if (array_key_exists('work_order_issue_date', $projectInfo)) {
                $updateData['work_order_issue_date'] = $projectInfo['work_order_issue_date'];
            }

            if (array_key_exists('scheme_cd', $projectInfo)) {
                $updateData['scheme_cd'] = $projectInfo['scheme_cd'];
            }


            if (!empty($othersInfo)) {
				if($project->owner_dept_cd === 14 || $project->owner_dept_cd === 3){
                // ===================== NEW =====================
                if ($project->project_type_cd == 'NEW') {

                    $existingOthers = json_decode($project->others, true) ?? [];

                    // Remove temporary files
                    unset(
                        $othersInfo['road_kml_file'],
                        $othersInfo['road_geojson_file']
                    );

                    // Preserve old road details
                    $mergedOthers = array_merge($existingOthers, $othersInfo);

                    $updateData['others'] = json_encode($mergedOthers);

                }

                // ===================== UPG =====================
                elseif ($project->project_type_cd == 'UPG') {

                    $existingOthers = json_decode($project->others, true) ?? [];

                    if (isset($othersInfo['upgradation'])) {

                        $existingUpg = $existingOthers['upgradation'] ?? [];
                        $newUpg = $othersInfo['upgradation'];

                        // Remove temporary files if present
                        unset(
                            $newUpg['road_kml_file'],
                            $newUpg['road_geojson_file']
                        );

                        $existingNewAsset = $existingUpg['new_asset'] ?? [];
                        $newAsset = $newUpg['new_asset'] ?? null;

                        if ($newAsset) {

                            $roadName = trim($newAsset['road_name'] ?? '');
                            $roadLength = (float) ($newAsset['road_length'] ?? 0);

                            // If either is entered, preserve the missing value
                            if ($roadName !== '' || $roadLength > 0) {

                                if ($roadName === '' && !empty($existingNewAsset['road_name'])) {
                                    $newAsset['road_name'] = $existingNewAsset['road_name'];
                                }

                                if ($roadLength == 0 && !empty($existingNewAsset['road_length'])) {
                                    $newAsset['road_length'] = $existingNewAsset['road_length'];
                                }

                                if (empty($newAsset['road_id']) && !empty($existingNewAsset['road_id'])) {
                                    $newAsset['road_id'] = $existingNewAsset['road_id'];
                                }

                                $newUpg['new_asset'] = $newAsset;

                            } else {
                                $newUpg['new_asset'] = [
                                    'road_id' => null,
                                    'road_name' => null,
                                    'road_length' => null,
                                    'road_category' => $newAsset['road_category'] ?? '',
                                    'road_type' => $newAsset['road_type'] ?? '',
                                    'road_owner' => $newAsset['road_owner'] ?? '',
                                ];
                            }
                        }

                        // Replace only upgradation section
                        $existingOthers['upgradation'] = $newUpg;
                    }

                    $updateData['others'] = json_encode($existingOthers);
                }

                // ===================== MTN =====================
                elseif ($project->project_type_cd == 'MTN') {

                    // Remove temporary files
                    unset(
                        $othersInfo['road_kml_file'],
                        $othersInfo['road_geojson_file']
                    );

                    // No merge required
                    $updateData['others'] = json_encode($othersInfo);
                }

                // ===================== OTHER PROJECT TYPES =====================
                else {
                    $updateData['others'] = json_encode($othersInfo);
                }
            }
			if($project->owner_dept_cd === 6){
                    if ($project->project_type_cd == 'NEW') {
                        $existingOthers = json_decode($project->others, true) ?? [];
                        $mergedOthers = array_merge($existingOthers, $othersInfo);
                        $updateData['others'] = json_encode($mergedOthers);
                    } else {
                        $updateData['others'] = json_encode($othersInfo);
                    }
                }
            }


            $updateData['created_at'] = now();
            $updateData['created_by'] = $userid;
            $updateData['updated_at'] = now();
            $updateData['updated_by'] = $userid;



            if (!$project) {
                return redirect()->back()->with('error', 'Project not found!');
            }

            $histData = [
                'project_cd' => $projectCd,
                'project_name' => $project->project_name,
                'project_type_cd' => $project->project_type_cd,
                'owner_dept_cd' => $project->owner_dept_cd,
                'division_cd' => $project->division_cd,
                'sub_division_cd' => $project->sub_division_cd,
                'project_start_date' => $project->project_start_date,
                'project_end_date' => $project->project_end_date,
                'est_proj_cost' => $project->est_proj_cost,
                'defect_liability_period' => $project->defect_liability_period,
                'work_order_amount' => $project->work_order_amount,
				//by dipshikha
                'work_order_no' => $project->work_order_no,
                'work_order_issue_date' => $project->work_order_issue_date,
                'scheme_cd' => $project->scheme_cd,
                //end
                'project_awarded_to' => $project->project_awarded_to,
                'others' => $project->others,
                'is_published' => $project->is_published,
                'created_at' => $project->created_at,
                'created_by' => $project->created_by,
                'updated_at' => $project->updated_at,
                'updated_by' => $project->updated_by,
                'hist_remarks' => $request->remark . ' For request_id = ' . $request->request_id,
                'hist_created_at' => now(),
                'hist_created_by' => $userid,
            ];

            DB::table('projects.prt_project_details_hist')->insert($histData);

            if (!empty($othersInfo['road_kml_file']) && !empty($othersInfo['road_geojson_file'])) {

                // Get current KML record for the project
                $existingKml = AssetRoadDocumentKmlFileDetails::where('project_cd', $projectCd)->first();

                if ($existingKml) {
                    DB::table('asset_road_document_kml_file_details_hist')->insert([
                        'rd_system_id' => $existingKml->rd_system_id,
                        'file_path' => $existingKml->file_path,
                        'geojson_file_path' => $existingKml->geojson_file_path,
                        'file_type' => $existingKml->file_type,
                        'project_cd' => $existingKml->project_cd,
                        'created_by' => $existingKml->created_by,
                        'updated_by' => $existingKml->updated_by,
                        'created_at' => $existingKml->created_at,
                        'updated_at' => $existingKml->updated_at,
                        'created_at_office_cd' => $existingKml->created_at_office_cd,
                    ]);

                    // Remove current record (optional)
                    $existingKml->delete();
                }

                AssetRoadDocumentKmlFileDetails::create([
                    'rd_system_id' => $projectCd,
                    'file_path' => $othersInfo['road_kml_file'],
                    'geojson_file_path' => $othersInfo['road_geojson_file'],
                    'file_type' => 'kml',
                    'created_by' => $userid,
                    'updated_by' => $userid,
                    'project_cd' => $projectCd,
                    'created_at_office_cd' => $office_cd,
                ]);
            }

		foreach ($deletedItems as $widId) {

                $workItem = DB::table('projects.prt_project_work_items_details')
                    ->where('id', $widId)
                    ->first();

                if ($workItem) {

                    // Move Work Item to History
                    DB::table('projects.prt_project_work_items_details_hist')->insert([
                        'id' => $workItem->id,
                        'project_cd' => $workItem->project_cd,
                        'item_cd' => $workItem->item_cd,
                        'quantity' => $workItem->quantity,
                        'request_id' => $request->request_id,
                        'hist_created_at' => now(),
                        'hist_created_by' => $userid,
                    ]);


                    $workPlans = DB::table('projects.prt_project_work_plan_details')
                        ->where('wid_id', $widId)
                        ->get();

                    foreach ($workPlans as $plan) {

                        DB::table('projects.prt_project_work_plan_details_hist')->insert([
                            'id' => $plan->id,
                            'wid_id' => $plan->wid_id,
                            'plan_start_date' => $plan->plan_start_date,
                            'plan_end_date' => $plan->plan_end_date,
                            'wid_precedence_item_cd' => $plan->wid_precedence_item_cd,
                            'project_cd' => $plan->project_cd,
                            'request_id' => $request->request_id,
                            'hist_created_at' => now(),
                            'hist_created_by' => $userid,
                        ]);
                    }

                    $workSubItems = DB::table('projects.prt_project_work_sub_items_details')
                        ->where('work_item_details_id', $widId)
                        ->get();

                    foreach ($workSubItems as $subItem) {

                        DB::table('projects.prt_project_work_sub_items_details_hist')->insert([
                            'id' => $subItem->id,
                            'project_cd' => $subItem->project_cd,
                            'sub_item_cd' => $subItem->sub_item_cd,
                            'work_item_details_id' => $subItem->work_item_details_id,
                            'request_id' => $request->request_id,
                            'hist_created_at' => now(),
                            'hist_created_by' => $userid,
                        ]);
                    }

                    // Delete Work Plans
                    DB::table('projects.prt_project_work_plan_details')
                        ->where('wid_id', $widId)
                        ->delete();

                    DB::table('projects.prt_project_work_sub_items_details')
                        ->where('work_item_details_id', $widId)
                        ->delete();

                    DB::table('projects.prt_project_work_items_details')
                        ->where('id', $widId)
                        ->delete();
                }
            }

            foreach ($addedItems as $item) {
                $widId = DB::table('projects.prt_project_work_items_details')
                    ->insertGetId([
                        'project_cd' => $projectCd,
                        'item_cd' => $item['item_cd'],
                        'quantity' => $item['qty'],
                        'created_at' => now(),
                        'created_by' => $userid,
                        'updated_at' => now(),
                        'updated_by' => $userid,
                        'request_id' => $request->request_id,
                    ], 'id');

                foreach (($item['work_plans'] ?? []) as $plan) {

                    DB::table('projects.prt_project_work_plan_details')->insert([
                        'project_cd' => $projectCd,
                        'wid_id' => $widId,
                        'plan_start_date' => $plan['plan_start_date'],
                        'plan_end_date' => $plan['plan_end_date'],
                        'wid_precedence_item_cd' => !empty($plan['wid_precedence_item_cd'])
                            ? $plan['wid_precedence_item_cd']
                            : null,
                        'created_at' => now(),
                        'created_by' => $userid,
                        'updated_at' => now(),
                        'updated_by' => $userid,
                    ]);
                }

                // Sub Items
                foreach (($item['sub_items'] ?? []) as $subItemName) {

                    $subItemId = DB::table('projects.prm_item_sub_item_of_work')
                        ->where('sub_item_name', trim($subItemName))
                        ->value('sub_item_cd');

                    if($subItemId){
                        DB::table('projects.prt_project_work_sub_items_details')->insert([
                            'item_cd' => $item['item_cd'],
                            'project_cd' => $projectCd,
                            'work_item_details_id' => $widId,
                            'sub_item_cd' => $subItemId,
                            'created_at' => now(),
                            'created_by' => $userid,
                            'updated_at' => now(),
                            'updated_by' => $userid,
                        ]);
                    }
                }
            }

            $project->update($updateData);
			\Log::info('Update Data:', $updateData);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Modification request approved successfully.'
            ]);

        } catch (Exception $e) {
			log::info($e);
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);

        }
    }

     private function handleDocument($request, $randomCode, $project_cd)
    {
        $configPath = config('customconfigpath.PMS_DOCS_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');

            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'like', '%MODIFICATION ORDER%')->get()->first();

            if (!$docCatg) {
                return;
            }

            $extension = $file->getClientOriginalExtension();
            $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            $completeFilePath = $rootPath . '/' . $filePath;
            PrtProjectDocumentDetail::create([
                'request_id' => $randomCode,
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
                'request_id' => $randomCode,
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
                'request_id' => $randomCode,
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
                'request_id' => $randomCode,
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
                'request_id' => $randomCode,
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
    }
}
