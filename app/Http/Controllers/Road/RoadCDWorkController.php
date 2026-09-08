<?php

namespace App\Http\Controllers\Road;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\AssetMasterHeadWall;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterAbutmentType;
use App\Models\AssetMasterBearingType;
use App\Models\Road\AssetRoadDetail;
use App\Models\AssetMasterToeWallType;
use App\Models\AssetMasterCatchPitType;
use App\Models\AssetMasterConstructionMaterialTypes;
use App\Models\AssetMasterFaceWallType;
use App\Models\AssetMasterRdCdWorksType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterDocumentCategory;
use App\Models\AssetMasterCulvertOutletType;
use App\Models\AssetMasterHeadWallsStreamType;
use App\Models\AssetMasterHumePipeSpecification;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\CD_Works\AssetRoadCdworkDetail;
use App\Models\Road\Master\AssetMasterHandrailType;
use App\Models\Road\Master\AssetMasterWingWallType;
use App\Models\Road\Master\AssetMasterSafetyApronType;
use App\Models\Road\CD_Works\AssetRoadCdworkDetailsDraft;
use App\Models\Road\CD_Works\AssetRoadCdworkDocumentDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkHeadWallDetails;
use App\Models\Road\CD_Works\AssetRoadCdworkHeadWallDraftDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkImageDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkWingWallDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkWingWallDraftDetail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RoadCDWorkController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request)
    {
        $systemId = $request->id;
        // saiful # 21-04-2026 # Start
        $asset_plan_id = $request->asset_plan_id;
        // saiful # 21-04-2026 # End
        $roadDetails = AssetRoadDetail::find($systemId);
        session(['system_id' => $systemId]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
        session()->forget('culvert_id');  // modified by Pulak 30-04-26
        // saiful # 21-04-2026 # Start
        session(['asset_plan_id' => $asset_plan_id]);
        // saiful # 21-04-2026 # End
        return redirect()->route('createCDWorks');
    }

    public function create(Request $request)
    {
        try {
            if (!session()->has('system_id')) {
                throw new \RuntimeException('Road system ID not found!');
            }
            //Saiful -- 29-04-2026 -- Start
            $assetPlanId = session('asset_plan_id');
            $redefineAssetFromProject = $request->redefineAssetFromProject ?? false;
            //Saiful -- 29-04-2026 -- Start
            $road_system_id = session('system_id');
            $culvert_id = session('culvert_id') ?? null;  // modified by Pulak 30-04-26	
            $cdWorkTypes = AssetMasterRdCdWorksType::all();
            $headWalls = AssetMasterHeadWall::all();
            $roadConditions = AssetMasterRoadCondition::all();
            $faceWallTypes = AssetMasterFaceWallType::all();
            $outletTypes = AssetMasterCulvertOutletType::all();
            $pitTypes = AssetMasterCatchPitType::all();
            $toeWallTypes = AssetMasterToeWallType::all();
            $streamTypes = AssetMasterHeadWallsStreamType::all();
            $humePipeSpecifications = AssetMasterHumePipeSpecification::all();
            $wingWallTypes = AssetMasterWingWallType::all();
            $saftyApronTypes = AssetMasterSafetyApronType::all();
            $handRailTypes = AssetMasterHandrailType::all();
            $bearingTypes = AssetMasterBearingType::all();
            $abutmentTypes = AssetMasterAbutmentType::all();
            $constructionMaterials = AssetMasterConstructionMaterialTypes::all();
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('chainage_from', 'chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->first();

            $cd_work_details = DB::table('asset_road_cdwork_details_draft')
                ->select(
                    'asset_road_cdwork_details_draft.*',
                    'asset_master_rd_cdworks_type.cdwoerk_descr',
                    'asset_master_road_condition.rd_condition_descr as cd_condition',
                    'asset_master_culvert_outlet_types.outlet_type_descr',
                    'asset_master_catch_pit_types.catch_pit_type_descr',
                    'asset_master_road_condition_cp.rd_condition_descr as cp_condition',
                    'asset_master_hume_pipe_specifications.hume_pipe_descr',
                    'asset_master_abutment_types.abutment_type_descr',
                    'asset_master_bearing_types.bearing_type_descr',  // modified by Pulak 30-04-26
                    'asset_master_safety_apron_types.apron_type_descr',
                    'asset_master_construction_material_types.const_material_type_descr',
                    //Saiful -- 22-04-2026 -- Start
                    'pp.project_cd'
                    //Saiful -- 22-04-2026 -- End
                )
                ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details_draft.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details_draft.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details_draft.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
                ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details_draft.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
                ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details_draft.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
                ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details_draft.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
                ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details_draft.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
                ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details_draft.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
                ->leftJoin('asset_master_bearing_types', 'asset_road_cdwork_details_draft.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')  // modified by Pulak 30-04-26
                //Saiful -- 22-04-2026 -- Start
                ->leftJoin('prt_project_asset_plan as pp', 'asset_road_cdwork_details_draft.asset_plan_id', '=', 'pp.id')
                //Saiful -- 22-04-2026 -- End
                ->where('rd_system_id', '=', $road_system_id)  // modified by Pulak 30-04-26
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->where('sent_for_finalize', '=', 'N')
                ->orderBy('updated_at', 'desc')
                ->get();

            return view('road.cd_work.index', compact(
                'cd_work_details',
                'cdWorkTypes',
                'road_system_id',
                'headWalls',
                'roadConditions',
                'faceWallTypes',
                'outletTypes',
                'pitTypes',
                'toeWallTypes',
                'roadChainage',
                'streamTypes',
                'humePipeSpecifications',
                'wingWallTypes',
                'saftyApronTypes',
                'handRailTypes',
                'bearingTypes',
                'abutmentTypes',
                'constructionMaterials',
                'culvert_id',   // modified by Pulak 30-04-26
                //saiful # 29-04-2026 # Start
                'assetPlanId',
                'redefineAssetFromProject'
                //saiful # 29-04-2026 # End
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

    public function store(Request $request)
    {
        //dd($request->all());					  
        $request->validate([
            'chainage' => 'required',
            'culvert_type' => 'required',
            'images.*' => 'required|image|mimes:jpeg,jpg,png|max:1024',
        ]);

        try {
            $system_id = $request->road_system_id;
            $userid = Auth::user()->id;
            $randomNumber = mt_rand(100, 999);
            $culvertNumber = 'CD' . mt_rand(1000, 9999);
            $currentTime = time();
            $randomCode = $userid . $currentTime . $randomNumber;
            $location = $request->lat . ',' . $request->lng;

            // get the maker checker status for sub assets
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('0');

            // Common fields (both tables have)
            $commonData = [
                'rd_cdwork_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
                'culvert_no' => $culvertNumber,
                'chainage' => $request->chainage,
                'cdwork_outlet' => $request->outlet,
                'cdwork_thickness_side_wall' => $request->thickness_of_side_wall,
                'cdwork_thickness_top_slab' => $request->thickness_of_top_slab,
                'cdwork_thickness_bottom_slab' => $request->thickness_of_bottom_slab,
                'discharge' => $request->discharge,
                'year_of_construction' => $request->year_of_contruction,
                'year_of_rehabilitation' => $request->year_of_rehabilitation,
                'cdwork_condition' => $request->condition,
                'outlet_type_cd' => $request->outlet_type_cd,
                'culvert_type_cd' => $request->culvert_type,
                'culvert_location' => $location,
                // Box Culvert Fields
                'no_of_cell' => $request->cell_no,
                'width_each_cell' => $request->each_cell_width,
                'heigth_each_cell' => $request->each_cell_height,
                'length_span' => $request->length_span_bxc,
                'cdwork_has_wing_wall' => 'N',

                // Hume Pipe
                'no_of_rows' => $request->no_of_rows,
                'height_of_earth_cushion' => $request->cd_cussion,
                'pipe_diameter' => $request->pipe_diameter,
                'culvert_width' => $request->culvert_width,
                'pipe_specification' => $request->pipe_specification,

                // Slab Culvert
                'span' => $request->span,
                'slab_width' => $request->slab_width_slb,
                'no_of_wing_wall' => $request->no_of_wing_wall,
                'abutment_type_cd' => $request->abutment_type_slb,
                'abutment_height' => $request->abutment_height_slb,
                'bearing_type_cd' => $request->bearing_type,

                'const_material_type_cd' => null,

                'cussion' => 'N',
                'cdwork_no_of_vents' => $request->no_of_vents,
                'created_by' => auth()->user()->id,
                'updated_by' => Auth::user()->id,
                'created_at_office_cd' => Auth::user()->office,
                'catch_pit_availability' => $request->catch_pit_availability,
                'catch_pit_type_cd' => $request->catch_pit_type_cd,
                'catch_pit_width' => $request->catch_pit_width,
                'catch_pit_condition' => $request->catch_pit_condition,
                'cdwork_remark' => $request->cdwork_remark,
                'cdwork_has_head_wall' => 'N',
                'cdwork_has_safety_apron' => $request->safety_apron,
                'cdwork_safety_apron_type' => $request->cdwork_safety_apron_type,
                'cdwork_safety_apron_outlet' => $request->cdwork_safety_apron_outlet,
                'cdwork_safety_apron_width' => $request->cdwork_safety_apron_width,
                'cdwork_safety_apron_length' => $request->cdwork_safety_apron_length,
                'cdwork_safety_apron_slab_thickness' => $request->cdwork_safety_apron_slab_thickness,
                'cdwork_safety_apron_hand_rail_type' => $request->cdwork_safety_apron_hand_rail_type,
                'catch_pit_heigth' => $request->catch_pit_heigth,
                'catch_pit_breadth' => $request->catch_pit_breadth,
                'catch_pit_thickness' => $request->catch_pit_thickness,
                'slab_thickness' => null,
                'slab_length' => $request->slab_length,
                // Saiful # 21-04-2026 # Start
                'asset_plan_id' => $request->hdn_asset_plan_id ?? null,
                // Saiful # 21-04-2026 # End
            ];

            if ($makerCheckerStatus === 'Y') {
                if ($request->culvert_type == 'BXC') {
                    $commonData['const_material_type_cd'] = $request->box_construction_material;
                    if ($request->wing_wall == 'Y') {
                        $commonData['cdwork_has_wing_wall'] = 'Y';
                        $this->handleBoxCulvertWingWallData($request, $randomNumber, $randomCode, $userid, $makerCheckerStatus);
                    }
                }

                if ($request->culvert_type == 'SLB') {
                    $commonData['slab_width'] = $request->slab_width_slb;
                    $commonData['const_material_type_cd'] = $request->slab_construction_material;
                    if ($request->slab_wing_wall == 'Y') {
                        $commonData['cdwork_has_wing_wall'] = 'Y';
                        $this->handleSlabCulvertWingWallData($request, $randomNumber, $randomCode, $userid, $makerCheckerStatus);
                    }
                }

                if ($request->culvert_type == 'HPC') {
                    $commonData['cussion'] = 'Y';
                    if ($request->head_wall == 'Y') {
                        $commonData['cdwork_has_head_wall'] = 'Y';
                        $requestHeadWallData = $request->all();
                        $this->handleHumePipeHeadWallData($requestHeadWallData, $randomNumber, $randomCode, $userid, $makerCheckerStatus);
                    }
                }

                $status = AssetRoadCdworkDetailsDraft::create($commonData);
                if ($status) {
                    //saiful 21-04-2026 -- Start
                    if ($request->hdn_asset_plan_id != null)
                        $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                            ->where('id', $request->hdn_asset_plan_id)
                            ->where('no_of_new_asset', '>', 0)
                            ->decrement('no_of_new_asset', 1, [
                                'updated_at' => now(),
                                'remarks' => 'Culvert Created on: ' . now()
                            ]);
                    session()->forget('asset_plan_id');
                    //saiful 21-04-2026 -- End
                    $this->handleDocument($request, $randomCode, $system_id);
                    return redirect()->back()
                        ->with('success', 'CD Work details save successfully with culvert number : ' . $culvertNumber)
                        ->with('rd_system_id', $system_id);
                } else {
                    //saiful -- 22-04-2026 -- start
                    session()->forget('asset_plan_id');
                    //saiful -- 22-04-2026 -- End
                    return redirect()->back()
                        ->with('failed', 'CD Work details not save for the culvert number : ' . $culvertNumber)
                        ->with('rd_system_id', $system_id);
                }
            } else {
                $extraData = [
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),
                ];
                if ($request->culvert_type == 'BXC') {
                    $commonData['const_material_type_cd'] = $request->box_construction_material;
                    if ($request->wing_wall == 'Y') {
                        $commonData['cdwork_has_wing_wall'] = 'Y';
                        $this->handleBoxCulvertWingWallData($request, $randomNumber, $randomCode, $userid, $makerCheckerStatus);
                    }
                }

                if ($request->culvert_type == 'SLB') {
                    $commonData['slab_width'] = $request->slab_width_slb;
                    $commonData['const_material_type_cd'] = $request->slab_construction_material;
                    if ($request->slab_wing_wall == 'Y') {
                        $commonData['cdwork_has_wing_wall'] = 'Y';
                        $this->handleSlabCulvertWingWallData($request, $randomNumber, $randomCode, $userid, $makerCheckerStatus);
                    }
                }

                if ($request->culvert_type == 'HPC') {
                    $commonData['cussion'] = 'Y';
                    if ($request->head_wall == 'Y') {
                        $commonData['cdwork_has_head_wall'] = 'Y';
                        $requestHeadWallData = $request->all();
                        $this->handleHumePipeHeadWallData($requestHeadWallData, $randomNumber, $randomCode, $userid, $makerCheckerStatus);
                    }
                }
                $status = AssetRoadCdworkDetail::create(array_merge($commonData, $extraData));
                if ($status) {
                    //saiful 21-04-2026 -- Start
                    if ($request->hdn_asset_plan_id != null)
                        $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                            ->where('id', $request->hdn_asset_plan_id)
                            ->where('no_of_new_asset', '>', 0)
                            ->decrement('no_of_new_asset', 1, [
                                'updated_at' => now(),
                                'remarks' => 'Culvert Created on: ' . now()
                            ]);
                    session()->forget('asset_plan_id');
                    //saiful 21-04-2026 -- End
                    $this->handleDocument($request, $randomCode, $system_id);
                    return redirect()->back()
                        ->with('success', 'CD Work details save successfully with culvert number : ' . $culvertNumber)
                        ->with('rd_system_id', $system_id);
                } else {
                    //saiful -- 22-04-2026 -- start
                    session()->forget('asset_plan_id');
                    //saiful -- 22-04-2026 -- End
                    return redirect()->back()
                        ->with('failed', 'CD Work details not save for the culvert number : ' . $culvertNumber)
                        ->with('rd_system_id', $system_id);
                }
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
            //saiful -- 22-04-2026 -- start
            session()->forget('asset_plan_id');
            //saiful -- 22-04-2026 -- End
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            //saiful -- 22-04-2026 -- start
            session()->forget('asset_plan_id');
            //saiful -- 22-04-2026 -- End
            return response()->view('errors.generic', [], 500);
        }
    }

    private function handleBoxCulvertWingWallData($request, $randomNumber, $randomCode, $userid, $makerCheckerStatus)
    {
        // dd($request->all());					  
        $wingWallData = [
            'wing_wall_sr_no' => $randomNumber,
            'rd_cdwork_cd' => $randomCode,
            'top_width' => null,
            'bottom_width' => null,
            'height1' => null,
            'height2' => null,
            'wing_wall_type_cd' => null,
            'thickness' => null,
            'slope' => null,
            'transitions' => null,
            'angle' => null,
            'radius' => null,
            'length' => null,
            'created_by' => $userid,
        ];
        if ($request->is_same_wing_wall == 'Y') {
            $wingWallData['top_width'] = $request->top_width;
            $wingWallData['bottom_width'] = $request->bottom_width;
            $wingWallData['height1'] = $request->height1;
            $wingWallData['height2'] = $request->height2;
            $wingWallData['wing_wall_type_cd'] = $request->wing_wall_type_box;
            $wingWallData['slope'] = $request->slope;
            $wingWallData['angle'] = $request->angle_box;
            $wingWallData['radius'] = $request->radius_box;
            $wingWallData['length'] = $request->length;
            if ($makerCheckerStatus === 'Y') {
                AssetRoadCdworkWingWallDraftDetail::create($wingWallData);
            } else {
                AssetRoadCdworkWingWallDetail::create($wingWallData);
            }
        }
        if ($request->is_same_wing_wall == 'N') {
            $requestWingData = $request->all();
            for ($i = 1; $i <= 4; $i++) {
                $wingWallData['wing_wall_sr_no'] = $i;
                $wingWallData['top_width'] = $requestWingData['top_width_' . $i];
                $wingWallData['bottom_width'] = $requestWingData['bottom_width_' . $i];
                $wingWallData['height1'] = $requestWingData['height1_' . $i];
                $wingWallData['height2'] = $requestWingData['height2_' . $i];
                $wingWallData['wing_wall_type_cd'] = $requestWingData['box_wing_wall_type_' . $i];
                $wingWallData['slope'] = $requestWingData['slope_' . $i];
                $wingWallData['angle'] = $requestWingData['box_angle_' . $i];
                $wingWallData['radius'] = $requestWingData['box_radius_' . $i];
                $wingWallData['length'] = $requestWingData['length_' . $i];
                if ($makerCheckerStatus === 'Y') {
                    AssetRoadCdworkWingWallDraftDetail::create($wingWallData);
                } else {
                    AssetRoadCdworkWingWallDetail::create($wingWallData);
                }
            }
        }
    }

    private function handleSlabCulvertWingWallData($request, $randomNumber, $randomCode, $userid, $makerCheckerStatus)
    {
        $wingWallData = [
            'wing_wall_sr_no' => $randomNumber,
            'rd_cdwork_cd' => $randomCode,
            'top_width' => null,
            'bottom_width' => null,
            'height1' => null,
            'height2' => null,
            'wing_wall_type_cd' => null,
            'thickness' => null,
            'slope' => null,
            'transitions' => null,
            'angle' => null,
            'radius' => null,
            'length' => null,
            'created_by' => $userid,
        ];

        if ($request->is_same_wing_wall_slab_vented == 'Y') {
            $wingWallData['top_width'] = $request->top_width;
            $wingWallData['bottom_width'] = $request->bottom_width;
            $wingWallData['height1'] = $request->height1;
            $wingWallData['height2'] = $request->height2;
            $wingWallData['wing_wall_type_cd'] = $request->wing_wall_type;
            $wingWallData['slope'] = $request->slope;
            $wingWallData['angle'] = $request->angle;
            $wingWallData['radius'] = $request->radius;
            $wingWallData['length'] = $request->length;
            if ($makerCheckerStatus === 'Y') {
                AssetRoadCdworkWingWallDraftDetail::create($wingWallData);
            } else {
                AssetRoadCdworkWingWallDetail::create($wingWallData);
            }
        }
        if ($request->is_same_wing_wall_slab_vented == 'N') {
            $requestWingData = $request->all();
            for ($i = 1; $i <= 4; $i++) {
                $wingWallData['wing_wall_sr_no'] = $randomNumber + $i;
                $wingWallData['top_width'] = $requestWingData['top_width_' . $i];
                $wingWallData['bottom_width'] = $requestWingData['bottom_width_' . $i];
                $wingWallData['height1'] = $requestWingData['height1_' . $i];
                $wingWallData['height2'] = $requestWingData['height2_' . $i];
                $wingWallData['wing_wall_type_cd'] = $requestWingData['wing_wall_type_' . $i];
                $wingWallData['slope'] = $requestWingData['slope_' . $i];
                $wingWallData['angle'] = $requestWingData['angle_' . $i];
                $wingWallData['radius'] = $requestWingData['radius_' . $i];
                $wingWallData['length'] = $requestWingData['length_' . $i];
                if ($makerCheckerStatus === 'Y') {
                    AssetRoadCdworkWingWallDraftDetail::create($wingWallData);
                } else {
                    AssetRoadCdworkWingWallDetail::create($wingWallData);
                }
            }
        }
    }

    private function handleHumePipeHeadWallData($requestHeadWallData, $randomNumber, $randomCode, $userid, $makerCheckerStatus)
    {
        if ($makerCheckerStatus == 'Y') {
            for ($i = 1; $i <= 2; $i++) {
                $headWallData = [
                    'head_wall_sr_no' => $randomNumber,
                    'rd_cdwork_cd' => $randomCode,
                    'head_wall_type_cd' => $requestHeadWallData["hume_head_wall_type_$i"],
                    'head_wall_stream_type_cd' => $requestHeadWallData["hume_head_wall_stream_type_$i"],
                    'head_wall_length' => $requestHeadWallData["hume_head_wall_length_$i"],
                    'top_width' => $requestHeadWallData["hume_head_wall_width_$i"],
                    'head_wall_heigth' => $requestHeadWallData["hume_head_wall_height_$i"],
                    'created_by' => $userid,
                ];
                AssetRoadCdworkHeadWallDraftDetail::create($headWallData);
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                $headWallData = [
                    'head_wall_sr_no' => $randomNumber,
                    'rd_cdwork_cd' => $randomCode,
                    'head_wall_type_cd' => $requestHeadWallData["hume_head_wall_type_$i"],
                    'head_wall_stream_type_cd' => $requestHeadWallData["hume_head_wall_stream_type_$i"],
                    'head_wall_length' => $requestHeadWallData["hume_head_wall_length_$i"],
                    'head_wall_width' => $requestHeadWallData["hume_head_wall_width_$i"],
                    'head_wall_heigth' => $requestHeadWallData["hume_head_wall_height_$i"],
                    'created_by' => $userid,
                ];
                AssetRoadCdworkHeadWallDetails::create($headWallData);
            }
        }
    }

    private function handleDocument($request, $randomCode, $system_id)
    {
        // dd($request->all());					  
        $configPath = config('customconfigpath.CDWORK_DOCS_PATH');
        $configImagePath = config('customconfigpath.CDWORK_IMAGES_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $randomNumber = mt_rand(100, 999);
                // get image extension
                $extension = $image->getClientOriginalExtension();
                $uniqueFileName = 'culvert_' . $randomCode . '_' . $randomNumber . '.' . $extension;
                $folderPath = $configImagePath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $image->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                AssetRoadCdworkImageDetail::create([
                    'rd_cdwork_cd' => $randomCode,
                    'rd_system_id' => $system_id,
                    'image_path' => $completeFilePath,
                    'file_type' => $image->getClientOriginalExtension(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'created_at_office_cd' => Auth::user()->office,
                    'lat' => $request->lat,
                    'lon' => $request->lng,
                ]);
            }
        }

        if ($request->hasFile('workorder')) {
            $file = $request->file('workorder');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Work Order')->get()->first();
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

            AssetRoadCdworkDocumentDetail::create([
                'rd_cdwork_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
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

            AssetRoadCdworkDocumentDetail::create([
                'rd_cdwork_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
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

            AssetRoadCdworkDocumentDetail::create([
                'rd_cdwork_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
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

            AssetRoadCdworkDocumentDetail::create([
                'rd_cdwork_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }
    }

    // modified by Pulak 30-04-26
    public function getCDWorkDetails(Request $request, $id)
    {
        //
        $road_system_id = session('system_id');
        $cd_work_details = DB::table('asset_road_cdwork_details_draft')
            ->select(
                'asset_road_cdwork_details_draft.*',
                'asset_master_rd_cdworks_type.cdwoerk_descr',
                'asset_master_road_condition.rd_condition_descr as cd_condition',
                'asset_master_culvert_outlet_types.outlet_type_descr',
                'asset_master_catch_pit_types.catch_pit_type_descr',
                'asset_master_road_condition_cp.rd_condition_descr as cp_condition',
                'asset_master_hume_pipe_specifications.hume_pipe_descr',
                'asset_master_abutment_types.abutment_type_descr',
                'asset_master_safety_apron_types.apron_type_descr',
                'asset_master_construction_material_types.const_material_type_descr'
            )
            ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details_draft.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
            ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details_draft.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
            ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details_draft.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
            ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details_draft.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
            ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details_draft.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
            ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details_draft.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
            ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
            ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details_draft.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
            ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details_draft.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
            ->where('rd_system_id', '=', $road_system_id)
            ->where('rd_cdwork_cd', '=', $id)
            // ->where('created_at_office_cd', '=', auth()->user()->office)
            ->where('sent_for_finalize', '=', 'N')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($cd_work_details as $cd_work_detail) {

            $cd_work_detail->wing_wall = DB::table('asset_road_cdwork_wing_wall_draft_details')
                ->where('rd_cdwork_cd', '=', $cd_work_detail->rd_cdwork_cd)
                ->orderBy('wing_wall_sr_no')
                ->get();

            $cd_work_detail->wing_wall_count = count($cd_work_detail->wing_wall) > 1 ? 'N' : 'Y';

            $cd_work_detail->head_wall = DB::table('asset_road_cdwork_head_wall_draft_details')
                ->where('rd_cdwork_cd', '=', $cd_work_detail->rd_cdwork_cd)
                ->orderBy('head_wall_sr_no')
                ->get();

            $cd_work_detail->head_wall_count = count($cd_work_detail->head_wall) > 1 ? 'N' : 'Y';

        }

        $cd_work = $cd_work_details->first();

        return response()->json($cd_work);
    }
    // modified by Pulak 30-04-26						  

    public function edit(Request $request)
    {
        $culvert_id = $request->id;
        Log::info('Edit culvert_id: ' . $culvert_id);
        //Saiful -- 29-04-2026 -- Start
        $assetPlanId = $request->assetPlanId ?? null;
        $redefineAssetFromProject = $request->redefineAssetFromProject ?? false;
        //Saiful -- 29-04-2026 -- Start
        $road_system_id = session('system_id');
        $cdWorkTypes = AssetMasterRdCdWorksType::all();
        $headWalls = AssetMasterHeadWall::all();
        $roadConditions = AssetMasterRoadCondition::all();
        $faceWallTypes = AssetMasterFaceWallType::all();
        $outletTypes = AssetMasterCulvertOutletType::all();
        $pitTypes = AssetMasterCatchPitType::all();
        $toeWallTypes = AssetMasterToeWallType::all();
        $streamTypes = AssetMasterHeadWallsStreamType::all();
        $humePipeSpecifications = AssetMasterHumePipeSpecification::all();
        $wingWallTypes = AssetMasterWingWallType::all();
        $saftyApronTypes = AssetMasterSafetyApronType::all();
        $handRailTypes = AssetMasterHandrailType::all();
        $bearingTypes = AssetMasterBearingType::all();
        $abutmentTypes = AssetMasterAbutmentType::all();
        $constructionMaterials = AssetMasterConstructionMaterialTypes::all();
        $roadChainage = DB::table('asset_road_chainage_mappings')
            ->select('chainage_from', 'chainage_to')
            ->where('rd_system_id', '=', $road_system_id)
            ->first();

        $cd_work_details = DB::table('asset_road_cdwork_details_draft')
            ->select(
                'asset_road_cdwork_details_draft.*',
                'asset_master_rd_cdworks_type.cdwoerk_descr',
                'asset_master_road_condition.rd_condition_descr as cd_condition',
                'asset_master_culvert_outlet_types.outlet_type_descr',
                'asset_master_catch_pit_types.catch_pit_type_descr',
                'asset_master_road_condition_cp.rd_condition_descr as cp_condition',
                'asset_master_hume_pipe_specifications.hume_pipe_descr',
                'asset_master_abutment_types.abutment_type_descr',
                'asset_master_bearing_types.bearing_type_descr',  // modified by Pulak 30-04-26
                'asset_master_safety_apron_types.apron_type_descr',
                'asset_master_construction_material_types.const_material_type_descr',
                //Saiful -- 22-04-2026 -- Start
                'pp.project_cd'
                //Saiful -- 22-04-2026 -- End
            )
            ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details_draft.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
            ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details_draft.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
            ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details_draft.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
            ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details_draft.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
            ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details_draft.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
            ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details_draft.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
            ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
            ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details_draft.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
            ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details_draft.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
            ->leftJoin('asset_master_bearing_types', 'asset_road_cdwork_details_draft.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')  // modified by Pulak 30-04-26
            //Saiful -- 22-04-2026 -- Start
            ->leftJoin('prt_project_asset_plan as pp', 'asset_road_cdwork_details_draft.asset_plan_id', '=', 'pp.id')
            //Saiful -- 22-04-2026 -- End
            ->where('rd_system_id', '=', $road_system_id)  // modified by Pulak 30-04-26
            ->where('created_at_office_cd', '=', auth()->user()->office)
            ->where('sent_for_finalize', '=', 'N')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('road.cd_work.editCdWork', compact(
            'culvert_id',
            'cd_work_details',
            'cdWorkTypes',
            'road_system_id',
            'headWalls',
            'roadConditions',
            'faceWallTypes',
            'outletTypes',
            'pitTypes',
            'toeWallTypes',
            'roadChainage',
            'streamTypes',
            'humePipeSpecifications',
            'wingWallTypes',
            'saftyApronTypes',
            'handRailTypes',
            'bearingTypes',
            'abutmentTypes',
            'constructionMaterials',
            //saiful # 29-04-2026 # Start
            'assetPlanId',
            'redefineAssetFromProject'
            //saiful # 29-04-2026 # End
        ));
    }

    // modified by Pulak 30-04-26

    public function update(Request $request)
    {
		//dd($request->all());					  
        try {
            if ((isset($request->id)) and (isset($request->_token))) {
                $validator = Validator::make($request->all(), [
                    'chainage' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'validation Failed'
                    ]);
                }
                // modified by Pulak 30-04-26
                $cdWorkData = AssetRoadCdworkDetailsDraft::find($request->id);

                if (!$cdWorkData) {
                    Log::warning('CD Work not found for ID: ' . $request->id);
                    return back()->with('failed', 'CD Work not found');
                }

                if ($cdWorkData) {

                    // 🔹 BASIC IDENTIFIERS
                    $cdWorkData->rd_system_id = $request->road_system_id;
                    $cdWorkData->culvert_no = $request->culvert_no;
                    $cdWorkData->chainage = $request->chainage;

                    // 🔹 TYPE
                    $cdWorkData->culvert_type_cd = $request->culvert_type;

                    // 🔹 BOX CULVERT
                    $cdWorkData->no_of_cell = $request->cell_no;
                    $cdWorkData->width_each_cell = $request->each_cell_width;
                    $cdWorkData->heigth_each_cell = $request->each_cell_height;
                    $cdWorkData->cdwork_thickness_side_wall = $request->thickness_of_side_wall;
                    $cdWorkData->cdwork_thickness_top_slab = $request->thickness_of_top_slab;
                    $cdWorkData->cdwork_thickness_bottom_slab = $request->thickness_of_bottom_slab;

                    // 🔹 SLAB CULVERT
                    $cdWorkData->span = $request->span;
                    $cdWorkData->slab_width = $request->slab_width_slb;
                    $cdWorkData->slab_length = $request->slab_length;
                    $cdWorkData->slab_thickness = $request->slab_thickness;
                    $cdWorkData->no_of_wing_wall = $request->no_of_wing_wall;

                    // 🔹 VENTED CULVERT
                    //   $cdWorkData->cdwork_no_of_vents = $request->no_of_vents;

                    // 🔹 HUME PIPE
                    $cdWorkData->no_of_rows = $request->no_of_rows;
                    $cdWorkData->pipe_diameter = $request->pipe_diameter;
                    $cdWorkData->pipe_specification = $request->pipe_specification;
                    $cdWorkData->culvert_width = $request->culvert_width;
                    $cdWorkData->height_of_earth_cushion = $request->cd_cussion;
                    $cdWorkData->cussion = $request->cd_cussion ? 'Y' : 'N';

                    // 🔹 GENERAL DIMENSIONS
                    $cdWorkData->length_span = $request->length_span_bxc;
                    $cdWorkData->cdwork_outlet = $request->cdwork_outlet;

                    // 🔹 WING WALL / HEAD WALL
                    $cdWorkData->cdwork_has_wing_wall = $request->slab_wing_wall ?? 'N';
                    $cdWorkData->cdwork_has_head_wall = $request->head_wall ?? 'N';

					//modified by PUlak 01-07-26							
                    // 🔹 SAFETY APRON
					if ($request->safety_apron == 'Y') {
						
                    $cdWorkData->cdwork_has_safety_apron = 'Y';
                    $cdWorkData->cdwork_safety_apron_type = $request->cdwork_safety_apron_type;
                    $cdWorkData->cdwork_safety_apron_outlet = $request->cdwork_safety_apron_outlet;
                    $cdWorkData->cdwork_safety_apron_width = $request->cdwork_safety_apron_width;
                    $cdWorkData->cdwork_safety_apron_length = $request->cdwork_safety_apron_length;
                    $cdWorkData->cdwork_safety_apron_slab_thickness = $request->cdwork_safety_apron_slab_thickness;
                    $cdWorkData->cdwork_safety_apron_hand_rail_type = $request->cdwork_safety_apron_hand_rail_type;

				} else {

                        $cdWorkData->cdwork_has_safety_apron = 'N';
                        $cdWorkData->cdwork_safety_apron_type = null;
                        $cdWorkData->cdwork_safety_apron_outlet = null;
                        $cdWorkData->cdwork_safety_apron_width = null;
                        $cdWorkData->cdwork_safety_apron_length = null;
                        $cdWorkData->cdwork_safety_apron_slab_thickness = null;
                        $cdWorkData->cdwork_safety_apron_hand_rail_type = null;

                    }			


                    // 🔹 MATERIAL / STRUCTURE
                    $cdWorkData->const_material_type_cd = $request->slab_construction_material;
                    $cdWorkData->abutment_type_cd = $request->abutment_type_slb;
                    $cdWorkData->abutment_height = $request->abutment_height_slb;
                    $cdWorkData->bearing_type_cd = $request->bearing_type;

                    // 🔹 FLOW / CONDITION
                    $cdWorkData->discharge = $request->discharge;
                    $cdWorkData->cdwork_condition = $request->condition;

                    // 🔹 YEAR (FIXED TYPO)
                    $cdWorkData->year_of_construction = $request->year_of_contruction;
                    $cdWorkData->year_of_rehabilitation = $request->year_of_rehabilitation;

                    // 🔹 LOCATION

                    $cdWorkData->culvert_location = $request->culvert_location;
                    $cdWorkData->outlet_type_cd = $request->outlet_type_cd;

                    // 🔹 CATCH PIT
                    $cdWorkData->catch_pit_availability = $request->catch_pit_availability ?? 'N';
                    $cdWorkData->catch_pit_type_cd = $request->catch_pit_type_cd;
                    $cdWorkData->catch_pit_width = $request->catch_pit_width;
                    $cdWorkData->catch_pit_heigth = $request->catch_pit_heigth;
                    $cdWorkData->catch_pit_breadth = $request->catch_pit_breadth;
                    $cdWorkData->catch_pit_thickness = $request->catch_pit_thickness;
                    $cdWorkData->catch_pit_condition = $request->catch_pit_condition;

                    // 🔹 REMARK
                    $cdWorkData->cdwork_remark = $request->cdwork_remark;
                    $cdWorkData->updated_by = Auth::user()->id;// modified by Pulak 30-04-26
                    $cdWorkData->updated_at = now();

                    if ($request->wing_wall === "Y" || $request->slab_wing_wall === "Y") {
                        $this->deleteWingWallValue($request->id);
                    }


                    if ($request->head_wall === "Y") {
                        $this->deleteHeadWallValue($request->id);
                    }

                    $randomNumber = 1234;
                    $randomCode = $request->id;
                    $userid = Auth::user()->id;

                    if ($request->culvert_type == 'BXC') {
                        // $cdWorkData->vent_height = $request->vent_height_bxc;
                        if ($request->wing_wall == 'Y') {
                            $cdWorkData->cdwork_has_wing_wall = 'Y';
                            $this->handleBoxCulvertWingWallData(
                                $request,
                                $randomNumber,
                                $randomCode,
                                $userid,
                                'Y' // makerCheckerStatus
                            );
                        }
                    }

                    if ($request->culvert_type == 'SLB') {
                        if ($request->slab_wing_wall == 'Y') {
                            $cdWorkData->cdwork_has_wing_wall = 'Y';
                            $this->handleSlabCulvertWingWallData(
                                $request,
                                $randomNumber,
                                $randomCode,
                                $userid,
                                'Y'
                            );
                        }
                    }

                    if ($request->culvert_type == 'HPC') {
                        if ($request->head_wall == 'Y') {
                            $cdWorkData->cdwork_has_head_wall = 'Y';
                            $this->handleHumePipeHeadWallData(
                                $request->all(),
                                $randomNumber,
                                $randomCode,
                                $userid,
                                'Y'
                            );
                        }
                    }



                    $this->handleDocument($request, $request->id, $request->road_system_id);
                    // modified by Pulak 30-04-26						

                    session()->forget('culvert_id');

                    //saiful -- 29-04-2026 -- start
                    $cdWorkData->updated_by = Auth::user()->id;
                    $cdWorkData->asset_plan_id = $request->hdn_asset_plan_id ?? null;
                    // $cdWorkData->length_span = $request->length_span_bxc ?? null;
                    $cdWorkData->updated_at = now();
                    if ($request->hdn_redefine_asset_from_project == true)
                        $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                            ->where('id', $request->hdn_asset_plan_id)
                            ->update(['status' => 1]);
                    //saiful -- 29-04-2026 -- end
                    $status = $cdWorkData->save();


                    if ($status) {
                        Log::info('Culvert updated successfully. Culvert ID: ' . $request->id);
                        return redirect()->route('createCDWorks')->with('success', 'Culvert updated successfully.');



                    } else {
                        Log::error('Failed to update culvert. Culvert ID: ' . $request->id);
                        return redirect()->route('createCDWorks')->with('error', 'Failed to update culvert.');



                    }
                } else {
                    Log::error('Culvert not found. Culvert ID: ' . $request->id);
                    return redirect()->route('createCDWorks')->with('error', 'Culvert not found.');



                }
            } else {
                Log::error('Invalid request. Missing ID or CSRF token.');
                return redirect()->route('createCDWorks')->with('error', 'Something went wrong.');



            }
        } catch (Exception $e) {
            Log::error('Error updating culvert: ' . $e->getMessage());
            return redirect()->route('createCDWorks')->with('error', $e->getMessage());



        }
    }
    // modified by Pulak 30-04-26							  

    public function deleteWingWallValue($id)
    {
        $status = DB::table('asset_road_cdwork_wing_wall_draft_details')
            ->where('rd_cdwork_cd', $id)
            ->delete();
        return $status;
    }

    public function deleteHeadWallValue($id)
    {
        $status = DB::table('asset_road_cdwork_head_wall_draft_details')
            ->where('rd_cdwork_cd', $id)
            ->delete();
        return $status;
    }

    public function getWingWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_cdwork_wing_wall_draft_details')
            ->select('*', 'asset_master_wing_wall_types.wing_wall_type_descr')
            ->leftJoin('asset_master_wing_wall_types', 'asset_road_cdwork_wing_wall_draft_details.wing_wall_type_cd', '=', 'asset_master_wing_wall_types.wing_wall_type_cd')
            ->where('rd_cdwork_cd', '=', $request->id)
            ->orderBy('id', 'asc')
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function getFinalizedWingWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_cdwork_wing_wall_details')
            ->select('*', 'asset_master_wing_wall_types.wing_wall_type_descr')
            ->leftJoin('asset_master_wing_wall_types', 'asset_road_cdwork_wing_wall_details.wing_wall_type_cd', '=', 'asset_master_wing_wall_types.wing_wall_type_cd')
            ->where('rd_cdwork_cd', '=', $request->id)
            ->orderBy('id', 'asc')
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function getHeadWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_cdwork_head_wall_draft_details')
            ->select('asset_road_cdwork_head_wall_draft_details.*', 'asset_master_head_walls.head_wall_descr', 'asset_master_head_walls_stream_types.stream_descr')
            ->leftJoin('asset_master_head_walls', 'asset_road_cdwork_head_wall_draft_details.head_wall_type_cd', '=', 'asset_master_head_walls.head_wall_cd')
            ->leftJoin('asset_master_head_walls_stream_types', 'asset_road_cdwork_head_wall_draft_details.head_wall_stream_type_cd', '=', 'asset_master_head_walls_stream_types.stream_type_cd')
            ->where('asset_road_cdwork_head_wall_draft_details.rd_cdwork_cd', '=', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function getFinalizedHeadWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_cdwork_head_wall_details')
            ->select('asset_road_cdwork_head_wall_details.*', 'asset_master_head_walls.head_wall_descr', 'asset_master_head_walls_stream_types.stream_descr')
            ->leftJoin('asset_master_head_walls', 'asset_road_cdwork_head_wall_details.head_wall_type_cd', '=', 'asset_master_head_walls.head_wall_cd')
            ->leftJoin('asset_master_head_walls_stream_types', 'asset_road_cdwork_head_wall_details.head_wall_stream_type_cd', '=', 'asset_master_head_walls_stream_types.stream_type_cd')
            ->where('asset_road_cdwork_head_wall_details.rd_cdwork_cd', '=', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function getLatLng(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $latLng = DB::table('asset_road_cdwork_image_details')
                    ->select('rd_cdwork_cd', 'lat', 'lon')
                    ->where('rd_system_id', $request->id)
                    ->get();

                if ($latLng->count()) {
                    $coordinatesList = [];
                    foreach ($latLng as $point) {
                        $coordinatesList[] = [
                            'id' => $point->rd_cdwork_cd,
                            'coords' => [
                                'lat' => $point->lat,
                                'lng' => $point->lon,
                            ],
                        ];
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => $coordinatesList
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Culvert data not available for this road!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server error!'
            ]);
        }
    }

    public function getCulvertDetails(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $culvertDetails = DB::table('asset_road_cdwork_details_draft')
                    ->select('rd_cdwork_cd', 'rd_system_id', 'culvert_no', 'chainage')
                    ->where('rd_cdwork_cd', $request->id)
                    ->get()->first();

                if ($culvertDetails) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $culvertDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Culvert details not found!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server error!'
            ]);
        }
    }
}