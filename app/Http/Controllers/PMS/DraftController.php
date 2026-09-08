<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Road\AssetRoadDocumentKmlFileDetails;													

class DraftController extends Controller
{
    //
    public function saveDraft(Request $request)
    {
        $userId = Auth::user()->id;
        $cacheKey = "drafts_{$userId}";

        // Get existing drafts or initialize empty
        $drafts = Cache::get($cacheKey, []);

        // Check if a draft ID is sent from frontend (for updating)
        $draftId = $request->input('draft_id');

        if ($draftId && isset($drafts[$draftId])) {
            // Update existing draft
            $drafts[$draftId]['data'] = $request->all();
            $drafts[$draftId]['project_name'] = $request->input('project_name', $drafts[$draftId]['project_name']);
            $drafts[$draftId]['saved_at'] = now()->format('Y-m-d H:i:s');

            $message = 'Draft updated successfully';
        } else {
            // Create new draft
            $draftId = uniqid('draft_', true);
            $drafts[$draftId] = [
                'id' => $draftId,
                'data' => $request->all(),
                'project_name' => $request->input('project_name', 'Untitled Project'),
                'saved_at' => now()->format('Y-m-d H:i:s'),
            ];

            $message = 'New draft created successfully';
        }

        // Store updated drafts in cache for 7 days
        Cache::put($cacheKey, $drafts, now()->addDays(7));

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'savedAt' => now(),
            'draftId' => $draftId
        ]);
    }


    // List all drafts
    public function listDrafts()
    {
        $userId = Auth::user()->id;
        $cacheKey = "drafts_{$userId}";

        $drafts = Cache::get($cacheKey, []);

        return response()->json([
            'status' => 'success',
            'drafts' => collect($drafts)->map(function ($item) {
                return [
                    'key' => $item['id'],
                    'project_name' => $item['project_name'] ?? 'Untitled Project',
                    'saved_at' => $item['saved_at'] ?? now()->toDateTimeString(),
                ];
            })->values(),
        ]);
    }

    //Load a specific draft
    public function loadDraft($draftId)
    {
        $userId = Auth::user()->id;
        $cacheKey = "drafts_{$userId}";
        $drafts = Cache::get($cacheKey, []);

        if (!isset($drafts[$draftId])) {
            return response()->json(['status' => 'error', 'message' => 'Draft not found']);
        }

        return response()->json([
            'status' => 'success',
            'draft' => $drafts[$draftId]['data']
        ]);
    }

    //Delete a specific draft
    public function deleteDraft($draftId)
    {
        $userId = Auth::user()->id;
        $cacheKey = "drafts_{$userId}";
        $drafts = Cache::get($cacheKey, []);

        if (!isset($drafts[$draftId])) {
            return response()->json(['status' => 'error', 'message' => 'Draft not found']);
        }

        unset($drafts[$draftId]);

        Cache::put($cacheKey, $drafts, now()->addDays(7));

        return response()->json(['status' => 'success', 'message' => 'Draft deleted successfully']);
    }

    public function loadDbDraft($project_cd,$department)
    {
        try {

            $draft = DB::table('projects.prt_project_details_draft')
                ->where('project_cd', $project_cd)
                ->first();

            if (!$draft) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Draft not found'
                ]);
            }


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


            $draftArray = (array) $draft;
            $others = json_decode($draftArray['others'] ?? "{}", true);

            $upgradationData = null;
            $maintenanceData = null;

            if ($department == 15) {

                if (($others['project_type'] ?? null) === 'UPG') {

                    $upg = $others['upgradation'] ?? [];

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

                    $upgradationData = [
                        'vehicles' => $vehicles->values(),
                        'equipments' => $equipments->values()
                    ];
                }

                if (($others['project_type'] ?? null) === 'MTN') {

                    $mtn = $others['maintenance'] ?? [];

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

                    $maintenanceData = [
                        'vehicles' => $vehicles->values(),
                        'equipments' => $equipments->values()
                    ];
                }
            }

            //by dipshikha
            if ($department == 14 || $department == 3){
                //end
                if (($others['project_type'] ?? null) === 'UPG') {

                    $upgradation = $others['upgradation'] ?? [];

                    $newAsset = $upgradation['new_asset'] ?? null;

					 $roadSequence = $upgradation['road_sequence'] ?? [];	

					 
                    $upgradationData = [
                        'new_asset' => $newAsset ? [
                            'road_id' => $newAsset['road_id'] ?? null,
                            'road_name' => $newAsset['road_name'] ?? null,
                            'road_length' => $newAsset['road_length'] ?? null,
                            'road_type' => $newAsset['road_type'] ?? null,
                            'road_category' => $newAsset['road_category'] ?? null,
                            'road_owner' => $newAsset['road_owner'] ?? null,
                        ] : null,

                        'upgraded_roads' => $upgradation['upgraded_roads'] ?? [],
						'road_sequence' => $roadSequence,														 

                        'upgraded_asset_dtls' => collect($upgradation['upgraded_asset_dtls'] ?? [])
                            ->map(function ($item) {
                                return [
                                    'parent_asset_id' => $item['parent_asset_id'] ?? null,
									'start_chainage' => $item['start_chainage'] ?? null,
                                    'end_chainage' => $item['end_chainage'] ?? null,													

                                    'bridges' => $item['bridges'] ?? [],
                                    'culverts' => $item['culverts'] ?? [],
                                    'retaining_walls' => $item['retaining_walls'] ?? [],
                                ];
                            })
                            ->values(),
                    ];
                }


                if (($others['project_type'] ?? null) === 'MTN') {

                    $maintenance = $others['maintenance'] ?? [];

                    $subAssets = $maintenance['sub_asset_dtls'] ?? [];

                    $formattedAssets = [
                        'roads' => [],
                        'culverts' => [],
                        'bridges' => [],
                        'retaining_walls' => [],
                    ];

                    $assetDtls = $maintenance['asset_dtls'] ?? [];

                    if (!empty($assetDtls)) {
                        $type = $assetDtls['asset_type_cd'] ?? null;
                        $list = $assetDtls['asset_list'] ?? [];

                        if ($type == 10) {
                            $formattedAssets['roads'] = $list;
                        }
                    }

                    $subAssets = $maintenance['sub_asset_dtls'] ?? [];

                    foreach ($subAssets as $item) {

                        $type = $item['sub_asset_type_cd'] ?? null;
                        $list = $item['sub_asset_list'] ?? [];

                        switch ($type) {

                            case 0:
                                $formattedAssets['culverts'] = array_merge(
                                    $formattedAssets['culverts'],
                                    $list
                                );
                                break;

                            case 1:
                                $formattedAssets['bridges'] = array_merge(
                                    $formattedAssets['bridges'],
                                    $list
                                );
                                break;

                            case 16:
                                $formattedAssets['retaining_walls'] = array_merge(
                                    $formattedAssets['retaining_walls'],
                                    $list
                                );
                                break;
                        }
                    }

                    $maintenanceData = [
                        'assets' => [
                            'roads' => $formattedAssets['roads'] ?? [],
                            'culverts' => $formattedAssets['culverts'] ?? [],
                            'bridges' => $formattedAssets['bridges'] ?? [],
                            'retaining_walls' => $formattedAssets['retaining_walls'] ?? [],
                        ]
                    ];
                }
            }

            if ($department == 6){
                if (($others['project_type'] ?? null) === 'MTN') {

                    $maintenance = $others['maintenance'] ?? [];
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

                    $maintenanceData = [
                        'building_id' => $buildingCd,
                        'building_details' => $buildingDetails
                    ];
                }

                if (($others['project_type'] ?? null) === 'UPG') {
                    $upg = $others['upgradation'] ?? [];
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

                    $upgradationData = [
                        'building_details' => $buildingDetails
                    ];
                }
            }

			$kmlFile = AssetRoadDocumentKmlFileDetails::where('project_cd', $project_cd)->first();
																					  

            $formatted = [
                'projectTypeSelect' => $others['project_type'] ?? null,
                'project_name' => $draftArray['project_name'] ?? null,
                'division_cd' => $draftArray['division_cd'] ?? null,
                'sub_division_cd' => $draftArray['sub_division_cd'] ?? null,
                'project_start_date' => $draftArray['project_start_date'] ?? null,
                'project_end_date' => $draftArray['project_end_date'] ?? null,
                'project_awarded_to' => $draftArray['project_awarded_to'] ?? null,
                'est_proj_cost' => $draftArray['est_proj_cost'] ?? null,
                'defect_liability_period' => $draftArray['defect_liability_period'] ?? null,
                'work_order_amount' => $draftArray['work_order_amount'] ?? null,
                'is_published' => $draftArray['is_published'] ?? null,
				// done by dipshikha --start
                'work_order_no' => $draftArray['work_order_no'] ?? null,
                'work_order_issue_date' => $draftArray['work_order_issue_date'] ?? null,
                'scheme_cd' => $draftArray['scheme_cd'] ?? null,
                //done by dipshikha --end
                'kml_file' => $kmlFile ? [
                            'id' => $kmlFile->id,
                            'file_path' => $kmlFile->file_path,
                            'geojson_file_path' => $kmlFile->geojson_file_path,
                        ] : null,

                'new_road_name' => $others['new_road_name'] ?? null,
                'rd_length' => $others['new_road_length'] ?? null,
                'road_category' => $others['road_category'] ?? null,
                'road_owner' => $others['road_owner'] ?? null,
                'road_type' => $others['road_type'] ?? null,
				'tech_type_cd' => $others['tech_type_cd'] ?? null,												  
																			  
                'new_building_lat' =>  $others['new_building_lat'] ?? null,
                'new_building_lng' =>  $others['new_building_lng'] ?? null,
                'new_building_class_cd' =>  $others['new_building_class_cd'] ?? null,
                'new_building_location_cd' =>  $others['new_building_location_cd'] ?? null,
                'new_building_maintain_by_npwd' =>  $others['new_building_maintain_by_npwd'] ?? null,

                'upgradation' => $upgradationData,

                'maintenance' => $maintenanceData,
            ];

            return response()->json([
                'status' => 'success',
                'draft' => $formatted,
                'images' => $images,
                'documents' => $documents
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    public function destroy($project_cd, Request $request)
    {
        $draft = DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $project_cd)
            ->first();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'Draft not found'
            ]);
        }

        // Convert draft to array
        $data = (array) $draft;

        // Remove unwanted fields if needed
        unset($data['id']);

        // Add history fields
        $data['hist_remarks'] = $request->remarks ?? 'Deleted';
        $data['hist_created_at'] = now();
        $data['hist_updated_at'] = now();
        $data['hist_created_by'] = auth()->id();
        $data['hist_updated_by'] = auth()->id();

        // Single insert only
        DB::table('projects.prt_project_details_draft_hist')->insert($data);

		AssetRoadDocumentKmlFileDetails::where('project_cd', $project_cd)->delete();																			
        // Delete from draft
        DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $project_cd)
            ->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleted successfully'
        ]);
    }
}
