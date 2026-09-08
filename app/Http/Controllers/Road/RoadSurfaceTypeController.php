<?php

namespace App\Http\Controllers\Road;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Road\AssetRoadDetail;
use App\Models\AssetMasterSurfaceType;
use App\Models\AssetMasterPavementType;
use App\Models\AssetMasterShoulderType;
use App\Models\AssetMasterBaseLayerType;
use App\Models\AssetMasterRoadCondition;
use Illuminate\Support\Facades\Validator;
use App\Models\AssetMasterSubBaseLayerType;
use App\Models\AssetRoadSurfaceTypeDetail;
use App\Models\AssetRoadSurfaceTypeDetailsDraft;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\Master\AssetMasterDrainageLineDrainageType;
use App\Models\Road\Master\AssetMasterDrainageType;
use App\Models\Road\Master\AssetMasterMaintenanceType;
use App\Models\Road\SurfaceTypes\AssetRoadSurfaceTypeDrainageDetail;

class RoadSurfaceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request)
    {
        $systemId = $request->id;
        $roadDetails = AssetRoadDetail::find($systemId);
        session(['system_id' => $systemId]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
        return redirect()->route('road.add-surface-type');
    }

    public function create(Request $request)
    {
        $roadSystemId = session('system_id');
        $roadChainage = DB::table('asset_road_chainage_mappings')
            ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
            ->where('rd_system_id', '=', $roadSystemId)
            ->get()->first();
        $surface_types = AssetMasterSurfaceType::all();
        $road_conditions = AssetMasterRoadCondition::all();
        $baseLayerTypes = AssetMasterBaseLayerType::all();
        $subBaseLayerTypes = AssetMasterSubBaseLayerType::all();
        $pavementTypes = AssetMasterPavementType::all();
        $shoulderTypes = AssetMasterShoulderType::all();
        $drainageTypes = AssetMasterDrainageType::all();
        $maintenanceTypes = AssetMasterMaintenanceType::all();
        $lineDrainages = AssetMasterDrainageLineDrainageType::all();
        $road_system_id = session('system_id');
        $surfaceTypeDetails = DB::table('asset_road_surface_type_details_draft')
            ->select('asset_road_surface_type_details_draft.*', 'asset_master_surface_type.surface_descr', 'asset_master_road_condition.rd_condition_descr', 'office_details.office_name', 'asset_master_base_layer_types.base_layer_type_descr', 'asset_master_sub_base_layer_types.sub_base_layer_type_descr', 'asset_master_pavement_types.pavement_type_descr', 'asset_master_shoulder_types.shoulder_type_descr', "asset_master_maintenance_types.maintenance_type_descr", 'asset_master_drainage_types.drainage_descr')
            ->leftJoin('asset_master_surface_type', 'asset_road_surface_type_details_draft.surface_type_cd', '=', 'asset_master_surface_type.surface_cd')
            ->leftJoin('asset_master_road_condition', 'asset_road_surface_type_details_draft.surface_condition_cd', '=', 'asset_master_road_condition.rd_condition_cd')
            ->leftJoin('office_details', 'asset_road_surface_type_details_draft.created_at_office_cd', '=', 'office_details.id')
            ->leftJoin('asset_master_base_layer_types', 'asset_road_surface_type_details_draft.base_layer_type', '=', 'asset_master_base_layer_types.base_layer_type_cd')
            ->leftJoin('asset_master_sub_base_layer_types', 'asset_road_surface_type_details_draft.sub_base_layer_type', '=', 'asset_master_sub_base_layer_types.sub_base_layer_type_cd')
            ->leftJoin('asset_master_pavement_types', 'asset_road_surface_type_details_draft.pavement_type', '=', 'asset_master_pavement_types.pavement_type_cd')
            ->leftJoin('asset_master_shoulder_types', 'asset_road_surface_type_details_draft.shoulder_type', '=', 'asset_master_shoulder_types.shoulder_type_cd')
            ->leftJoin('asset_master_maintenance_types', 'asset_road_surface_type_details_draft.maintenance_type', '=', 'asset_master_maintenance_types.maintenance_type_cd')
            ->leftJoin('asset_master_drainage_types', 'asset_road_surface_type_details_draft.drainage', '=', 'asset_master_drainage_types.drainage_cd')
            ->where('rd_system_id', '=', $roadSystemId)
            ->where('sent_for_finalize', '=', 'N')
            ->where('created_at_office_cd', '=', auth()->user()->office)
            ->orderBy('id', 'desc')
            ->get();

        $surfaceTypeChainage = DB::table('asset_road_surface_type_details_draft')
            ->select('rd_system_id', 'start_chainage', 'end_chainage', 'updated_at')
            ->where('rd_system_id', '=', $road_system_id)
            ->orderBy('updated_at', 'desc')
            ->get()->first();

        return view('road.surface_type.index', compact(
            'surface_types',
            'road_conditions',
            'road_system_id',
            'baseLayerTypes',
            'subBaseLayerTypes',
            'pavementTypes',
            'shoulderTypes',
            'surfaceTypeDetails',
            'roadChainage',
            'surfaceTypeChainage',
            'drainageTypes',
            'maintenanceTypes',
            'lineDrainages'
        ));
    }

    public function store(Request $request)
    {
        // dd($request);
        $system_id = $request->road_system_id;

        try {
            $randomCode = rand(10, 99);
            $currentTime = Carbon::now();
            $currentTimeString = $currentTime->format('YmdHis');
            $randomCodeWithTime = $randomCode . $currentTimeString;

            $data = [
                'rd_system_id' => $request->road_system_id,
                'rd_surface_cd' => $randomCodeWithTime,
                'surface_type_cd' => $request->input('surface_type_cd'),
                'surface_condition_cd' => $request->input('surface_condition'),
                'start_chainage' => $request->input('from_chainage'),
                'end_chainage' => $request->input('to_chainage'),
                'created_at_office_cd' => $request->input('created_office'),
                'base_layer_type' => $request->input('base_layer_type'),
                'base_layer_thickness' => $request->input('base_layer_tickness'),
                'sub_base_layer_type' => $request->input('sub_base_layer_type'),
                'sub_base_layer_thickness' => $request->input('sub_base_layer_thickness'),
                'pavement_type' => $request->input('pavment_type'),
                'shoulder_type' => $request->input('shoulder_type'),
                'land_slide' => $request->input('land_slide'),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at_office_cd' => auth()->user()->office,
                'construction_year' => $request->input('construction_year'),
                'surface_width' => $request->input('surface_width'),
                'shoulder_width' => $request->input('shoulder_width'),
                'base_cbr' => $request->input('base_cbr'),
                'base_pi' => $request->input('base_pi'),
                'sub_base_cbr' => $request->input('sub_base_cbr'),
                'sub_base_pi' => $request->input('sub_base_pi'),
                'last_maintenance_date' => $request->input('last_maintenance_date'),
                'maintenance_type' => $request->input('maintenance_type'),
                'drainage' => $request->input('drainage'),
            ];

            // store line drainage info here
            if ($request->input('drainage') == '0') {
                // check for drainage side
                if ($request->line_drainage_side == 'N') {
                    for ($i = 0; $i < count($request->start_chainage); $i++) {
                        $drainageData = [];
                        $drainageData['rd_system_id'] = $request->road_system_id;
                        $drainageData['rd_surface_cd'] = $randomCodeWithTime;
                        $drainageData['start_chainage'] = $request->start_chainage[$i];
                        $drainageData['end_chainage'] = $request->end_chainage[$i];
                        $drainageData['drainage_length'] = $request->end_chainage[$i] - $request->start_chainage[$i];
                        $drainageData['type_of_line_drainage'] = $request->type_of_line_drainage[$i];
                        $drainageData['created_at_office_cd'] = auth()->user()->office;
                        $drainageData['created_by'] = auth()->user()->id;
                        $drainageData['updated_by'] = auth()->user()->id;
                        AssetRoadSurfaceTypeDrainageDetail::create($drainageData);
                    }
                }

                if ($request->line_drainage_side == 'Y') {
                    for ($i = 0; $i < count($request->start_chainage_left); $i++) {
                        $leftDrainageData = [];
                        $leftDrainageData['rd_system_id'] = $request->road_system_id;
                        $leftDrainageData['rd_surface_cd'] = $randomCodeWithTime;
                        $leftDrainageData['start_chainage'] = $request->start_chainage_left[$i];
                        $leftDrainageData['end_chainage'] = $request->end_chainage_left[$i];
                        $leftDrainageData['drainage_length'] = $request->end_chainage_left[$i] - $request->start_chainage_left[$i];
                        $leftDrainageData['type_of_line_drainage'] = $request->type_of_line_drainage_left[$i];
                        $leftDrainageData['drainage_side'] = 'Left';
                        $leftDrainageData['created_at_office_cd'] = auth()->user()->office;
                        $leftDrainageData['created_by'] = auth()->user()->id;
                        $leftDrainageData['updated_by'] = auth()->user()->id;
                        AssetRoadSurfaceTypeDrainageDetail::create($leftDrainageData);
                    }

                    for ($i = 0; $i < count($request->start_chainage_right); $i++) {
                        $rightDrainageData = [];
                        $rightDrainageData['rd_system_id'] = $request->road_system_id;
                        $rightDrainageData['rd_surface_cd'] = $randomCodeWithTime;
                        $rightDrainageData['start_chainage'] = $request->start_chainage_right[$i];
                        $rightDrainageData['end_chainage'] = $request->end_chainage_right[$i];
                        $rightDrainageData['drainage_length'] = $request->end_chainage_right[$i] - $request->start_chainage_right[$i];
                        $rightDrainageData['type_of_line_drainage'] = $request->type_of_line_drainage_right[$i];
                        $rightDrainageData['drainage_side'] = 'Right';
                        $rightDrainageData['created_at_office_cd'] = auth()->user()->office;
                        $rightDrainageData['created_by'] = auth()->user()->id;
                        $rightDrainageData['updated_by'] = auth()->user()->id;
                        AssetRoadSurfaceTypeDrainageDetail::create($rightDrainageData);
                    }
                }
            }
            //new code start by Pulak 
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('3');

            if ($makerCheckerStatus === 'Y') {
                $status = AssetRoadSurfaceTypeDetailsDraft::create($data);
            } else {
                $status = AssetRoadSurfaceTypeDetail::create($data);
            }

            if ($status) {
                return redirect()->back()
                    ->with('success', 'Surface Type value inserted successfully')
                    ->with('rd_system_id', $system_id);
            } else {
                return redirect()->back()
                    ->with('failed', 'Failed to insert surface type value due to some error')
                    ->with('rd_system_id', $system_id);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function update(Request $request)
    {
        try {
            if ((isset($request->id)) and (isset($request->_token))) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'validation Failed'
                    ]);
                }

                $surfaceTypeData = AssetRoadSurfaceTypeDetailsDraft::where('rd_surface_cd', '=', $request->id)->get()->first();
                if ($surfaceTypeData) {
                    $surfaceTypeData->surface_type_cd = $request->surface_type_cd;
                    $surfaceTypeData->surface_condition_cd = $request->road_condition;
                    $surfaceTypeData->surface_width = $request->surface_width;
                    $surfaceTypeData->shoulder_width = $request->shoulder_width;
                    $surfaceTypeData->start_chainage = $request->from_chainage;
                    $surfaceTypeData->end_chainage = $request->to_chainage;
                    $surfaceTypeData->base_layer_type = $request->base_layer_type;
                    $surfaceTypeData->base_layer_thickness = $request->base_layer_tickness;
                    $surfaceTypeData->sub_base_layer_type = $request->sub_base_layer_type;
                    $surfaceTypeData->sub_base_layer_thickness = $request->sub_base_layer_thickness;
                    $surfaceTypeData->pavement_type = $request->pavment_type;
                    $surfaceTypeData->shoulder_type = $request->shoulder_type;
                    $surfaceTypeData->land_slide = $request->land_slide;
                    $surfaceTypeData->construction_year = $request->construction_year;
                    // $surfaceTypeData->last_treatment_month = $request->last_treatment_month;
                    // $surfaceTypeData->last_treatment_year = $request->last_treatment_year;
                    $surfaceTypeData->maintenance_type = $request->maintenance_type;
                    // $surfaceTypeData->treatment_thickness = $request->treatment_thickness;
                    $surfaceTypeData->base_cbr = $request->base_cbr;
                    $surfaceTypeData->base_pi = $request->base_pi;
                    $surfaceTypeData->sub_base_cbr = $request->sub_base_cbr;
                    $surfaceTypeData->sub_base_pi = $request->sub_base_pi;
                    // $surfaceTypeData->protection_wall = $request->protection_wall;
                    $surfaceTypeData->drainage = $request->drainage;
                    $status = $surfaceTypeData->save();
                    if ($status) {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data Updated successfully'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Error to update the new data'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Road ID not found'
                    ]);
                }
                return $surfaceTypeData;
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e
            ]);
        }
    }
}
