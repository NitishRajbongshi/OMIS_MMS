<?php

namespace App\Http\Controllers\Road;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDeckType;
use App\Models\AssetMasterHeadWall;
use App\Models\AssetMasterPileType;
use App\Models\AssetMasterWellType;
use App\Http\Controllers\Controller;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterBridgeType;
use App\Models\AssetMasterBearingType;
use App\Models\AssetMasterAbutmentType;
use App\Models\AssetMasterHandrailType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterExpansionJoint;
use App\Models\AssetMasterFoundationType;
use App\Models\AssetMasterRetainWallType;
use Illuminate\Support\Facades\Validator;
use App\Models\AssetMasterConstructionType;
use App\Models\AssetMasterDocumentCategory;
use App\Models\AssetMasterSuperStructureType;
use App\Models\AssetMasterHeadWallsStreamType;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\Bridge\AssetRoadBridgeAbutmentWallDetail;
use App\Models\Road\Master\AssetMasterWingWallType;
use App\Models\Road\Master\AssetMasterSafetyApronType;
use App\Models\Road\Bridge\AssetRoadBridgeDetailsDraft;
use App\Models\Road\Bridge\AssetRoadBridgeHeadWallDraftDetail;
use App\Models\Road\Bridge\AssetRoadBridgeWingWallDraftDetail;
use App\Models\Road\Bridge\AssetRoadBridgeRetainWallDraftDetail;
use App\Models\Road\Bridge\AssetRoadBridgeAbutmentWallDraftDetail;
use App\Models\Road\Bridge\AssetRoadBridgeDetail;
use App\Models\Road\Bridge\AssetRoadBridgeDocumentDetail;
use App\Models\Road\Bridge\AssetRoadBridgeHeadWallDetail;
use App\Models\Road\Bridge\AssetRoadBridgeImagesDetail;
use App\Models\Road\Bridge\AssetRoadBridgePierDetails;
use App\Models\Road\Bridge\AssetRoadBridgePierDetailsDraft;
use App\Models\Road\Bridge\AssetRoadBridgeRetainWallDetail;
use App\Models\Road\Bridge\AssetRoadBridgeSpanDetail;
use App\Models\Road\Bridge\AssetRoadBridgeSpanDraftDetail;
use App\Models\Road\Bridge\AssetRoadBridgeWingWallDetail;
use App\Models\Road\Master\AssetMasterPierType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RoadBridgeController extends Controller
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
        // saiful # 21-04-2026 # Start
        session(['asset_plan_id' => $asset_plan_id]);
        // saiful # 21-04-2026 # End
        // Pulak # 28-04-2026 # Start
        session()->forget('bridgeId'); // Remove any existing bridgeId from the session
        // Pulak # 28-04-2026 # End
        return redirect()->route('bridge.store');
    }

    public function show(Request $request)
    {
        session(['bridgeId' => $request->id]);
        Log::info('Bridge ID stored in session: ' . session('bridgeId')); // Debugging line to confirm the value is stored
        return redirect()->route('bridge.store');
    }
    public function create(Request $request)
    {
        //Saiful -- 29-04-2026 -- Start
        $assetPlanId = session('asset_plan_id');
        $redefineAssetFromProject = $request->redefineAssetFromProject ?? false;
        //Saiful -- 29-04-2026 -- Start
        $road_system_id = session('system_id');
        $bridge_id = session('bridgeId');
        log::info('Bridge ID in create method: ' . $bridge_id); // Debugging line to check the value of bridgeId in create method																	
        $bridgeTypes = AssetMasterBridgeType::all();
        $constructionTypes = AssetMasterConstructionType::all();
        $foundationTypes = AssetMasterFoundationType::all();
        $abutmentTypes = AssetMasterAbutmentType::all();
        $superStructureType = AssetMasterSuperStructureType::all();
        $handrailTypes = AssetMasterHandrailType::all();
        $deckTypes = AssetMasterDeckType::all();
        $expJoints = AssetMasterExpansionJoint::all();
        $bridgeConditions = AssetMasterRoadCondition::all();
        $bearingTypes = AssetMasterBearingType::all();
        $pileTypes = AssetMasterPileType::all();
        $wellTypes = AssetMasterWellType::all();
        $headWalls = AssetMasterHeadWall::all();
        $retainWalls = AssetMasterRetainWallType::all();
        $wingWallTypes = AssetMasterWingWallType::all();
        $saftyApronTypes = AssetMasterSafetyApronType::all();
        $handRailTypes = AssetMasterHandrailType::all();
        $streamTypes = AssetMasterHeadWallsStreamType::all();
        $pierTypes = AssetMasterPierType::all();
        $cd_bridge_details = DB::table('asset_road_bridge_details_draft')
            ->select(
                'asset_road_bridge_details_draft.*',
                'asset_master_bridge_type.bridge_type_descr',
                'asset_master_construction_types.construction_type_descr',
                // 'asset_master_foundation_types.foundation_descr',
                'asset_master_abutment_types.abutment_type_descr',
                'asset_master_super_structure_types.st_type_descr',
                'asset_master_handrail_types.hand_rail_type_descr',
                'asset_master_deck_types.deck_type_descr',
                // 'asset_master_bearing_types.bearing_type_descr',
                'asset_master_expansion_joints.expn_joint_descr',
                'asset_master_road_condition.rd_condition_descr',
                // 'asset_master_pile_types.pile_type_descr',
                // 'asset_master_well_types.well_type_descr'
                //saiful # 29-04-2026 # Start
                'pp.project_cd as project_cd'
                //saiful # 29-04-2026 # End
            )
            ->leftJoin('asset_master_bridge_type', 'asset_road_bridge_details_draft.bridge_type_cd', '=', 'asset_master_bridge_type.bridge_type_cd')
            ->leftJoin('asset_master_construction_types', 'asset_road_bridge_details_draft.construction_type_cd', '=', 'asset_master_construction_types.construction_type_cd')
            // ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_details_draft.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
            ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
            ->leftJoin('asset_master_super_structure_types', 'asset_road_bridge_details_draft.super_structure_type_cd', '=', 'asset_master_super_structure_types.st_type_cd')
            ->leftJoin('asset_master_handrail_types', 'asset_road_bridge_details_draft.handrail_type_cd', '=', 'asset_master_handrail_types.hand_rail_type_cd')
            ->leftJoin('asset_master_deck_types', 'asset_road_bridge_details_draft.deck_type_cd', '=', 'asset_master_deck_types.deck_type_cd')
            // ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_details_draft.bearings', '=', 'asset_master_bearing_types.bearing_type_cd')
            ->leftJoin('asset_master_expansion_joints', 'asset_road_bridge_details_draft.expansion_join_cd', '=', 'asset_master_expansion_joints.expn_joint_cd')
            ->leftJoin('asset_master_road_condition', 'asset_road_bridge_details_draft.bridge_condition', '=', 'asset_master_road_condition.rd_condition_cd')
            // ->leftJoin('asset_master_pile_types', 'asset_road_bridge_details_draft.pile_type', '=', 'asset_master_pile_types.pile_type_cd')
            // ->leftJoin('asset_master_well_types', 'asset_road_bridge_details_draft.well_type', '=', 'asset_master_well_types.well_type_cd')
            //saiful # 29-04-2026 # Start
            ->leftJoin('prt_project_asset_plan as pp', 'asset_road_bridge_details_draft.asset_plan_id', '=', 'pp.id')
            //saiful # 29-04-2026 # Start
            ->where('asset_road_bridge_details_draft.rd_system_id', '=', $road_system_id)
            ->where('sent_for_finalize', '=', 'N')
            ->where('created_at_office_cd', '=', auth()->user()->office)
            ->orderBy('updated_at', 'desc')
            ->get();

        $roadChainage = DB::table('asset_road_chainage_mappings')
            ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
            ->where('rd_system_id', '=', $road_system_id)
            ->get()->first();

        //    dd($cd_bridge_details);										 
        return view('road.cd_bridge.index', compact(
            'roadChainage',
            'bridgeTypes',
            'constructionTypes',
            'foundationTypes',
            'abutmentTypes',
            'superStructureType',
            'handrailTypes',
            'deckTypes',
            'expJoints',
            'bearingTypes',
            'bridgeConditions',
            'road_system_id',
            'cd_bridge_details',
            'pileTypes',
            'wellTypes',
            'headWalls',
            'retainWalls',
            'wingWallTypes',
            'saftyApronTypes',
            'handRailTypes',
            'streamTypes',
            'pierTypes',
            'bridge_id',
            //saiful # 29-04-2026 # Start
            'assetPlanId',
            'redefineAssetFromProject'
            //saiful # 29-04-2026 # End
        ));
    }
    // new code start by Pulak-- 27-04-2026
    public function edit($id)
    {
        $road_system_id = session('system_id');
        $cd_bridge_details = DB::table('asset_road_bridge_details_draft')
            ->select(
                'asset_road_bridge_details_draft.*',
                'asset_master_bridge_type.bridge_type_descr',
                'asset_master_construction_types.construction_type_descr',
                // 'asset_master_foundation_types.foundation_descr',
                'asset_master_abutment_types.abutment_type_descr',
                'asset_master_super_structure_types.st_type_descr',
                'asset_master_handrail_types.hand_rail_type_descr',
                'asset_master_deck_types.deck_type_descr',
                // 'asset_master_bearing_types.bearing_type_descr',
                'asset_master_expansion_joints.expn_joint_descr',
                'asset_master_road_condition.rd_condition_descr',
                // 'asset_master_pile_types.pile_type_descr',
                // 'asset_master_well_types.well_type_descr'
            )
            ->leftJoin('asset_master_bridge_type', 'asset_road_bridge_details_draft.bridge_type_cd', '=', 'asset_master_bridge_type.bridge_type_cd')
            ->leftJoin('asset_master_construction_types', 'asset_road_bridge_details_draft.construction_type_cd', '=', 'asset_master_construction_types.construction_type_cd')
            // ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_details_draft.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
            ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
            ->leftJoin('asset_master_super_structure_types', 'asset_road_bridge_details_draft.super_structure_type_cd', '=', 'asset_master_super_structure_types.st_type_cd')
            ->leftJoin('asset_master_handrail_types', 'asset_road_bridge_details_draft.handrail_type_cd', '=', 'asset_master_handrail_types.hand_rail_type_cd')
            ->leftJoin('asset_master_deck_types', 'asset_road_bridge_details_draft.deck_type_cd', '=', 'asset_master_deck_types.deck_type_cd')
            // ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_details_draft.bearings', '=', 'asset_master_bearing_types.bearing_type_cd')
            ->leftJoin('asset_master_expansion_joints', 'asset_road_bridge_details_draft.expansion_join_cd', '=', 'asset_master_expansion_joints.expn_joint_cd')
            ->leftJoin('asset_master_road_condition', 'asset_road_bridge_details_draft.bridge_condition', '=', 'asset_master_road_condition.rd_condition_cd')
            // ->leftJoin('asset_master_pile_types', 'asset_road_bridge_details_draft.pile_type', '=', 'asset_master_pile_types.pile_type_cd')
            // ->leftJoin('asset_master_well_types', 'asset_road_bridge_details_draft.well_type', '=', 'asset_master_well_types.well_type_cd')
            ->where('asset_road_bridge_details_draft.rd_system_id', '=', $road_system_id)
            ->where('sent_for_finalize', '=', 'N')
            // ->where('created_at_office_cd', '=', auth()->user()->office)
            ->orderBy('updated_at', 'desc')
            ->get();
        //modified by Pulak-- 27-04-2026
        foreach ($cd_bridge_details as $bridge) {
            $bridge->spans = DB::table('asset_road_bridge_span_draft_details')
                ->where('rd_bridge_cd', $bridge->rd_bridge_cd)
                ->orderBy('span_sr_no')
                ->pluck('span_length');
            $bridge->span_dimension = count($bridge->spans) > 1 ? 'N' : 'Y';
            $bridge->piers = DB::table('asset_road_bridge_pier_details_draft')
                ->where('rd_bridge_cd', $bridge->rd_bridge_cd)
                ->orderBy('pier_sr_no')
                ->get();
            $bridge->abutments = DB::table('asset_road_bridge_abutment_wall_draft_details')
                ->where('rd_bridge_cd', $bridge->rd_bridge_cd)
                ->orderBy('abutment_wall_sr_no')
                ->get();
            $bridge->wing_walls = DB::table('asset_road_bridge_wing_wall_draft_details')
                ->where('rd_bridge_cd', $bridge->rd_bridge_cd)
                ->orderBy('wing_wall_sr_no')
                ->get();
            $bridge->is_same_wing_wall = count($bridge->wing_walls) > 1 ? 'N' : 'Y';
            $bridge->head_walls = DB::table('asset_road_bridge_head_wall_draft_details')
                ->where('rd_bridge_cd', $bridge->rd_bridge_cd)
                ->orderBy('head_wall_sr_no')
                ->get();
            $bridge->retain_walls = DB::table('asset_road_bridge_retain_wall_draft_details')
                ->where('rd_bridge_cd', $bridge->rd_bridge_cd)
                ->orderBy('retain_wall_sr_no')
                ->get();
            $bridge->is_same_retain_wall = count($bridge->retain_walls) > 1 ? 'N' : 'Y';
        }
        $bridge = $cd_bridge_details->where('rd_bridge_cd', $id)->first();

        Log::info('Editing bridge with ID: ' . $id . ', Found bridge: ' . ($bridge ? 'Yes' : 'No')); // Debugging line to check if the bridge is found
        Log::info("Road Id: " . $road_system_id . ", Bridge Id: " . $id); // Debugging line to check the road and bridge IDs

        if (!$bridge) {
            return response()->json(['error' => 'Bridge not found'], 404);
        }
        return response()->json($bridge);
    }
    // new code end by Pulak-- 27-04-2026										   

    //new code start by Pulak-- 27-04-2026
    public function storeWingWall($bridgeID, $userid, $request, $makerCheckerStatus)
    {
        $model = ($makerCheckerStatus === 'Y')
            ? AssetRoadBridgeWingWallDraftDetail::class
            : AssetRoadBridgeWingWallDetail::class;

        // =========================
        // SAME DIMENSION (Y)
        // =========================
        if ($request->is_same_wing_wall == 'Y') {

            $existing = $model::where('rd_bridge_cd', $bridgeID)
                ->where('wing_wall_sr_no', 1)
                ->first();

            $data = [
                'wing_wall_sr_no' => 1,
                'rd_bridge_cd' => $bridgeID,
                'wing_wall_type_cd' => $request->wing_wall_type_cd,
                'length' => $request->length,
                'top_width' => $request->top_width,
                'bottom_width' => $request->bottom_width,
                'height1' => $request->height1,
                'height2' => $request->height2,
                'slope' => $request->slope,
                'angle' => $request->angle,
                'radius' => $request->radius,
                'created_by' => $userid,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $model::create($data);
            }

            $model::where('rd_bridge_cd', $bridgeID)
                ->where('wing_wall_sr_no', '>', 1)
                ->delete();
        }

        // =========================
        // MULTIPLE (N)
        // =========================
        if ($request->is_same_wing_wall == 'N') {

            for ($i = 1; $i <= 4; $i++) {

                // skip empty rows (important)
                if (!$request->input('length_' . $i)) {
                    continue;
                }

                $existing = $model::where('rd_bridge_cd', $bridgeID)
                    ->where('wing_wall_sr_no', $i)
                    ->first();

                $data = [
                    'wing_wall_sr_no' => $i,
                    'rd_bridge_cd' => $bridgeID,
                    'wing_wall_type_cd' => $request->input('wing_wall_type_' . $i),
                    'length' => $request->input('length_' . $i),
                    'top_width' => $request->input('top_width_' . $i),
                    'bottom_width' => $request->input('bottom_width_' . $i),
                    'height1' => $request->input('height1_' . $i),
                    'height2' => $request->input('height2_' . $i),
                    'slope' => $request->input('slope_' . $i),
                    'angle' => $request->input('angle_' . $i),
                    'radius' => $request->input('radius_' . $i),
                    'created_by' => $userid,
                ];

                if ($existing) {
                    $existing->update($data);
                } else {
                    $model::create($data);
                }
            }
        }
    }

    public function storeHeadWall($bridgeID, $userid, $request, $makerCheckerStatus)
    {
        $model = ($makerCheckerStatus === 'Y')
            ? AssetRoadBridgeHeadWallDraftDetail::class
            : AssetRoadBridgeHeadWallDetail::class;

        for ($i = 1; $i <= 2; $i++) {

            // skip empty rows (important)
            if (!$request->input('head_wall_length_' . $i)) {
                continue;
            }

            // 🔍 check existing row
            $existing = $model::where('rd_bridge_cd', $bridgeID)
                ->where('head_wall_sr_no', $i)
                ->first();

            $data = [
                'head_wall_sr_no' => $i,
                'rd_bridge_cd' => $bridgeID,
                'head_wall_type_cd' => $request->input('head_wall_type_' . $i),
                'head_wall_length' => $request->input('head_wall_length_' . $i),
                'head_wall_width' => $request->input('head_wall_width_' . $i),
                'head_wall_heigth' => $request->input('head_wall_height_' . $i),
                'head_wall_stream_type_cd' => $request->input('head_wall_stream_type_' . $i),
                'created_by' => $userid,

            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $model::create($data);
            }
        }
    }

    public function storeRetainWall($bridgeID, $userid, $request, $makerCheckerStatus)
    {
        $model = ($makerCheckerStatus === 'Y')
            ? AssetRoadBridgeRetainWallDraftDetail::class
            : AssetRoadBridgeRetainWallDetail::class;

        // =========================
        // SAME DIMENSION (Y)
        // =========================
        if ($request->is_same_retain_wall == 'Y') {

            $existing = $model::where('rd_bridge_cd', $bridgeID)
                ->where('retain_wall_sr_no', 1)
                ->first();

            $data = [
                'retain_wall_sr_no' => 1,
                'rd_bridge_cd' => $bridgeID,
                'retain_wall_type_cd' => $request->retain_wall_type_cd,
                'retain_wall_length' => $request->retain_wall_length,
                'retain_wall_width' => $request->retain_wall_width,
                'retain_wall_heigth' => $request->retain_wall_heigth,
                'created_by' => $userid,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $model::create($data);
            }

            $model::where('rd_bridge_cd', $bridgeID)
                ->where('retain_wall_sr_no', '>', 1)
                ->delete();
        }

        // =========================
        // MULTIPLE (N)
        // =========================
        if ($request->is_same_retain_wall == 'N') {

            for ($i = 1; $i <= 4; $i++) {

                // skip empty rows (IMPORTANT)
                if (!$request->input('retain_wall_length_' . $i)) {
                    continue;
                }

                $existing = $model::where('rd_bridge_cd', $bridgeID)
                    ->where('retain_wall_sr_no', $i)
                    ->first();

                $data = [
                    'retain_wall_sr_no' => $i,
                    'rd_bridge_cd' => $bridgeID,
                    'retain_wall_type_cd' => $request->input('retain_wall_type_cd_' . $i),
                    'retain_wall_length' => $request->input('retain_wall_length_' . $i),
                    'retain_wall_width' => $request->input('retain_wall_width_' . $i),
                    'retain_wall_heigth' => $request->input('retain_wall_heigth_' . $i),
                    'created_by' => $userid,
                ];

                if ($existing) {
                    $existing->update($data);
                } else {
                    $model::create($data);
                }
            }
        }
    }

    public function storeAbutmentWall($bridgeID, $userid, $request, $makerCheckerStatus)
    {
        $model = ($makerCheckerStatus === 'Y')
            ? AssetRoadBridgeAbutmentWallDraftDetail::class
            : AssetRoadBridgeAbutmentWallDetail::class;

        // 🔍 check existing
        $existing = $model::where('rd_bridge_cd', $bridgeID)
            ->where('abutment_wall_sr_no', 1)
            ->first();

        $data = [
            'abutment_wall_sr_no' => 1,
            'rd_bridge_cd' => $bridgeID,
            'abutment_wall_type_cd' => $request->abutment_wall_type_cd,
            'abutment_wall_length' => $request->abutment_wall_length,
            'abutment_wall_width' => $request->abutment_wall_width,
            'abutment_wall_heigth' => $request->abutment_wall_heigth,
            'created_by' => $userid,
            'foundation_type_cd' => $request->foundation_type,
            'bearing_type_cd' => $request->abutment_bearings,
            'pile_diameter' => null,
            'pile_length' => null,
            'pile_type_cd' => null,
            'well_type_cd' => null,
            'well_size' => null,
            'open_foundation_size' => null,
            'open_foundation_depth' => null
        ];

        // =========================
        // FOUNDATION LOGIC
        // =========================

        // PILE
        if ($request->foundation_type == '0') {
            $data['pile_diameter'] = $request->pile_diameter;
            $data['pile_length'] = $request->pile_length;
            $data['pile_type_cd'] = $request->pile_type;
        }

        // WELL
        if ($request->foundation_type == '1') {
            $data['well_type_cd'] = $request->well_type;
            $data['well_size'] = $request->well_size;
        }

        // OPEN
        if ($request->foundation_type == '2') {
            $data['open_foundation_size'] = $request->open_fundation_size;
            $data['open_foundation_depth'] = $request->open_fundation_depth;
        }

        // =========================
        // UPDATE OR CREATE
        // =========================

        if ($existing) {
            $existing->update($data);
        } else {
            $model::create($data);
        }
    }
    //new code end by Pulak-- 27-04-2026									

    //modifed by Pulak-- 27-04-2026

    public function storePierDetails($bridgeID, $userid, $request, $makerCheckerStatus)
    {
        //modified by Pulak-- 27-04-2026
        $model = ($makerCheckerStatus === 'Y')
            ? AssetRoadBridgePierDetailsDraft::class
            : AssetRoadBridgePierDetails::class;

        // 🔍 check existing pier (only sr_no = 1 in your UI)
        $existing = $model::where('rd_bridge_cd', $bridgeID)
            ->where('pier_sr_no', 1)
            ->first();

        $pierData = [
            'pier_sr_no' => 1,
            'rd_bridge_cd' => $bridgeID,
            'pier_type_cd' => $request->pier_type_cd,
            'foundation_type_cd' => $request->pier_foundation_type,
            'pier_length' => $request->pier_length,
            'pier_width' => $request->pier_width,
            'pier_heigth' => $request->pier_height,
            'created_by' => $userid,
            'bearing_type_cd' => $request->pier_bearings,
            'pile_diameter' => null,
            'pile_length' => null,
            'pile_type_cd' => null,
            'well_type_cd' => null,
            'well_size' => null,
            'open_foundation_size' => null,
            'open_foundation_depth' => null
        ];

        // =========================
        // FOUNDATION LOGIC
        // =========================

        // PILE
        if ($request->pier_foundation_type == '0') {
            $pierData['pile_diameter'] = $request->pier_pile_diameter;
            $pierData['pile_length'] = $request->pier_pile_length;
            $pierData['pile_type_cd'] = $request->pier_pile_type;
        }

        // WELL
        if ($request->pier_foundation_type == '1') {
            $pierData['well_type_cd'] = $request->pier_well_type;
            $pierData['well_size'] = $request->pier_well_size;
        }

        // OPEN
        if ($request->pier_foundation_type == '2') {
            $pierData['open_foundation_size'] = $request->pier_open_fundation_size;
            $pierData['open_foundation_depth'] = $request->pier_open_fundation_depth;
        }

        // =========================
        // UPDATE OR CREATE
        // =========================

        if ($existing) {
            $existing->update($pierData);
        } else {
            $model::create($pierData);
        }
    }
    //modifed by Pulak-- 27-04-2026

    public function storeSpanDetails($bridgeID, $userid, $request, $makerCheckerStatus)
    {

        //new code start by Pulak-- 27-04-2026
        $model = ($makerCheckerStatus === 'Y')
            ? AssetRoadBridgeSpanDraftDetail::class
            : AssetRoadBridgeSpanDetail::class;

        // =========================
        // SINGLE SPAN
        // =========================
        if ($request->span_dimension == 'Y') {

            $existing = $model::where('rd_bridge_cd', $bridgeID)
                ->where('span_sr_no', 1)
                ->first();

            $data = [
                'rd_bridge_cd' => $bridgeID,
                'span_sr_no' => 1,
                'span_length' => $request->span_length,
                'created_by' => $userid
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $model::create($data);
            }
            $model::where('rd_bridge_cd', $bridgeID)
                ->where('span_sr_no', '>', 1)
                ->delete();
        }

        // =========================
        // MULTIPLE SPANS
        // =========================
        if ($request->span_dimension == 'N') {

            for ($i = 1; $i <= $request->span_no; $i++) {

                $existing = $model::where('rd_bridge_cd', $bridgeID)
                    ->where('span_sr_no', $i)
                    ->first();

                $data = [
                    'rd_bridge_cd' => $bridgeID,
                    'span_sr_no' => $i,
                    'span_length' => $request->input('span_length_' . $i),
                    'created_by' => $userid
                ];

                if ($existing) {
                    $existing->update($data);
                } else {
                    $model::create($data);
                }
            }
        }
    }
    //end of new code by Pulak--27-04-2026																				   
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $userid = Auth::user()->id;
            $randomNumber = mt_rand(100, 999);
            $currentTime = time();
            $randomCode = $userid . $currentTime . $randomNumber;
            DB::beginTransaction();
            $location = $request->lat . ',' . $request->lng;
            $bridgeData = [
                'rd_bridge_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
                'bridge_name' => $request->bridge_name,
                'chainage' => $request->chainage,
                'bridge_type_cd' => $request->bridge_type,
                'bridge_width' => $request->bridge_width,
                'river_name' => $request->river_name,
                'construction_type_cd' => $request->construction_type,
                'no_of_span' => $request->span_no,
                'kerb_width' => $request->kerb_width,
                'kerb_height' => $request->kerb_height,
                'load_capacity' => $request->load_capacity,
                'no_of_piers' => $request->no_of_piers,
                'super_structure_type_cd' => $request->superstructure_type,
                'handrail_type_cd' => $request->handrail_type,
                'deck_type_cd' => $request->deck_type,
                'expansion_join_cd' => $request->expansion_joints,
                'deck_level' => $request->deck_level,
                'carriage_width' => $request->carriage,
                'guard_stone' => $request->guard_stone,
                'discharge' => $request->discharge,
                'source_depth' => $request->source_depth,
                'lowest_water_level' => $request->lowest_water_level,
                'highest_flood_level' => $request->highest_flood_level,
                'rfl' => $request->rfl,
                'year_of_rehabilitation' => $request->year_of_rehabilitation,
                'year_of_construction' => $request->year_of_contruction,
                'date_of_last_inspection' => $request->last_inspection,
                'bridge_condition' => $request->condition,
                'bridge_location' => $location,
                'next_schedule_inspection_date' => $request->next_schedule_inspection,
                'footh_path' => $request->footpath,
                'bridge_remark' => $request->remarks,
                'updated_by' => auth()->user()->id,
                'created_by' => auth()->user()->id,
                'created_at_office_cd' => auth()->user()->office,
                'has_abutment_wall' => $request->bridge_abutment,
                'has_wing_wall' => $request->wing_wall,
                'has_head_wall' => $request->bridge_head_wall,
                'has_retain_wall' => $request->bridge_retain_wall,
                'has_safety_apron' => $request->has_safety_apron,
                'safety_apron_type' => $request->has_safety_apron ? $request->safety_apron_type : null,
                'apron_width' => $request->has_safety_apron ? $request->apron_width : null,
                // Saiful # 21-04-2026 # Start
                'asset_plan_id' => $request->hdn_asset_plan_id ?? null
                // Saiful # 21-04-2026 # End
            ];
            //new code start by Pulak 
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('1');

            if ($makerCheckerStatus === 'Y') {
                $bridge = AssetRoadBridgeDetailsDraft::create($bridgeData);
            } else {
                $extraData = [
                    'bridge_lane' => $request->bridge_lane,
                    'span_length' => $request->span_length,
                    'kerb_distance' => $request->kerb_height,
                    'abutment_type_cd' => $request->abutment_type,
                    'lowest_water_level' => $request->lowest_water_level,
                    'minimum_water_level' => $request->lowest_water_level,
                    'safety_apron_hand_rail_type' => $request->safety_apron_hand_rail_type,
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),
                ];
                $bridge = AssetRoadBridgeDetail::create(array_merge($bridgeData, $extraData));
            }

            if ($bridge) {
                //saiful 21-04-2026 -- Start
                if ($request->hdn_asset_plan_id != null)
                    $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                        ->where('id', $request->hdn_asset_plan_id)
                        ->where('no_of_new_asset', '>', 0)
                        ->decrement('no_of_new_asset', 1, [
                            'updated_at' => now(),
                            'remarks' => 'Bridge Created on: ' . now()
                        ]);
                session()->forget('asset_plan_id');
                //saiful 21-04-2026 -- End
                if ($request->wing_wall == 'Y') {
                    $this->storeWingWall($randomCode, $userid, $request, $makerCheckerStatus);
                }
                if ($request->bridge_head_wall == 'Y') {
                    $this->storeHeadWall($randomCode, $userid, $request, $makerCheckerStatus);
                }
                if ($request->bridge_retain_wall == 'Y') {
                    $this->storeRetainWall($randomCode, $userid, $request, $makerCheckerStatus);
                }
                if ($request->bridge_abutment == 'Y') {
                    $this->storeAbutmentWall($randomCode, $userid, $request, $makerCheckerStatus);
                }
                if ($request->span_no != null) {
                    $this->storeSpanDetails($randomCode, $userid, $request, $makerCheckerStatus);
                }
                if ($request->no_of_piers > 0) {
                    $this->storePierDetails($randomCode, $userid, $request, $makerCheckerStatus);
                }

                $this->handleDocument($request, $randomCode);

                DB::commit();
                return redirect()->back()
                    ->with('success', 'Bridge details were saved successfully with code : ' . $randomCode);
            } else {
                DB::rollBack();
                //saiful -- 22-04-2026 -- start
                session()->forget('asset_plan_id');
                //saiful -- 22-04-2026 -- End
                return redirect()->back()
                    ->with('failed', 'Failed to save bridge data due to some errors!');
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
    public function handleDocument($request, $randomCode)
    {
        $configPath = config('customconfigpath.BRIDGES_DOCS_PATH');
        $configImagePath = config('customconfigpath.BRIDGE_IMAGES_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $randomNumber = mt_rand(100, 999);
                // get image extension
                $extension = $image->getClientOriginalExtension();
                $uniqueFileName = 'bridge_' . $randomCode . '_' . $randomNumber . '.' . $extension;
                $folderPath = $configImagePath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $image->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                AssetRoadBridgeImagesDetail::create([
                    'rd_bridge_cd' => $randomCode,
                    'rd_system_id' => $request->road_system_id,
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

            AssetRoadBridgeDocumentDetail::create([
                'rd_bridge_cd' => $randomCode,
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

            AssetRoadBridgeDocumentDetail::create([
                'rd_bridge_cd' => $randomCode,
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

            AssetRoadBridgeDocumentDetail::create([
                'rd_bridge_cd' => $randomCode,
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

            AssetRoadBridgeDocumentDetail::create([
                'rd_bridge_cd' => $randomCode,
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
    //modifed by Pulak-- 27-04-2026							   
    public function update(Request $request, $id)
    {
        //   dd($request->all());						  
        DB::beginTransaction();
        try {

            session()->forget('bridgeId'); // Remove any existing bridgeId from the session

            $bridgeData = AssetRoadBridgeDetailsDraft::find($id);

            if (!$bridgeData) {
                return back()->with('failed', 'Bridge not found');
            }

            // UPDATE MAIN BRIDGE (keep your existing fields)													 
            // ── Required Fields Only ─────────────────────────────

            $bridgeData->rd_bridge_cd = $request->bridge_id;
            $bridgeData->rd_system_id = $request->rd_system_id;
            $bridgeData->bridge_type_cd = $request->bridge_type;
            $bridgeData->bridge_name = $request->bridge_name;


            $bridgeData->chainage = $request->chainage;
            $bridgeData->bridge_lane = $request->bridge_lane;
            $bridgeData->river_name = $request->river_name;
            $bridgeData->cd_bridge_length = $request->cd_bridge_length;
            $bridgeData->construction_type_cd = $request->construction_type;
            $bridgeData->year_of_construction = $request->year_of_contruction;
            $bridgeData->no_of_span = $request->span_no;

            $bridgeData->kerb_height = $request->kerb_height;

            $bridgeData->year_of_rehabilitation = $request->year_of_rehabilitation;
            $bridgeData->no_of_piers = $request->no_of_piers;

            $bridgeData->abutment_type_cd = $request->abutment_type_cd;
            $bridgeData->handrail_type_cd = $request->handrail_type;
            $bridgeData->deck_type_cd = $request->deck_type;
            $bridgeData->carriage_width = $request->carriage;
            $bridgeData->guard_stone = $request->guard_stone;
            $bridgeData->load_capacity = $request->load_capacity;

            $bridgeData->lowest_water_level = $request->lowest_water_level;
            $bridgeData->highest_flood_level = $request->highest_flood_level;
            $bridgeData->rfl = $request->rfl;
            $bridgeData->source_depth = $request->source_depth;
            $bridgeData->discharge = $request->discharge;
            $bridgeData->deck_level = $request->deck_level;
            $bridgeData->super_structure_type_cd = $request->superstructure_type;
            $bridgeData->footh_path = $request->footpath;

            $bridgeData->expansion_join_cd = $request->expansion_joints;
            $bridgeData->bridge_condition = $request->condition;
            $bridgeData->next_schedule_inspection_date = $request->next_schedule_inspection;

            $bridgeData->bridge_location = $request->latitude && $request->longitude
                ? $request->latitude . ',' . $request->longitude
                : null;

            $bridgeData->date_of_last_inspection = $request->last_inspection;
            $bridgeData->kerb_width = $request->kerb_width;

            $bridgeData->bridge_remark = $request->remarks;
            $bridgeData->bridge_width = $request->bridge_width;
            $bridgeData->safety_apron_type = $request->safety_apron_type ?? 'N';
            $bridgeData->apron_width = $request->apron_width ?? 'N';

            // ── Boolean Flags ─────────────────────────────
            $bridgeData->has_abutment_wall = $request->bridge_abutment ?? 'N';
            $bridgeData->has_wing_wall = $request->wing_wall ?? 'N';
            $bridgeData->has_head_wall = $request->bridge_head_wall ?? 'N';
            $bridgeData->has_retain_wall = $request->bridge_retain_wall ?? 'N';
            $bridgeData->has_safety_apron = $request->has_safety_apron ?? 'N';

            // ── Meta ─────────────────────────────
            $bridgeData->updated_by = Auth::user()->id;

            //saiful -- 29-04-2026 -- start
            $bridgeData->asset_plan_id = $request->hdn_asset_plan_id ?? null;
            $bridgeData->span_length = $request->span_length ?? null;
            $bridgeData->updated_at = now();
            if ($request->hdn_redefine_asset_from_project == true)
                $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                    ->where('id', $request->hdn_asset_plan_id)
                    ->update(['status' => 1]);
            //saiful -- 29-04-2026 -- end
            // ── Save ─────────────────────────────
            $status = $bridgeData->save();

            // IMPORTANT
            $bridgeID = $bridgeData->rd_bridge_cd;
            $userid = auth()->user()->id;
            $makerCheckerStatus = 'Y'; // change if needed

            // span
            if ($request->span_no != null) {
                $this->storeSpanDetails($bridgeID, $userid, $request, $makerCheckerStatus);
            }
            // pier
            if ($request->no_of_piers > 0) {
                $this->storePierDetails($bridgeID, $userid, $request, $makerCheckerStatus);
            } else {
                AssetRoadBridgePierDetailsDraft::where('rd_bridge_cd', $bridgeID)->delete();
            }
            // abutment
            if ($request->bridge_abutment == 'Y') {
                $this->storeAbutmentWall($bridgeID, $userid, $request, $makerCheckerStatus);
            } else {
                AssetRoadBridgeAbutmentWallDraftDetail::where('rd_bridge_cd', $bridgeID)->delete();
            }

            // wing wall
            if ($request->wing_wall == 'Y') {
                $this->storeWingWall($bridgeID, $userid, $request, $makerCheckerStatus);
            } else {
                AssetRoadBridgeWingWallDraftDetail::where('rd_bridge_cd', $bridgeID)->delete();
            }

            // head wall
            if ($request->bridge_head_wall == 'Y') {
                $this->storeHeadWall($bridgeID, $userid, $request, $makerCheckerStatus);
            } else {
                AssetRoadBridgeHeadWallDraftDetail::where('rd_bridge_cd', $bridgeID)->delete();
            }


            // retain wall
            if ($request->bridge_retain_wall == 'Y') {
                $this->storeRetainWall($bridgeID, $userid, $request, $makerCheckerStatus);
            } else {
                AssetRoadBridgeRetainWallDraftDetail::where('rd_bridge_cd', $bridgeID)->delete();
            }

            // reuse SAME function

            $this->handleDocument($request, $bridgeID);

            DB::commit();

            session()->forget('bridgeId');

            if ($status) {

                return redirect()->back()->with('success', 'Bridge updated successfully.');
            }

            return redirect()->back()->with('failed', 'Failed to update bridge.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('failed', 'An error occurred: ' . $e->getMessage())
                ->withInput();

        }
    }
    //modification end by Pulak-- 27-04-2026
    public function bridgeWingWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_bridge_wing_wall_draft_details')
            ->select('*', 'asset_master_wing_wall_types.wing_wall_type_descr')
            ->leftJoin('asset_master_wing_wall_types', 'asset_road_bridge_wing_wall_draft_details.wing_wall_type_cd', '=', 'asset_master_wing_wall_types.wing_wall_type_cd')
            ->where('rd_bridge_cd', '=', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function finalizedBridgeWingWallDetails(Request $request)
    {
        try {
            $wingWallValues = DB::table('asset_road_bridge_wing_wall_details')
                ->select('*', 'asset_master_wing_wall_types.wing_wall_type_descr')
                ->leftJoin('asset_master_wing_wall_types', 'asset_road_bridge_wing_wall_details.wing_wall_type_cd', '=', 'asset_master_wing_wall_types.wing_wall_type_cd')
                ->where('rd_bridge_cd', '=', $request->id)
                ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetched successfully',
                'value' => $wingWallValues
            ]);
        } catch (QueryException $e) {
            Log::error('Database Error in finalized Bridge Wing Wall Details', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred while fetching bridge wing wall details.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected Error in finalized Bridge Wing Wall Details', [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function bridgeHeadWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_bridge_head_wall_draft_details')
            ->select('asset_road_bridge_head_wall_draft_details.*', 'asset_master_head_walls.head_wall_descr', 'asset_master_head_walls_stream_types.stream_descr')
            ->leftJoin('asset_master_head_walls', 'asset_road_bridge_head_wall_draft_details.head_wall_type_cd', '=', 'asset_master_head_walls.head_wall_cd')
            ->leftJoin('asset_master_head_walls_stream_types', 'asset_road_bridge_head_wall_draft_details.head_wall_stream_type_cd', '=', 'asset_master_head_walls_stream_types.stream_type_cd')
            ->where('asset_road_bridge_head_wall_draft_details.rd_bridge_cd', '=', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function finalizedBridgeHeadWallDetails(Request $request)
    {
        try {
            $wingWallValues = DB::table('asset_road_bridge_head_wall_details')
                ->select('asset_road_bridge_head_wall_details.*', 'asset_master_head_walls.head_wall_descr', 'asset_master_head_walls_stream_types.stream_descr')
                ->leftJoin('asset_master_head_walls', 'asset_road_bridge_head_wall_details.head_wall_type_cd', '=', 'asset_master_head_walls.head_wall_cd')
                ->leftJoin('asset_master_head_walls_stream_types', 'asset_road_bridge_head_wall_details.head_wall_stream_type_cd', '=', 'asset_master_head_walls_stream_types.stream_type_cd')
                ->where('asset_road_bridge_head_wall_details.rd_bridge_cd', '=', $request->id)
                ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetched successfully',
                'value' => $wingWallValues
            ]);
        } catch (QueryException $e) {
            Log::error('Database Error in finalized Bridge Head Wall Details', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred while fetching bridge head wall details.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected Error in finalized Bridge Head Wall Details', [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function bridgeAbutmentWallDetails(Request $request)
    {
        $abutmentWallValues = DB::table('asset_road_bridge_abutment_wall_draft_details')
            ->select(
                'asset_road_bridge_abutment_wall_draft_details.*',
                'asset_master_abutment_types.abutment_type_descr',
                'asset_master_bearing_types.bearing_type_descr',
                'asset_master_foundation_types.foundation_descr',
                'asset_master_pile_types.pile_type_descr',
                'asset_master_well_types.well_type_descr'
            )
            ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_abutment_wall_draft_details.abutment_wall_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
            ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_abutment_wall_draft_details.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
            ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_abutment_wall_draft_details.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
            ->leftJoin('asset_master_pile_types', 'asset_road_bridge_abutment_wall_draft_details.pile_type_cd', '=', 'asset_master_pile_types.pile_type_cd')
            ->leftJoin('asset_master_well_types', 'asset_road_bridge_abutment_wall_draft_details.well_type_cd', '=', 'asset_master_well_types.well_type_cd')
            ->where('asset_road_bridge_abutment_wall_draft_details.rd_bridge_cd', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $abutmentWallValues
        ]);
    }

    public function finalizedBridgeAbutmentWallDetails(Request $request)
    {
        try {
            $abutmentWallValues = DB::table('asset_road_bridge_abutment_wall_details')
                ->select(
                    'asset_road_bridge_abutment_wall_details.*',
                    'asset_master_abutment_types.abutment_type_descr',
                    'asset_master_bearing_types.bearing_type_descr',
                    'asset_master_foundation_types.foundation_descr',
                    'asset_master_pile_types.pile_type_descr',
                    'asset_master_well_types.well_type_descr'
                )
                ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_abutment_wall_details.abutment_wall_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_abutment_wall_details.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
                ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_abutment_wall_details.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
                ->leftJoin('asset_master_pile_types', 'asset_road_bridge_abutment_wall_details.pile_type_cd', '=', 'asset_master_pile_types.pile_type_cd')
                ->leftJoin('asset_master_well_types', 'asset_road_bridge_abutment_wall_details.well_type_cd', '=', 'asset_master_well_types.well_type_cd')
                ->where('asset_road_bridge_abutment_wall_details.rd_bridge_cd', $request->id)
                ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetched successfully',
                'value' => $abutmentWallValues
            ]);
        } catch (QueryException $e) {
            Log::error('Database Error in finalized Bridge Abutment Wall Details', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred while fetching bridge abutment wall details.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected Error in finalized Bridge Abutment Wall Details', [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function bridgeRetainWallDetails(Request $request)
    {
        $wingWallValues = DB::table('asset_road_bridge_retain_wall_draft_details')
            ->select('asset_road_bridge_retain_wall_draft_details.*', 'asset_master_retain_wall_types.reatain_wall_descr')
            ->leftJoin('asset_master_retain_wall_types', 'asset_road_bridge_retain_wall_draft_details.retain_wall_type_cd', '=', 'asset_master_retain_wall_types.retain_type_cd')
            ->where('asset_road_bridge_retain_wall_draft_details.rd_bridge_cd', $request->id)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $wingWallValues
        ]);
    }

    public function finalizedBridgeRetainWallDetails(Request $request)
    {
        try {
            $wingWallValues = DB::table('asset_road_bridge_retain_wall_details')
                ->select('asset_road_bridge_retain_wall_details.*', 'asset_master_retain_wall_types.reatain_wall_descr')
                ->leftJoin('asset_master_retain_wall_types', 'asset_road_bridge_retain_wall_details.retain_wall_type_cd', '=', 'asset_master_retain_wall_types.retain_type_cd')
                ->where('asset_road_bridge_retain_wall_details.rd_bridge_cd', $request->id)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetched successfully',
                'value' => $wingWallValues
            ]);
        } catch (QueryException $e) {
            Log::error('Database Error in finalized Bridge Retain Wall Details', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred while fetching bridge retain wall details.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected Error in finalized Bridge Retain Wall Details', [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function bridgeSpanDetails(Request $request)
    {
        $spanDetails = DB::table('asset_road_bridge_span_draft_details')
            ->select('span_sr_no', 'rd_bridge_cd', 'span_length', 'created_by')
            ->where('rd_bridge_cd', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $spanDetails
        ]);
    }

    public function finalizedBridgeSpanDetails(Request $request)
    {
        try {
            $spanDetails = DB::table('asset_road_bridge_span_details')
                ->select('span_sr_no', 'rd_bridge_cd', 'span_length', 'created_by')
                ->where('rd_bridge_cd', $request->id)
                ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetched successfully',
                'value' => $spanDetails
            ]);
        } catch (QueryException $e) {
            Log::error('Database Error in finalized Bridge Span Details', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred while fetching bridge span details.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected Error in finalized Bridge Span Details', [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    public function bridgePierDetails(Request $request)
    {
        $abutmentWallValues = DB::table('asset_road_bridge_pier_details_draft')
            ->select(
                'asset_road_bridge_pier_details_draft.*',
                'asset_master_pier_types.pier_type_descr',
                'asset_master_bearing_types.bearing_type_descr',
                'asset_master_foundation_types.foundation_descr',
                'asset_master_pile_types.pile_type_descr',
                'asset_master_well_types.well_type_descr'
            )
            ->leftJoin('asset_master_pier_types', 'asset_road_bridge_pier_details_draft.pier_type_cd', '=', 'asset_master_pier_types.id')
            ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_pier_details_draft.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
            ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_pier_details_draft.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
            ->leftJoin('asset_master_pile_types', 'asset_road_bridge_pier_details_draft.pile_type_cd', '=', 'asset_master_pile_types.pile_type_cd')
            ->leftJoin('asset_master_well_types', 'asset_road_bridge_pier_details_draft.well_type_cd', '=', 'asset_master_well_types.well_type_cd')
            ->where('asset_road_bridge_pier_details_draft.rd_bridge_cd', $request->id)
            ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $abutmentWallValues
        ]);
    }

    public function finalizedBridgePierDetails(Request $request)
    {
        try {
            $abutmentWallValues = DB::table('asset_road_bridge_pier_details')
                ->select(
                    'asset_road_bridge_pier_details.*',
                    'asset_master_pier_types.pier_type_descr',
                    'asset_master_bearing_types.bearing_type_descr',
                    'asset_master_foundation_types.foundation_descr',
                    'asset_master_pile_types.pile_type_descr',
                    'asset_master_well_types.well_type_descr'
                )
                ->leftJoin('asset_master_pier_types', 'asset_road_bridge_pier_details.pier_type_cd', '=', 'asset_master_pier_types.id')
                ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_pier_details.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
                ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_pier_details.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
                ->leftJoin('asset_master_pile_types', 'asset_road_bridge_pier_details.pile_type_cd', '=', 'asset_master_pile_types.pile_type_cd')
                ->leftJoin('asset_master_well_types', 'asset_road_bridge_pier_details.well_type_cd', '=', 'asset_master_well_types.well_type_cd')
                ->where('asset_road_bridge_pier_details.rd_bridge_cd', $request->id)
                ->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetched successfully',
                'value' => $abutmentWallValues
            ]);
        } catch (QueryException $e) {
            Log::error('Database Error in finalized Bridge Pier Details', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred while fetching bridge pier details.'
            ], 500);
        } catch (Exception $e) {
            Log::error('Unexpected Error in finalized Bridge Pier Details', [
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }
}
