<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use App\Models\AssetMasterDocumentCategory;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\PMS\PrtProjectDetail;
use App\Models\PMS\PrtProjectDetailsDraft;
use App\Models\PMS\PrtProjectDocumentDetail;
use App\Models\PMS\PrtProjectImageDetail;
use App\Models\PMS\PrtProjectSubAssetDetail;
use App\Models\PMS\PrtProjectSubAssetDetailsDraft;
use App\Models\PMS\PrtProjectWorkSubItemsDetail;
use App\Models\PMS\PrtProjectWorkItemsDetail;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PmsController extends Controller
{
    //
    public function index(Request $request)
    {
        session(['project_cd' => $request->id]);

        return redirect()->route('manage-project');
    }

    public function create(Request $request)
    {

        $user = Auth::user();
        $department = $user->department;

        $officeDetails = session('userMapping');

        if ($request->query('mode') == 'create') {
            session()->forget('project_cd');
        }

        $project_cd = session('project_cd');

        if ($project_cd) {
            // EDIT MODE
            $project = DB::select("
            SELECT
                main.*,
                DATE(main.project_start_date) AS project_start_date,
                DATE(main.project_end_date) AS project_end_date,
                COUNT(sub.id) AS total_sub_assets,
                SUM(CASE WHEN sub.sub_asset_type_cd = '0' THEN 1 ELSE 0 END) AS total_culvert,
                SUM(CASE WHEN sub.sub_asset_type_cd = '1' THEN 1 ELSE 0 END) AS total_bridge,
                SUM(CASE WHEN sub.sub_asset_type_cd = '2' THEN 1 ELSE 0 END) AS total_pvm,
                SUM(CASE WHEN sub.sub_asset_type_cd = '16' THEN 1 ELSE 0 END) AS total_rtws
            FROM projects.prt_project_details_draft AS main
            LEFT JOIN projects.prt_project_sub_asset_details_draft AS sub
                ON sub.project_cd = main.project_cd
            WHERE main.created_by = ?
            AND main.sent_for_finalize = 'N'
            AND main.project_cd = ?
            GROUP BY main.project_cd
            ORDER BY main.project_cd DESC
        ", [$user->id, $project_cd]);

            $project = $project[0] ?? null;
            //dd($project);
        } else {
            // CREATE MODE
            $project = null;
        }

        //dd($project);
        $circle_cd = $officeDetails->circle_cd;
        $division_cd = $officeDetails->division_cd;
        $officeType = $officeDetails->office_type_cd;

        if ($officeType === 'CO') {
            $div = DB::table('asset_master_divisions')->where('circle_cd', '=', $circle_cd)->get();
        } else {
            $div = DB::table('asset_master_divisions')->where('division_cd', '=', $division_cd)->get();
        }
        $roads = DB::table('road_details')->get();

        $departments = DB::table('department_details')->where('id', '=', $department)->get();


        //new code ended....

        $projectTypes = [
            'NEW' => 'New Works',
            'UPG' => 'Upgradation',
            'MTN' => 'Maintenance',
            'NWNUP' => 'New Works and Upgradation',
        ];

        $constructors = DB::table('projects.prt_contractor_details')->get();



        $roads4 = [
            'RD001' => 'Due Maintanence Road 001',
            'RD002' => 'Due Maintanence Road 002',
        ];

        $culverts = [
            'Culvert A' => 'Culvert A',
            'Culvert B' => 'Culvert B',
            'Culvert C' => 'Culvert C',
        ];

        $culverts2 = [
            'CUL001' => 'Upgrade Culvert A',
            'CUL002' => 'Upgrade Culvert B',
            'CUL003' => 'Upgrade Culvert C',
        ];

        $culverts3 = [
            'Due Maintanence Culvert A' => 'Due Maintanence Culvert A',
            'Due Maintanence Culvert B' => 'Due Maintanence Culvert B',
        ];

        $bridges = [
            'Bridge A' => 'Bridge A',
            'Bridge B' => 'Bridge B',
            'Bridge C' => 'Bridge C',
        ];

        $bridges2 = [
            'BR001' => 'Upgrade Bridge A',
            'BR002' => 'Upgrade Bridge B',
        ];

        $bridges3 = [
            'Due Maintanence Bridge A' => 'Due Maintanence Bridge A',
            'Due Maintanence Bridge B' => 'Due Maintanence Bridge B',
        ];

        $walls = [
            'Wall A' => 'Wall A',
            'Wall B' => 'Wall B',
            'Wall C' => 'Wall C',
        ];

        $walls1 = [
            'RW001' => 'Upgrade Wall A',
            'RW002' => 'Upgrade Wall B',
        ];

        $walls2 = [
            'Due Maintanence Wall A' => 'Due Maintanence Wall A',
            'Due Maintanence Wall B' => 'Due Maintanence Wall B',
        ];

        $pavements = [
            'Pavement A' => 'Pavement A',
            'Pavement B' => 'Pavement B',
            'Pavement C' => 'Pavement C',
        ];

        $pavements2 = [
            'Wall A' => 'Pavement A',
            'Wall B' => 'Pavement B',
            'Wall C' => 'Pavement C',
        ];

        $siteOffices = DB::table('projects.prm_site_incharge_office_details')->get();

        $workItems = DB::table('projects.prm_item_of_work')->get();

        $workSubItems = DB::table('projects.prm_item_sub_item_of_work')->get();




        $draftDetails = DB::select("
            SELECT
                main.*,
                COUNT(sub.id) AS total_sub_assets,
                SUM(CASE WHEN sub.sub_asset_type_cd = '0' THEN 1 ELSE 0 END) AS total_culvert,
                SUM(CASE WHEN sub.sub_asset_type_cd = '1' THEN 1 ELSE 0 END) AS total_bridge,
                SUM(CASE WHEN sub.sub_asset_type_cd = '2' THEN 1 ELSE 0 END) AS total_pvm,
                SUM(CASE WHEN sub.sub_asset_type_cd = '16' THEN 1 ELSE 0 END) AS total_rtws
            FROM projects.prt_project_details_draft AS main
            LEFT JOIN projects.prt_project_sub_asset_details_draft AS sub
                ON sub.project_cd = main.project_cd
            WHERE main.created_by = ?
            AND main.sent_for_finalize = 'N'
            GROUP BY main.project_cd
            ORDER BY main.project_cd DESC
        ", [$user->id]);

        // dd($draftDetails);

        // $draftDetails = DB::table('projects.prt_project_details_draft')
        // ->orderBy('project_cd','desc')
        // ->where('created_by','=',$user->id)
        // ->where('sent_for_finalize','=','N')
        // ->get();
        $draftReject = DB::table('projects.prt_project_details_draft')
            ->where('created_by', '=', $user->id)
            ->where('is_rejected', '=', 'Y')
            ->get();

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
        $workItems_exist = DB::table('projects.prt_project_work_sub_items_details_draft')
            ->pluck('project_cd')
            ->toArray();

        // dd($project_code);
        return view('pms.create', compact(
            'projectTypes',
            'departments',
            'div',
            'constructors',
            'roads',
            'culverts',
            'bridges',
            'walls',
            'pavements',
            'siteOffices',
            'workItems',
            'culverts2',
            'culverts3',
            'bridges2',
            'bridges3',
            'walls1',
            'walls2',
            'pavements2',
            'roads4',
            'draftDetails',
            'project_code_culvert',
            'project_code_bridge',
            'project_code_rtw',
            'project_code_pvm',
            'draftReject',
            'project',
            'project_cd',
            'workSubItems',
            'workItems_exist'
        ));
    }

    public function getCulverts(Request $req)

    {
        $culverts = DB::table("asset_road_cdwork_details")
            ->where('rd_system_id', $req->roadId)
            ->whereBetween('chainage', [$req->start, $req->end])
            ->get();

        return response()->json($culverts);
    }

    public function getMaintenanceCulverts(Request $req)
    {
        $culverts = DB::table("asset_road_cdwork_details")
            ->join(
                'projects.prt_maintainable_assets',
                'asset_road_cdwork_details.rd_cdwork_cd',
                '=',
                'projects.prt_maintainable_assets.asset_cd'
            )
            ->where('asset_road_cdwork_details.rd_system_id', $req->roadId)
            ->whereBetween('asset_road_cdwork_details.chainage', [$req->start, $req->end])
            ->select('asset_road_cdwork_details.rd_cdwork_cd', 'asset_road_cdwork_details.culvert_no')
            ->get();

        return response()->json($culverts);
    }

    public function getBridges(Request $req)
    {
        $bridges = DB::table("asset_road_bridge_details")
            ->where('rd_system_id', $req->roadId)
            ->whereBetween('chainage', [$req->start, $req->end])
            ->get(['bridge_name', "rd_bridge_cd"]);

        return response()->json($bridges);
    }

    public function getMaintenanceBridges(Request $req)
    {
        $bridges = DB::table("asset_road_bridge_details")
            ->join(
                'projects.prt_maintainable_assets',
                'asset_road_bridge_details.rd_bridge_cd',
                '=',
                'projects.prt_maintainable_assets.asset_cd'
            )
            ->where('asset_road_bridge_details.rd_system_id', $req->roadId)
            ->whereBetween('asset_road_bridge_details.chainage', [$req->start, $req->end])
            ->select('asset_road_bridge_details.bridge_name', 'asset_road_bridge_details.rd_bridge_cd')
            ->get();

        return response()->json($bridges);
    }

    public function getWalls(Request $req)
    {
        $walls = DB::table("asset_protection_wall_details")
        ->where('rd_system_id', $req->roadId)
        ->whereBetween('chainage', [$req->start, $req->end])
        ->where('wall_type_cd', '=', '1')
        ->get()
        ->map(function ($item) {
            $item->protection_wall_cd = (string) $item->protection_wall_cd;
            return $item;
        });

        return response()->json($walls);
    }

    public function getMaintenanceWalls(Request $req)
    {
        $walls = DB::table("asset_protection_wall_details")
            ->join(
                'projects.prt_maintainable_assets',
                DB::raw('CAST(asset_protection_wall_details.protection_wall_cd AS TEXT)'),
                '=',
                'projects.prt_maintainable_assets.asset_cd'
            )
            ->where('asset_protection_wall_details.rd_system_id', $req->roadId)
            ->whereBetween('asset_protection_wall_details.chainage', [$req->start, $req->end])
            ->where('wall_type_cd', '=', '1')
            ->select(DB::raw('CAST(asset_protection_wall_details.protection_wall_cd AS TEXT) as protection_wall_cd'))
            ->get();


        return response()->json($walls);
    }

    public function getPavements(Request $req)
    {
        $pavements = DB::table("asset_road_pavement_details")
            ->where('rd_system_id', $req->roadId)
            ->where(function ($q) use ($req) {
                $q->where('start_chainage', '>=', $req->start)
                    ->where('end_chainage', '<=', $req->end);
            })
            ->get(['rd_pavement_cd']);

        return response()->json($pavements);
    }

    public function getMaintenancePavements(Request $req)
    {
        $pavements = DB::table("asset_road_pavement_details")
            ->join(
                'projects.prt_maintainable_assets',
                'asset_road_pavement_details.rd_pavement_cd',
                '=',
                'projects.prt_maintainable_assets.asset_cd'
            )
            ->where('asset_road_pavement_details.rd_system_id', $req->roadId)
            ->where(function ($q) use ($req) {
                $q->where('start_chainage', '>=', $req->start)
                    ->where('end_chainage', '<=', $req->end);
            })
            ->select('asset_road_pavement_details.rd_pavement_cd')
            ->get();

        return response()->json($pavements);
    }

    public function getRoads($division_cd)
    {
        $roads3 = DB::table('asset_road_details')
            ->select('asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length')
            ->rightJoin(
                'asset_road_chainage_mappings',
                'asset_road_details.rd_system_id',
                '=',
                'asset_road_chainage_mappings.rd_system_id'
            )
            ->where('asset_road_details.division_cd', '=', $division_cd)
            ->get();


        return response()->json($roads3);
    }

    // written by: dipsikha
    public function getRoadsbysubdivision($sub_division_cd)
    {
        $roads = DB::table('asset_road_chainage_mappings')
            ->join(
                'asset_road_details',
                'asset_road_chainage_mappings.rd_system_id',
                '=',
                'asset_road_details.rd_system_id'
            )
            ->select(
                'asset_road_details.rd_name',
                'asset_road_details.rd_number',
                'asset_road_details.road_length'
            )
            ->where('asset_road_chainage_mappings.sub_division_cd', $sub_division_cd)
            ->get();


        return response()->json($roads);
    }


  public function getMaintenanceAssetsbysubdivision($sub_division_cd)
    {
        $roads = DB::table('asset_road_chainage_mappings as rcm')
            ->join('asset_road_details as rd', 'rcm.rd_system_id', '=', 'rd.rd_system_id')
            ->join('projects.prt_maintainable_assets as m', 'rd.rd_system_id', '=', 'm.asset_cd')
            ->where('rcm.sub_division_cd', $sub_division_cd)
            ->select(
                'rd.rd_system_id',
                'rd.rd_name',
                'rd.rd_number',
                'rd.road_length'
            )
            ->distinct()
            ->get();


        $allSubdivisionRoadIds = DB::table('asset_road_chainage_mappings')
            ->where('sub_division_cd', $sub_division_cd)
            ->pluck('rd_system_id')
            ->toArray();


       $applyCommonFilter = function ($query) use ($allSubdivisionRoadIds) {
            return $query
                ->whereIn('m.asset_type_cd', [0,1,16,2])
                ->whereIn('m.parent_asset_cd', $allSubdivisionRoadIds);
        };


        $pavements = DB::table("asset_road_pavement_details as p")
            ->join('projects.prt_maintainable_assets as m', 'p.rd_pavement_cd', '=', 'm.asset_cd');

        $pavements = $applyCommonFilter($pavements)
            ->select('p.rd_pavement_cd', 'm.parent_asset_cd')
            ->get();


        $walls = DB::table("asset_protection_wall_details as w")
            ->join(
                'projects.prt_maintainable_assets as m',
                DB::raw('CAST(w.protection_wall_cd AS TEXT)'),
                '=',
                'm.asset_cd'
            )
            ->where('w.wall_type_cd', '1');

        $walls = $applyCommonFilter($walls)
            ->select(
                DB::raw('CAST(w.protection_wall_cd AS TEXT) as protection_wall_cd'),
                'm.parent_asset_cd'
            )
            ->get();


        $bridges = DB::table("asset_road_bridge_details as b")
            ->join('projects.prt_maintainable_assets as m', 'b.rd_bridge_cd', '=', 'm.asset_cd');

        $bridges = $applyCommonFilter($bridges)
            ->select('b.bridge_name', 'b.rd_bridge_cd', 'm.parent_asset_cd')
            ->get();


        $culverts = DB::table("asset_road_cdwork_details as c")
            ->join('projects.prt_maintainable_assets as m', 'c.rd_cdwork_cd', '=', 'm.asset_cd');

        $culverts = $applyCommonFilter($culverts)
            ->select('c.rd_cdwork_cd', 'c.culvert_no', 'm.parent_asset_cd')
            ->get();

      
        return response()->json([
            'roads' => $roads,
            'pavements' => $pavements,
            'walls' => $walls,
            'bridges' => $bridges,
            'culverts' => $culverts,
        ]);
    }


    public function getMaintenanceBuildingsbysubdivision($catCd, $sub_division_cd)
    {
        if ($catCd == 0) {
            $column = "COALESCE(b.qtr_no, b.bld_qtr_name)";
        } else {
            $column = "b.bld_qtr_name";
        }

        $buildings = DB::table('buildings.asset_building_details as b')
            ->join(
                'projects.prt_maintainable_assets as m',
                'b.building_system_cd',
                '=',
                'm.asset_cd'
            )
            ->select(
                'b.building_system_cd',
                DB::raw("$column as building_name")
            )
            ->where('b.sub_division_cd', $sub_division_cd)
            ->where('b.building_class_cd', $catCd)
            ->distinct()
            ->get();

        return response()->json($buildings);
    }

	public function getUpgrdationBuildingsbysubdivision($catCd, $sub_division_cd)
    {
        if ($catCd == 0) {
            $column = "COALESCE(b.qtr_no, b.bld_qtr_name)";
        } else {
            $column = "b.bld_qtr_name";
        }

        $buildings = DB::table('buildings.asset_building_details as b')
            ->select(
                'b.building_system_cd',
                DB::raw("$column as building_name")
            )
            ->where('b.sub_division_cd', $sub_division_cd)
            ->where('b.building_class_cd', $catCd)
            ->distinct()
            ->get();

        return response()->json($buildings);
    }
	
    public function getMaintVehicles($subDivId)
    {
        $office = DB::table('office_details')
            ->where('sub_division_cd', $subDivId)
            ->first();


        $officeId = $office->id;

        $vehicles = DB::table('mechanicals.asset_mech_vehicles_details as v')
            ->join(
                'projects.prt_maintainable_assets as m',
                'v.vehicle_asset_cd',
                '=',
                'm.asset_cd'
            )
            ->where('created_at_office_cd', $officeId)
            ->select(
                'v.vehicle_asset_cd',
                'v.vehicle_name'
            )
            ->get();

        return response()->json([
            'vehicles' => $vehicles
        ]);
    }


    public function getBuildingDetails($buildingCd)
    {
        $building = DB::table('buildings.asset_building_details as b')
            ->leftJoin(
                'buildings.asset_master_building_locations as l',
                'b.building_location_cd',
                '=',
                'l.location_cd'
            )
            ->leftJoin(
                'buildings.asset_master_building_types as bt',
                'b.building_type_cd',
                '=',
                'bt.building_type_cd'
            )
            ->leftJoin(
                'asset_master_dept_of_state as dept',
                'b.asset_owning_dept_cd',
                '=',
                'dept.id'
            )
            ->leftJoin(
                'buildings.asset_master_building_class as bc',
                'b.building_class_cd',
                '=',
                'bc.building_class_cd'
            )
            ->select(
                'b.building_system_cd',
                'b.is_maintained_by_npwd',
                'b.lat',
                'bc.building_class_descr',
                'b.lon',
                'l.location_name',
                'b.building_type_cd',
                'b.building_class_cd',
                'bt.building_type_descr',
                'b.asset_owning_dept_cd',
                'dept.dept_name',
                DB::raw("
                    CASE
                        WHEN b.building_class_cd = '0' THEN b.qtr_no
                        WHEN b.building_class_cd IN ('1','2') THEN b.bld_qtr_name
                    END as building_name
                ")
            )
            ->where('building_system_cd', $buildingCd)
            ->first();

        $buildingTypes = DB::table('buildings.asset_master_building_types')
            ->select('building_type_cd', 'building_type_descr')
            ->orderBy('building_type_descr')
            ->get();

        $departments = DB::table('asset_master_dept_of_state')
            ->select('id', 'dept_name')
            ->orderBy('dept_name')
            ->get();

        $categories = DB::table('buildings.asset_master_building_class')
            ->select('building_class_cd', 'building_class_descr')
            ->orderBy('building_class_descr')
            ->get();

        return response()->json([
            'building' => $building,
            'buildingTypes' => $buildingTypes,
            'departments' => $departments,
            'categories' => $categories
        ]);
    }

    public function getVehicles($subDivId)
    {
        $office = DB::table('office_details')
            ->where('sub_division_cd', $subDivId)
            ->first();


        $officeId = $office->id;

        $vehicles = DB::table('mechanicals.asset_mech_vehicles_details as v')
            ->where('created_at_office_cd', $officeId)
            ->select(
                'v.vehicle_asset_cd',
                'v.vehicle_name'
            )
            ->get();

        return response()->json([
            'vehicles' => $vehicles
        ]);
    }


    public function getMaintEquipments($subDivId)
    {

        $office = DB::table('office_details')
            ->where('sub_division_cd', $subDivId)
            ->first();


        $officeId = $office->id;

        $equipments = DB::table('mechanicals.asset_mech_equipment_details as e')
            ->join(
                'projects.prt_maintainable_assets as m',
                'e.euipment_cd',
                '=',
                'm.asset_cd'
            )
            ->where('created_at_office_cd', $officeId)
            ->select(
                'e.euipment_cd',
                'e.equipment_name'
            )
            ->get();

        return response()->json([
            'equipments' => $equipments
        ]);
    }

    public function getEquipments($subDivId)
    {
        $office = DB::table('office_details')
            ->where('sub_division_cd', $subDivId)
            ->first();


        $officeId = $office->id;

        $equipments = DB::table('mechanicals.asset_mech_equipment_details as e')
            ->where('created_at_office_cd', $officeId)
            ->select(
                'e.euipment_cd',
                'e.equipment_name'
            )
            ->get();

        return response()->json([
            'equipments' => $equipments
        ]);
    }

    public function getVehicleDetails($vehicleId)
    {

        $vehiclesDetails = DB::table('mechanicals.asset_mech_vehicles_details as v')
            ->where('vehicle_asset_cd', $vehicleId)
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
                'v.vehicle_name',
                'v.alloted_to',
                'v.alloted_from'
            )
            ->first();

        return response()->json([
            'vehiclesDetails' => $vehiclesDetails
        ]);

    }


    public function getEquipmentDetails($equipmentId)
    {
        $equipmentsDetails = DB::table('mechanicals.asset_mech_equipment_details as v')
            ->where('euipment_cd', $equipmentId)
            ->leftJoin(
                'mechanicals.asset_master_equipment_conditions as vc',
                'v.equipment_condition_cd',
                '=',
                'vc.condition_cd'
            )
            ->select(
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

        return response()->json([
            'equipmentsDetails' => $equipmentsDetails
        ]);

    }

    public function getBuildingsCategory(Request $req)
    {
        $categories = DB::table('buildings.asset_master_building_class')
            ->select(
                'building_class_cd',
                'building_class_descr'
            )
            ->orderBy('building_class_descr', 'asc')
            ->get();

        return response()->json($categories);
    }

    public function getRoadLength($rd_system_id)
    {
        $data = DB::table('asset_road_details as ard')
            ->rightJoin('asset_road_chainage_mappings as arcm', 'ard.rd_system_id', '=', 'arcm.rd_system_id')
            ->where('ard.rd_system_id', $rd_system_id)
            ->select('ard.road_length', 'arcm.chainage_from', 'arcm.chainage_to')
            ->first();

        return response()->json([
            'road_length' => $data->road_length ?? 0,
            'chainage_from' => $data->chainage_from ?? 0,
            'chainage_to' => $data->chainage_to ?? 0,
        ]);
    }

    public function getSubItems($item_cd, $id)
    {
        try {

            $subItems = DB::table('projects.prt_project_work_sub_items_details as d')
                ->select('d.*', 'm.sub_item_name as name')
                ->join('projects.prm_item_sub_item_of_work as m', function ($join) {
                    $join->on('d.item_cd', '=', 'm.item_cd')
                        ->on('d.sub_item_cd', '=', 'm.sub_item_cd');
                })
                ->where('d.item_cd', $item_cd)
                ->where('project_cd', $id)
                ->get();

            return response()->json([
                'status' => 'success',
                'subitems' => $subItems
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getUpgradationSubassets($project_cd)
    {
        // Fetch draft row
        $draft = DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $project_cd)
            ->first();

        if (!$draft || !$draft->others) {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        // Decode JSON
        $others = json_decode($draft->others, true);

        // Prepare response data
        $data = [
            'upgrade_road'      => $others['upgrade_road'] ?? [],
            'upg_start_chainage' => $others['upg_start_chainage'] ?? [],
            'upg_end_chainage'  => $others['upg_end_chainage'] ?? [],
            'walls'             => $others['walls'] ?? [],
            'bridges'           => $others['bridges'] ?? [],
            'culverts'          => $others['culverts'] ?? [],
        ];

        return response()->json(['status' => true, 'data' => $data]);
    }


    public function getMaintenanceSubassets($project_cd)
    {
        $draft = DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $project_cd)
            ->first();

        if (!$draft || !$draft->others) {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        // Decode JSON
        $others = json_decode($draft->others, true);

        // Prepare response data
        $data = [
            'mnt_road'          => $others['mnt_road'] ?? [],
            'mnt_start_chainage' => $others['mnt_start_chainage'] ?? [],
            'mnt_end_chainage'  => $others['mnt_end_chainage'] ?? [],
            'mnt_walls'         => $others['mnt_walls'] ?? [],
            'mnt_bridges'       => $others['mnt_bridges'] ?? [],
            'mnt_culverts'      => $others['mnt_culverts'] ?? [],
        ];

        return response()->json(['status' => true, 'data' => $data]);
    }




    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // 'project_name'=>'string|required',
            // 'owner_dept_cd' =>'string|required',
            // 'project_start_date'=>'date|required',
            // 'project_end_date'=>'date|required',
            // 'project_awarded_to'=>'string|required',
            // 'division_cd'=>'integer|required',
            // 'parent_asset_cd'=>'string|required',
            // 'site_incharge_name'=>'string|required',
            // 'site_incharge_office_cd'=>'string|required',
            // 'site_incharge_ph_no'=>'integer|required',
            // 'site_images'=>'required|image|mimes:jpeg,jpg,png|max:1024'
        ]);
        $department = $request->owner_dept_cd;
        $deptName = DB::table('department_details')->where('id', '=', $department)->value('dept_short_code');
        $year = date('Y');
        $prefix = 'PRJ_' . strtoupper($deptName) . '_' . $year . '_';

        $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('17');

        if ($makerCheckerStatus === 'Y') {

            $lastProject = DB::table('projects.prt_project_details_draft')
                ->where('owner_dept_cd', $request->owner_dept_cd)
                ->whereYear('created_at', $year)
                ->orderByDesc('project_cd')
                ->value('project_cd');
        } else {
            $lastProject = DB::table('projects.prt_project_details')
                ->where('owner_dept_cd', $request->owner_dept_cd)
                ->whereYear('created_at', $year)
                ->orderByDesc('project_cd')
                ->value('project_cd');
        }

        // Find the latest project_cd for this year & department


        // Extract serial number after the last '_'
        $serial = 1;
        if ($lastProject) {
            $lastSerial = (int)substr($lastProject, strrpos($lastProject, '_') + 1);
            $serial = $lastSerial + 1;
        }
        if ($serial <= 999) {
            // Pad with leading zeros for serial numbers <= 999
            $formattedSerial = str_pad($serial, 3, '0', STR_PAD_LEFT);
        } else {
            // No padding for serial numbers > 999
            $formattedSerial = (string)$serial;
        }

        $project_cd = $prefix . $formattedSerial;


        DB::beginTransaction();
        //generating new road name if not exist
        if ($request->has('new_rd_name')) {
            $prefix = strtoupper(substr($request->slnewRdNew, 0, 3));
            $seq_no = rand(1, 10);

            $parent_asset_cd = $prefix . '_' . $seq_no;
        } else {
            $parent_asset_cd = $request->parent_asset_cd;
        }
        try {
            $userid = Auth::user()->id;
            $randomNumber = mt_rand(100, 999);
            $currentTime = time();
            $randomCode = $userid . $currentTime . $randomNumber;
            // $roads = $request->input('roads', []);
            $rows = (int) $request->rowCount;
            $rows_mnt = (int) $request->mnt_rowCount;

            $culvertData = [];
            $bridgeData  = [];
            $wallData    = [];
            $mnt_culvertData = [];
            $mnt_bridgeData  = [];
            $mnt_wallData    = [];

            for ($i = 1; $i <= $rows; $i++) {

                $culvertData[$i] = $request->input("culverts_$i", []);
                $bridgeData[$i]  = $request->input("bridges_$i", []);
                $wallData[$i]    = $request->input("walls_$i", []);
            }
            for ($i = 1; $i <= $rows_mnt; $i++) {
                $mnt_culvertData[$i] = $request->input("culverts_mnt_$i", []);
                $mnt_bridgeData[$i]  = $request->input("bridges_mnt_$i", []);
                $mnt_wallData[$i]    = $request->input("walls_mnt_$i", []);
            }
            $siteIncharge = DB::table('projects.prm_site_incharge_office_details')
                ->select('contact_person_name', 'ph_no')
                ->where('office_cd', $request->site_incharge_office_cd)
                ->first();

            $data = [
                'project_cd'                => $project_cd,
                'project_name'              => $request->project_name,
                'owner_dept_cd'             => $request->owner_dept_cd,
                'division_cd'               => $request->division_cd,
                'parent_asset_cd'           => $parent_asset_cd,
                'project_start_date'        => $request->project_start_date,
                'project_end_date'          => $request->project_end_date,
                'est_proj_cost'             => $request->est_proj_cost,
                'defect_liability_period'   => $request->defect_liability_period,
                'project_status_cd'         => 1,
                'project_awarded_to'        => $request->project_awarded_to,
                'site_eng_id'               => $userid,
                'site_incharge_name'        => $siteIncharge->contact_person_name ?? null,
                'site_incharge_office_cd'   => $request->site_incharge_office_cd,
                'site_incharge_ph_no'       => $siteIncharge->ph_no ?? null,
                // 'sent_for_finalize'=> $userid,
                // 'approved_at'=>now(),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $userid,
                'updated_by' => $userid,
                'others' => json_encode([
                    'project_type' => $request->projectTypeSelect,
                    'new_road_name' => $request->input('new_rd_name', []),
                    'rd_length' => $request->rd_length,
                    'culvert_start_chainage' => $request->input('culvert_start', []),
                    'bridge_start_chainage' => $request->input('bridge_start', []),
                    'rtw_start_chainage' => $request->input('rtw_start', []),
                    'pvm_start_chainage' => $request->input('pvm_start', []),
                    'site_incharge_ph_no' => $request->site_incharge_ph_no,
                    'site_incharge_name' => $request->site_incharge_name,
                    'upgrade_road' => $request->input('roads', []),
                    'upg_start_chainage' => $request->input('start_chainage', []),
                    'upg_end_chainage' => $request->input('end_chainage', []),
                    'culverts' => $culvertData,
                    'bridges' => $bridgeData,
                    'walls' => $wallData,
                    'mnt_road' => $request->input('roads_mnt', []),
                    'mnt_start_chainage' => $request->input('start_chainage_mnt', []),
                    'mnt_end_chainage' => $request->input('end_chainage_mnt', []),
                    'mnt_culverts' => $mnt_culvertData,
                    'mnt_bridges' => $mnt_bridgeData,
                    'mnt_walls' => $mnt_wallData,
                ]),
            ];


            if ($makerCheckerStatus === 'Y') {
                $status = PrtProjectDetailsDraft::create($data);
            } else {
                $extraData = [
                    'approved_by' => $userid,
                    'approved_at' => now()
                ];
                $status = PrtProjectDetail::create(array_merge($extraData, $data));
            }

            // if(isNull($request->slnewRdNew)){
            //     $parent_asset_cd = '';
            // }
            if ($request->has('work_items')) {

                foreach ($request->work_items as $index => $item_cd) {

                    // Quantity
                    $quantity = $request->work_qtys[$index] ?? null;

                    // Predecessors (may contain multiple or null)
                    $predArray = $request->predecessorSelect[$index] ?? [];
                    $cleanPreds = array_filter($predArray);  // removes null values

                    // Convert to CSV or store null
                    $predCodes = !empty($cleanPreds) ? implode(",", $cleanPreds) : null;

                    // Dates (nullable)
                    $est_start = $request->estimateStartDate[$index] ?? null;
                    $est_end   = $request->estimateEndDate[$index] ?? null;
                    // dd($est_start);

                    $workItemsData = [
                        'project_cd'               => $project_cd,
                        'item_cd'                  => $item_cd,
                        'quantity'                 => $quantity,
                        'predecessors_item_codes'  => $predCodes, // e.g. "1,6,3"
                        'est_start_date'           => $est_start,
                        'est_end_date'             => $est_end,
                        'is_published'             => 'Y',
                        'created_at'               => now(),
                        'updated_at'               => now(),
                        'created_by'               => auth()->id(),
                        'updated_by'               => auth()->id(),
                    ];
                    PrtProjectWorkItemsDetail::create($workItemsData);
                }
            }
            $subItems = $request->sub_items ?? [];

            if (is_string($subItems)) {
                $subItems = json_decode($subItems, true);
            }

            //dd($subItems);
            if (!empty($subItems) && is_array($subItems)) {

                foreach ($subItems as $item_cd => $subItemList) {  // item_cd = 2

                    foreach ($subItemList as $key => $subData) {   // sub_item1, sub_item2, sub_item3

                        $subItemData = [
                            'project_cd'     => $project_cd,
                            'item_cd'        => $item_cd,
                            'sub_item_cd'    => $subData['code'],
                            'sub_item_name'  => $subData['name'] ?? null,
                            'quantity'       => $subData['quantity'] ?? null,
                            'est_start_date' => $subData['start'] ?: null,
                            'est_end_date'   => $subData['end'] ?: null,
                            'is_published'   => 'Y',
                            'created_at'     => now(),
                            'updated_at'     => now(),
                            'created_by'     => auth()->id(),
                            'updated_by'     => auth()->id(),
                        ];

                        if ($makerCheckerStatus === 'Y') {
                            PrtProjectWorkSubItemsDetail::create($subItemData);
                        } else {
                            PrtProjectWorkSubItemsDetail::create($subItemData);
                        }
                    }
                }
            }

            if ($status) {
                $this->handleSubAsset($request, $project_cd, $parent_asset_cd, $makerCheckerStatus);
                $this->handleDocument($request, $randomCode, $project_cd);

                if ($request->input('draft_id')) {
                    $userId = $request->user()->id;
                    $cacheKey = "drafts_{$userId}";
                    $draftId = $request->input('draft_id');

                    $drafts = Cache::get($cacheKey, []);

                    if (isset($drafts[$draftId])) {
                        unset($drafts[$draftId]);
                        Cache::put($cacheKey, $drafts, now()->addDays(7));
                    }
                }
                return redirect()->back()->with('success', 'Project details saved successfully! with project Id:' . $project_cd);
            } else {
                return redirect()->back()->with('error', 'Project details not saved');
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
    private function handleDocument($request, $randomCode, $project_cd)
    {
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

        if ($request->hasFile('workOrder')) {
            $file = $request->file('workOrder');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Work Order')->get()->first();
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

        if ($request->hasFile('designDoc')) {
            $file = $request->file('designDoc');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Design Document')->get()->first();
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
                ->where('doc_catg_descr', 'DPR Document')->get()->first();
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

        if ($request->hasFile('projectPlan')) {
            $file = $request->file('projectPlan');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Project Plan')->get()->first();
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
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    PrtProjectSubAssetDetail::create($subAssetData);
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
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    PrtProjectSubAssetDetail::create($subAssetData);
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
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    PrtProjectSubAssetDetail::create($subAssetData);
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
                ];
                if ($makerCheckerStatus === 'Y') {
                    PrtProjectSubAssetDetailsDraft::create($subAssetData);
                } else {
                    PrtProjectSubAssetDetail::create($subAssetData);
                }
            }

            DB::commit();
            return null;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getModaldetails($id, $type)
    {

        $culvertValues = DB::table('projects.prt_project_sub_asset_details_draft as pms_sub_asset')
            ->select('pms_sub_asset.start_chainage', 'public_sub_asset.sub_assets_descr as name')
            ->JOIN('asset_master_road_sub_assets as public_sub_asset', 'pms_sub_asset.sub_asset_type_cd', '=', 'public_sub_asset.sub_asset_cd')
            ->where('pms_sub_asset.project_cd', '=', $id)
            ->where('pms_sub_asset.sub_asset_type_cd', '0')
            ->get();
        $bridgeValues =   DB::table('projects.prt_project_sub_asset_details_draft as pms_sub_asset')
            ->select('pms_sub_asset.start_chainage', 'public_sub_asset.sub_assets_descr as name')
            ->JOIN('asset_master_road_sub_assets as public_sub_asset', 'pms_sub_asset.sub_asset_type_cd', '=', 'public_sub_asset.sub_asset_cd')
            ->where('pms_sub_asset.project_cd', '=', $id)
            ->where('pms_sub_asset.sub_asset_type_cd', '1')
            ->get();

        $rtwValues =   DB::table('projects.prt_project_sub_asset_details_draft as pms_sub_asset')
            ->select('pms_sub_asset.start_chainage', 'public_sub_asset.sub_assets_descr as name')
            ->JOIN('asset_master_road_sub_assets as public_sub_asset', 'pms_sub_asset.sub_asset_type_cd', '=', 'public_sub_asset.sub_asset_cd')
            ->where('pms_sub_asset.project_cd', '=', $id)
            ->where('pms_sub_asset.sub_asset_type_cd', '16')
            ->get();

        $pvmValues =  DB::table('projects.prt_project_sub_asset_details_draft as pms_sub_asset')
            ->select('pms_sub_asset.start_chainage', 'public_sub_asset.sub_assets_descr as name')
            ->JOIN('asset_master_road_sub_assets as public_sub_asset', 'pms_sub_asset.sub_asset_type_cd', '=', 'public_sub_asset.sub_asset_cd')
            ->where('pms_sub_asset.project_cd', '=', $id)
            ->where('pms_sub_asset.sub_asset_type_cd', '2')
            ->get();

        if ($type === 'Culvert') {
            $values = $culvertValues;
        } elseif ($type === 'Bridge') {
            $values = $bridgeValues;
        } elseif ($type === 'rtw') {
            $values = $rtwValues;
        } elseif ($type === 'pvm') {
            $values = $pvmValues;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched successfully',
            'value' => $values
        ]);
    }

    public function getItemsDetail($id)
    {
        try {

            // Fetch all items first
            $items = DB::table('projects.prt_project_work_items_details AS d')
                ->leftJoin('projects.prm_item_of_work AS w', 'd.item_cd', '=', 'w.item_cd')
                ->select(
                    'w.item_name AS name',
                    'd.quantity AS qty',
                    'd.est_start_date AS start_date',
                    'd.est_end_date AS end_date',
                    'd.predecessors_item_codes AS pre_code',
                    'd.item_cd AS item_cd'
                )
                ->where('d.project_cd', $id)
                ->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'value' => []
                ]);
            }

            // Convert predecessor item codes → names
            $result = $items->map(function ($item) {

                // default empty
                $predecessorNames = [];

                if (!empty($item->pre_code)) {

                    // convert "1,2,4" → array
                    $codes = explode(',', $item->pre_code);

                    // fetch names for these item_cd's
                    $predecessorNames = DB::table('projects.prm_item_of_work')
                        ->whereIn('item_cd', $codes)
                        ->pluck('item_name')
                        ->toArray();
                }

                return [
                    'name' => $item->name,
                    'qty' => $item->qty,
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'item_cd' => $item->item_cd,

                    // return names instead of codes
                    'predecessor' => $predecessorNames
                ];
            });

            return response()->json([
                'status' => 'success',
                'value' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkProjectName(Request $request)
    {
        $name = strtolower(trim($request->query('name')));

        $exists = DB::table('projects.prt_project_details')
            ->whereRaw('LOWER(project_name) = ?', [$name])
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkRoadName(Request $request)
    {
        $roadName = $request->query('name');

        // Fetch JSON field from prt_project_details
        $records = DB::table('projects.prt_project_details')->get(['others']);

        foreach ($records as $record) {
            $others = json_decode($record->others, true);
            if (isset($others['new_road_name']) && strcasecmp($others['new_road_name'], $roadName) === 0) {
                return response()->json(['exists' => true]);
            }
        }

        return response()->json(['exists' => false]);
    }

    public function edit($id)
    {

        $user = Auth::user();

        $project = DB::select("
        SELECT
            main.*,
            COUNT(sub.id) AS total_sub_assets,
            SUM(CASE WHEN sub.sub_asset_type_cd = '0' THEN 1 ELSE 0 END) AS total_culvert,
            SUM(CASE WHEN sub.sub_asset_type_cd = '1' THEN 1 ELSE 0 END) AS total_bridge,
            SUM(CASE WHEN sub.sub_asset_type_cd = '2' THEN 1 ELSE 0 END) AS total_pvm,
            SUM(CASE WHEN sub.sub_asset_type_cd = '16' THEN 1 ELSE 0 END) AS total_rtws
        FROM projects.prt_project_details_draft AS main
        LEFT JOIN projects.prt_project_sub_asset_details_draft AS sub
            ON sub.project_cd = main.project_cd
        WHERE main.created_by = ?
        AND main.sent_for_finalize = 'N'
        AND main.project_cd = ?
        GROUP BY main.project_cd
        ORDER BY main.project_cd DESC
    ", [$user->id, $id]);

        $project = $project[0] ?? null;


        $projectTypes = [
            'NEW' => 'New Works',
            'UPG' => 'Upgradation',
            'MTN' => 'Maintenance',
            'NWNUP' => 'New Works and Upgradation',
        ];


        return view('pms.verification', compact('project', 'projectTypes'));
    }

    public function update() {}
}
