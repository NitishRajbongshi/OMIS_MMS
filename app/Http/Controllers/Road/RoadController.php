<?php

namespace App\Http\Controllers\Road;

use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\AssetMasterZone;
use App\Models\AssetMasterCircle;
use App\Models\AssetMasterRdType;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDivision;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterRoadOwner;
use App\Models\Road\AssetRoadDetail;
use App\Models\AssetMasterLgdDistrict;
use App\Models\AssetMasterSubDivision;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetRoadChainageMapping;
use App\Models\Road\AssetRoadDetailsDraft;
use App\Models\Road\AssetRoadDocumentKmlFileDetails;

class RoadController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        $this->middleware("auth");
    }

    public function index()
    {
        try {
            Log::info('Road controller: ');

            $user = Auth::user();

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
                    $division_cd = $officeDivisionDtls->division_cd;
                    $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                }
            }

            $zoneDetails = AssetMasterZone::all();
            $circleDetails = AssetMasterCircle::all();
            $divisionDetails = AssetMasterDivision::all();
            $subDivisionDetails = AssetMasterSubDivision::all();

            $roadType = 'SR';

            if ($user->department == '3') {
                $roadType = 'NH';
            }
            $roadDetails = null;
            $roadDraftDetails = null;
            $roadDraftDetails = DB::table('asset_road_details_draft as road_details')
                ->select(
                    'road_details.rd_system_id',
                    'road_details.rd_number',
                    'road_details.rd_name',
                    'road_details.road_length',
                    'road_details.rd_category_cd',
                    'road_details.rd_type_cd',
                    'road_details.rd_owner_cd',
                    'road_details.district_cd',
                    'road_details.block_cd',
                    'road_details.is_rejected',
                    'road_details.reason_of_rejection',
                    'road_details.division_cd',
                    'category.rd_catg_descr',
                    'type.rd_type_descr',
                    'owner.owner_name',
                    'district.dist_name',
                    'block.block_name',
                    'div.division_name',
                    'pp.project_cd'
                )
                ->leftJoin('asset_master_road_category as category', 'road_details.rd_category_cd', '=', 'category.rd_catg_cd')
                ->leftJoin('asset_master_rd_type as type', 'road_details.rd_type_cd', '=', 'type.rd_type_cd')
                ->leftJoin('asset_master_road_owner as owner', 'road_details.rd_owner_cd', '=', 'owner.owner_cd')
                ->leftJoin('asset_master_lgd_district as district', 'road_details.district_cd', '=', 'district.dist_code')
                ->leftJoin('asset_master_block as block', 'road_details.block_cd', '=', 'block.block_cd')
                ->leftJoin('asset_master_divisions as div', 'road_details.division_cd', '=', 'div.division_cd')
                //Saiful --20-04-2026 -- Start
                ->leftJoin('prt_project_asset_plan as pp', 'pp.id', '=', 'road_details.asset_plan_id')
                //Saiful --20-04-2026 -- End
                ->where('road_details.sent_for_finalize', '=', 'N')
                ->where('road_details.road_created_at_office_cd', '=', session('office_cd'))
                ->where('road_details.road_created_at_office_type', '=', session('officeType'))
                ->orderBy('road_details.updated_at', 'desc')
                ->simplePaginate(200);

            if ($user->office_type_cd == 'SDO') {
                LOG::info("fetching Finalised Road list from within the Office Level");
                $sdo_road_id_list = [];
                $SDORoadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->whereIn('rd_system_id', function ($query) {
                        $query->select('rd_system_id')
                            ->from('asset_road_chainage_mappings')
                            ->whereNotNull('sub_division_cd')
                            ->where('remaining_chainage_length', 0)
                            ->where('chainage_step_id', 1);
                    })
                    ->where('sub_division_cd', $sub_division_cd)
                    ->distinct()
                    ->get();
                foreach ($SDORoadDetails as $item) {
                    $sdo_road_id_list[] = $item->rd_system_id;
                }
                $SDORoadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->where('remaining_chainage_length', '=', 0)
                    ->where('sub_division_cd', '=', $sub_division_cd)
                    ->where('chainage_step_id', '0')
                    ->distinct('rd_system_id')
                    ->get();
                foreach ($SDORoadDetails as $item) {
                    $sdo_road_id_list[] = $item->rd_system_id;
                }

                $roadDetails = DB::table('asset_road_chainage_mappings as chm')
                    ->select(
                        'chm.rd_system_id',
                        'chm.chainage_from',
                        'chm.chainage_to',
                        'chm.sub_division_cd',
                        'chm.remaining_chainage_length',
                        'chm.chainage_created_at_office_cd',
                        'rd.rd_name',
                        'rd.rd_number',
                        'rd.road_length',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        //saiful --21-04-2026 -- Start
                        'pp.project_cd'
                        //saiful --21-04-2026 -- End
                    )
                    ->leftJoin('asset_road_details as rd', 'chm.rd_system_id', '=', 'rd.rd_system_id')
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'rd.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'rd.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    //saiful --21-04-2026 -- Start
                    ->leftJoin('prt_project_asset_plan as pp', 'rd.asset_plan_id', '=', 'pp.id')
                    //saiful --21-04-2026 -- End
                    ->where('rd.road_type', '=', $roadType)
                    ->where('rd.road_created_at_office_cd', '=', session('office_cd'))
                    ->distinct('chm.rd_system_id')
                    ->simplePaginate(200);
            } elseif ($user->office_type_cd == 'DO') {
                LOG::info("fetching Finalised Road list from within the DO");
                $do_road_id_list = [];
                $DORoadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->whereIn('rd_system_id', function ($query) {
                        $query->select('rd_system_id')
                            ->from('asset_road_chainage_mappings')
                            ->whereNotNull('division_cd')
                            ->where('remaining_chainage_length', 0)
                            ->where('chainage_step_id', 1);
                    })
                    ->where('division_cd', $division_cd)
                    ->distinct()
                    ->get();
                foreach ($DORoadDetails as $item) {
                    $do_road_id_list[] = $item->rd_system_id;
                }
                $DORoadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->where('remaining_chainage_length', '=', 0)
                    ->where('division_cd', '=', $division_cd)
                    ->where('chainage_step_id', '0')
                    ->distinct('rd_system_id')
                    ->get();
                foreach ($DORoadDetails as $item) {
                    $do_road_id_list[] = $item->rd_system_id;
                }
                $roadDetails = DB::table('asset_road_chainage_mappings as chm')
                    ->select(
                        'chm.rd_system_id',
                        'chm.chainage_from',
                        'chm.chainage_to',
                        'chm.sub_division_cd',
                        'chm.remaining_chainage_length',
                        'chm.chainage_created_at_office_cd',
                        'rd.rd_name',
                        'rd.rd_number',
                        'rd.road_length',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        //saiful --21-04-2026 -- Start
                        'pp.project_cd'
                        //saiful --21-04-2026 -- End
                    )
                    ->leftJoin('asset_road_details as rd', 'chm.rd_system_id', '=', 'rd.rd_system_id')
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'rd.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'rd.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    //saiful --21-04-2026 -- Start
                    ->leftJoin('prt_project_asset_plan as pp', 'rd.asset_plan_id', '=', 'pp.id')
                    //saiful --21-04-2026 -- End
                    ->whereNotNull('chm.division_cd')
                    ->where('rd.road_type', '=', $roadType)
                    ->whereIn('chm.rd_system_id', $do_road_id_list)
                    ->distinct('chm.rd_system_id')
                    ->simplePaginate(200);
            } elseif ($user->office_type_cd == 'CO') {
                LOG::info("fetching Finalised Road list from within the CO Level");
                $co_road_id_list = [];
                $CORoadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->whereIn('rd_system_id', function ($query) {
                        $query->select('rd_system_id')
                            ->from('asset_road_chainage_mappings')
                            ->whereNotNull('circle_cd')
                            ->where('remaining_chainage_length', 0)
                            ->where('chainage_step_id', 1);
                    })
                    ->where('circle_cd', $circle_cd)
                    ->distinct()
                    ->get();
                foreach ($CORoadDetails as $item) {
                    $co_road_id_list[] = $item->rd_system_id;
                }
                $CORoadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->where('remaining_chainage_length', '=', 0)
                    ->where('circle_cd', '=', $circle_cd)
                    ->where('chainage_step_id', '0')
                    ->distinct('rd_system_id')
                    ->get();
                foreach ($CORoadDetails as $item) {
                    $co_road_id_list[] = $item->rd_system_id;
                }
                $roadDetails = DB::table('asset_road_chainage_mappings as chm')
                    ->select(
                        'chm.rd_system_id',
                        'chm.chainage_from',
                        'chm.chainage_to',
                        'chm.sub_division_cd',
                        'chm.remaining_chainage_length',
                        'chm.chainage_created_at_office_cd',
                        'rd.rd_name',
                        'rd.rd_number',
                        'rd.road_length',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        //saiful --21-04-2026 -- Start
                        'pp.project_cd'
                        //saiful --21-04-2026 -- End
                    )
                    ->leftJoin('asset_road_details as rd', 'chm.rd_system_id', '=', 'rd.rd_system_id')
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'rd.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'rd.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    //saiful --21-04-2026 -- Start
                    ->leftJoin('prt_project_asset_plan as pp', 'rd.asset_plan_id', '=', 'pp.id')
                    //saiful --21-04-2026 -- End
                    ->where('rd.road_type', '=', $roadType)
                    ->whereIn('chm.rd_system_id', $co_road_id_list)
                    ->distinct('chm.rd_system_id')
                    ->simplePaginate(200);
            } elseif ($user->office_type_cd == 'ZO') {
                LOG::info("fetching Finalised Road list from within the ZO Level");
                // Algorithm
                // get all the road created at upper level
                // - Zone cd should be not null
                // - Remaining length should be equal to zero
                // - chainage_step_id = 1, means it is created in other level
                // - Also the chainage should belongs to that particular zone area
                // Get all the road created at same office
                // - Remaining length should be equal to zero
                // - chainage_step_id = 0, means it is created in same level
                $roadid = [];
                $roadID = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->whereIn('rd_system_id', function ($query) {
                        $query->select('rd_system_id')
                            ->from('asset_road_chainage_mappings')
                            ->whereNotNull('zone_cd')
                            ->where('remaining_chainage_length', 0)
                            ->where('chainage_step_id', 1);
                    })
                    ->where('zone_cd', $zone_cd)
                    ->distinct()
                    ->get();
                foreach ($roadID as $item) {
                    $roadid[] = $item->rd_system_id;
                }
                $roadID = DB::table('asset_road_chainage_mappings')
                    ->select('rd_system_id')
                    ->where('remaining_chainage_length', '=', 0)
                    ->where('zone_cd', '=', $zone_cd)
                    ->where('chainage_step_id', '0')
                    ->distinct('rd_system_id')
                    ->get();
                foreach ($roadID as $item) {
                    $roadid[] = $item->rd_system_id;
                }
                $roadDetails = DB::table('asset_road_chainage_mappings as chm')
                    ->select(
                        'chm.rd_system_id',
                        'chm.chainage_from',
                        'chm.chainage_to',
                        'chm.sub_division_cd',
                        'chm.remaining_chainage_length',
                        'chm.chainage_created_at_office_cd',
                        'rd.rd_name',
                        'rd.rd_number',
                        'rd.road_length',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        //saiful -- 21-04-2026 -- start
                        'pp.project_cd'
                        //saiful -- 21-04-2026 -- end
                    )
                    ->leftJoin('asset_road_details as rd', 'chm.rd_system_id', '=', 'rd.rd_system_id')
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'rd.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'rd.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    //saiful --21-04-2026 -- Start
                    ->leftJoin('prt_project_asset_plan as pp', 'rd.asset_plan_id', '=', 'pp.id')
                    //saiful --21-04-2026 -- End
                    ->where('rd.road_type', '=', $roadType)
                    ->whereIn('chm.rd_system_id', $roadid)
                    ->distinct('chm.rd_system_id')
                    ->simplePaginate(200);
            } elseif (($user->user_role_id == '1') || ($user->office_type_cd == 'ADM') || ($user->office_type_cd == 'ECO')) {
                LOG::info("Fetching all the road details for upper level user including ADMIN, ADM and Eng. In Chief");
                $roadDetails = DB::table('asset_road_details as rd')
                    ->select(
                        'rd.*',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        //saiful -- 21-04-2026 -- start
                        'pp.project_cd'
                        //saiful -- 21-04-2026 -- end
                    )
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'rd.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'rd.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    //saiful --21-04-2026 -- Start
                    ->leftJoin('prt_project_asset_plan as pp', 'rd.asset_plan_id', '=', 'pp.id')
                    //saiful --21-04-2026 -- End
                    ->orderBy('created_at', 'desc')
                    ->simplePaginate(200);
            } else {
                LOG::info("Fetching all the road details for HQ users.");
                $roadDetails = DB::table('asset_road_details as rd')
                    ->select(
                        'rd.*',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name',
                        //saiful -- 21-04-2026 -- start
                        'pp.project_cd'
                        //saiful -- 21-04-2026 -- end
                    )
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'rd.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'rd.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    //saiful --21-04-2026 -- Start
                    ->leftJoin('prt_project_asset_plan as pp', 'rd.asset_plan_id', '=', 'pp.id')
                    //saiful --21-04-2026 -- End
                    ->where('rd.road_type', '=', $roadType)
                    ->orderBy('created_at', 'desc')
                    ->simplePaginate(200);
            }
            $districts = AssetMasterLgdDistrict::all();
            $roadTypes = AssetMasterRdType::all();
            $roadOwners = AssetMasterRoadOwner::all();
            $roadCategories = AssetMasterRoadCategory::all();
            $query = DB::getQueryLog();
            Log::info($query);

            return view('road.index', compact(
                'roadDetails',
                'roadDraftDetails',
                'districts',
                'zoneDetails',
                'circleDetails',
                'divisionDetails',
                'subDivisionDetails',
                'roadTypes',
                'roadOwners',
                'roadCategories'
            ));
        } catch (Exception $e) {
            Log::error("Error: ", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    // Show view to insert a new road details
    public function create(Request $request)
    {
        //Saiful -- 17-04-2026 -- Start
        //if Asset is Created from Project
        $project_cd = $request->project_cd ?? null;
        $asset_name = $request->asset_name ?? null;
        $temp_asset_cd = $request->asset_cd ?? null;
        $asset_length = $request->new_asset_length ?? null;
        $asset_plan_id = $request->asset_plan_id ?? null;
        $division_cd = $request->division_cd ?? null;
        $sub_division_cd = $request->sub_division_cd ?? null;
        //Saiful -- 17-04-2026 -- End
        $user = Auth::user();
        $roadDetails = AssetRoadDetail::all();
        $roadDraftDetails = DB::table('asset_road_details_draft as road_details')
            ->select(
                'road_details.rd_system_id',
                'road_details.rd_number',
                'road_details.rd_name',
                'road_details.road_length',
                'road_details.rd_category_cd',
                'road_details.rd_type_cd',
                'road_details.rd_owner_cd',
                'road_details.district_cd',
                'road_details.block_cd',
                'road_details.is_rejected',
                'road_details.reason_of_rejection',
                'road_details.division_cd',
                'category.rd_catg_descr',
                'type.rd_type_descr',
                'owner.owner_name',
                'district.dist_name',
                'block.block_name',
                //saiful 20-04-2026 -- Start
                'pp.project_cd'
                //saiful 20-04-2026 -- End
            )
            ->leftJoin('asset_master_road_category as category', 'road_details.rd_category_cd', '=', 'category.rd_catg_cd')
            ->leftJoin('asset_master_rd_type as type', 'road_details.rd_type_cd', '=', 'type.rd_type_cd')
            ->leftJoin('asset_master_road_owner as owner', 'road_details.rd_owner_cd', '=', 'owner.owner_cd')
            ->leftJoin('asset_master_lgd_district as district', 'road_details.district_cd', '=', 'district.dist_code')
            ->leftJoin('asset_master_block as block', 'road_details.block_cd', '=', 'block.block_cd')
            //Saiful --20-04-2026 -- Start
            ->leftJoin('prt_project_asset_plan as pp', 'pp.id', '=', 'road_details.asset_plan_id')
            //Saiful --20-04-2026 -- End
            ->where('road_details.sent_for_finalize', '=', 'N')
            ->where('road_details.road_created_at_office_cd', '=', auth()->user()->office)
            ->where('road_details.road_created_at_office_type', '=', auth()->user()->office_type_cd)
            ->orderBy('road_details.updated_at', 'desc')
            ->simplePaginate(200);
        $roadTypes = AssetMasterRdType::all();
        $roadOwners = AssetMasterRoadOwner::all();
        $roadCategories = AssetMasterRoadCategory::all();
        $districts = AssetMasterLgdDistrict::all();

        return view('road.store', compact(
            'user',
            'roadTypes',
            'roadOwners',
            'roadCategories',
            'roadDetails',
            'roadDraftDetails',
            'districts',
            //Saiful -- 17-04-2026 -- Start
            //Extra Parameters to be passed for creating assets from Projects
            'asset_name',
            'asset_length',
            'project_cd',
            'asset_plan_id',
            'division_cd',
            'sub_division_cd',
            'temp_asset_cd'
            //Saiful -- 17-04-2026 -- End
        ));
    }

    // store a new road details
    public function store(Request $request)
    {
        $request->validate([
            'road_name' => 'required|string|max:255',
            'road_category' => 'required|string|max:255',
            'road_type' => 'required|string|max:255',
            'road_length' => 'required|numeric',
            'road_owner' => 'required|string|max:255',
            'road_kml_file' => 'required|max:2048',
        ]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('road_kml_file')) {
                $file = $request->file('road_kml_file');
                $extension = $file->getClientOriginalExtension();
                if ($extension != "kml") {
                    return redirect()->route('road.add-road')
                        ->with('failed', 'Failed to add road details. Upload Only KML File!!!!');
                }
            }
            $user = Auth::user();
            $office_cd = $user->office;
            $users_office_type_cd = $user->office_type_cd;
            $division_name = null;
            $zone_cd = null;
            $circle_cd = null;
            $division_cd = null;
            $sub_division_cd = null;
            $rd_catg_descr = null;
            //Saiful -- 17-04-2026 -- Start
            $project_cd = $request->hdn_project_cd ?? null;
            $temp_asset_cd = $request->hdn_temp_asset_cd ?? null;
            $asset_plan_id = $request->hdn_asset_plan_id ?? null;
            $division_cd = $request->hdn_division_cd ?? null;
            $sub_division_cd = $request->hdn_sub_division_cd ?? null;
            Log::info("asset_plan_id:  " . $asset_plan_id);
            //Saiful -- 17-04-2026 -- End

            if ($project_cd == null) {
                $userMappingDetails = DB::table('asset_user_mappings')
                    ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                    ->where('user_id', '=', $user->id)
                    ->get()->first();
                $zone_cd = $userMappingDetails->zone_cd;
                $circle_cd = $userMappingDetails->circle_cd;
                $division_cd = $userMappingDetails->division_cd;
                $sub_division_cd = $userMappingDetails->sub_division_cd;

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
                        $division_cd = $officeDivisionDtls->division_cd;
                        $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                    }
                }
            }

            // $distDetails = DB::table('asset_master_lgd_district')
            //     ->select('dist_name', 'dist_short_code')
            //     ->where('dist_code', $request->district_name)
            //     ->get()->first();

            // $blockName = DB::table('asset_master_block')
            //     ->select('block_name')
            //     ->where('block_cd', '=', $request->block_name)
            //     ->get()->first();
            $categoryDetails = DB::table('asset_master_road_category')
                ->select('rd_catg_short_code', 'rd_catg_descr')
                ->where('rd_catg_cd', $request->road_category)
                ->get()->first();

            if ($categoryDetails)
                $rd_catg_descr = $categoryDetails->rd_catg_descr;

            $divShrtCd = 'NL';
            if ($division_cd !== null) {
                $divDetails = DB::table('asset_master_divisions')
                    ->select('div_short_code', 'division_cd', 'division_name')
                    ->where('division_cd', $division_cd)
                    ->get()->first();
                $divShrtCd = $divDetails->div_short_code;
                $division_name = $divDetails->division_name;
            }

            // creating a unique road system id
            $system_id = DB::table('asset_rd_system_id_running_no')
                ->select('start_no', 'end_no', 'current_running_no', 'expired', 'rd_catg_short_code')
                ->where('user_type', '=', 'P')
                ->where('rd_catg_short_code', $categoryDetails->rd_catg_short_code)
                ->get()->first();

            // Incase running number not found
            if ($system_id == null) {
                return redirect()->route('road.add-road')
                    ->with('failed', 'Current running number not found for the selected road category. Failed to generate the Road ID');
            }

            $current_number = $system_id->current_running_no;
            $curr_sys_id = str_pad($current_number, 4, '0', STR_PAD_LEFT);
            $is_expired = $system_id->expired;

            if (($is_expired == 'N') && ($current_number >= $system_id->start_no) && ($current_number < $system_id->end_no)) {
                // $distShrtCd = $distDetails->dist_short_code;
                $catShrtCd = $categoryDetails->rd_catg_short_code ? $categoryDetails->rd_catg_short_code : 'YY';
                // $road_system_id = $distShrtCd . $divShrtCd . $catShrtCd . $curr_sys_id;
                $road_system_id = $divShrtCd . $catShrtCd . $curr_sys_id;

                // Check if the road_system_id already exists in the database
                $existingRecord = AssetRoadDetail::where('rd_system_id', $road_system_id)->first();

                if ($existingRecord) {
                    return redirect()
                        ->route('road.add-road')
                        ->with('failed', 'Road ID already exist. Failed to add new road details!');
                }

                $roadData = [
                    'rd_system_id' => $road_system_id,
                    'rd_category_cd' => $request->road_category,
                    'rd_number' => $road_system_id,
                    'rd_name' => $request->road_name,
                    'rd_type_cd' => $request->road_type,
                    'road_length' => $request->road_length,
                    'rd_owner_cd' => $request->road_owner,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'road_created_at_office_type' => $users_office_type_cd,
                    'road_created_at_office_cd' => $office_cd,
                    'road_type' => 'SR',
                    'division_name' => $division_name,
                    'division_cd' => $division_cd,
                    'asset_plan_id' => $asset_plan_id,
                ];

                if ($user->department == '3') {
                    $roadData['road_type'] = 'NH';
                }

                //new code start by Pulak 
                $status = AssetRoadDetailsDraft::create($roadData);

                if ($status) {
                    //Saiful -- 18-04-2026 -- Start
                    $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                        ->where('id', $asset_plan_id)
                        ->where('project_cd', $project_cd)
                        ->update([
                            'temp_asset_cd' => $road_system_id,
                            'status' => 1,
                            'updated_at' => now(),
                            'remarks' => 'Draft Road Created on: ' . now() . ", and temp_asset_cd updated from " . $temp_asset_cd . " To " . $road_system_id
                        ]);
                    $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                        ->where('project_cd', $project_cd)
                        ->where('parent_asset_cd', $temp_asset_cd)
                        ->update([
                            'parent_asset_cd' => $road_system_id,
                            'updated_at' => now(),
                            'remarks' => 'Updated on: ' . now() . ", and parent_asset_cd updated from " . $temp_asset_cd . " To " . $road_system_id
                        ]);
                    //Saiful -- 18-04-2026 -- End
                    $chainageData = [
                        'rd_system_id' => $road_system_id,
                        'chainage_from' => '0',
                        'chainage_to' => $request->road_length,
                        'zone_cd' => null,
                        'circle_cd' => null,
                        'division_cd' => null,
                        'sub_division_cd' => null,
                        'remaining_chainage_length' => '0',
                        'chainage_step_id' => '0',
                        'chainage_created_at_office_cd' => $office_cd,
                        'chainage_created_by' => $user->id,
                        'chainage_updated_by' => $user->id,
                        'calculated_length' => $request->road_length,
                    ];

                    if ($asset_plan_id == null) {
                        if ($users_office_type_cd == 'SDO') {
                            $chainageData['rd_segment_id'] = 1;
                            $chainageData['zone_cd'] = $zone_cd;
                            $chainageData['circle_cd'] = $circle_cd;
                            $chainageData['division_cd'] = $division_cd;
                            $chainageData['sub_division_cd'] = $sub_division_cd;
                            $chainageData['chainage_created_at'] = 'SDO';
                        }

                        if ($users_office_type_cd == 'DO') {
                            $chainageData['rd_segment_id'] = 1;
                            $chainageData['zone_cd'] = $zone_cd;
                            $chainageData['circle_cd'] = $circle_cd;
                            $chainageData['division_cd'] = $division_cd;
                            $chainageData['chainage_created_at'] = 'DO';
                        }

                        if ($users_office_type_cd == 'CO') {
                            $chainageData['rd_segment_id'] = 1;
                            $chainageData['zone_cd'] = $zone_cd;
                            $chainageData['circle_cd'] = $circle_cd;
                            $chainageData['chainage_created_at'] = 'CO';
                        }

                        if ($users_office_type_cd == 'ZO') {
                            $chainageData['rd_segment_id'] = 1;
                            $chainageData['zone_cd'] = $zone_cd;
                            $chainageData['chainage_created_at'] = 'ZO';
                        }
                        if ($users_office_type_cd == 'HQ') {
                            $chainageData['rd_segment_id'] = 1;
                            $chainageData['chainage_created_at'] = 'HQ';
                        }
                    } else {
                        //asset created from Project
                        $divDetails = DB::table('asset_master_divisions')
                            ->select('zone_cd', 'circle_cd')
                            ->where('division_cd', $division_cd)
                            ->get()->first();
                        $zone_cd = $divDetails->zone_cd;
                        $circle_cd = $divDetails->circle_cd;
                        $chainageData['rd_segment_id'] = 1;
                        $chainageData['zone_cd'] = $zone_cd;
                        $chainageData['circle_cd'] = $circle_cd;
                        $chainageData['division_cd'] = $division_cd;
                        $chainageData['chainage_created_at'] = 'DO';
                    }

                    $status = AssetRoadChainageMapping::create($chainageData);

                    if ($status) {
                        // updating the current running number
                        $newRunningNumber = $current_number + 1;
                        $updateSystemIdStatus = DB::table('asset_rd_system_id_running_no')
                            ->where('user_type', 'P')
                            ->where('rd_catg_short_code', $categoryDetails->rd_catg_short_code)
                            ->update([
                                'current_running_no' => $newRunningNumber,
                                'expired' => 'N'
                            ]);
                        if ($updateSystemIdStatus) {
                            // Upload the kml file here -- Saiful -- Start
                            $configPath = config('customconfigpath.ROAD_DOCS_PATH');
                            $rootPath = config('filesystems.disks.external.root');
                            if ($request->hasFile('road_kml_file')) {
                                $file = $request->file('road_kml_file');
                                $uniqueFileName = $road_system_id . '.kml';
                                $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/KML_FILES';

                                if (!Storage::exists($folderPath)) {
                                    Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                                }

                                // Store the file using the 'external' disk
                                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                                // Combine the root path and folder path to get the complete file path
                                $completeFilePath = $rootPath . '/' . $filePath;

                                $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $road_system_id . '.geojson';
                                AssetRoadDocumentKmlFileDetails::create([
                                    'rd_system_id' => $road_system_id,
                                    'file_path' => $completeFilePath,
                                    'geojson_file_path' => $geojson_file_path,
                                    'file_type' => 'kml',
                                    'created_by' => auth()->id(),
                                    'updated_by' => auth()->id(),
                                    'created_at_office_cd' => $office_cd,
                                ]);


                                //Call POST API To convert the KML file to Shape File
                                $kml_file_api = config('customconfigpath.KML_FILE_CONVERT_API');
                                // Create a Guzzle HTTP client
                                $client = new Client();

                                // Make a POST request to the API
                                $response = $client->request('POST', $kml_file_api, [
                                    'json' => [
                                        'uid' => auth()->id(),
                                        'kml_file_path' => $completeFilePath,
                                        'road_id' => $road_system_id,
                                        'road_name' => $request->road_name,
                                        'road_length' => $request->road_length,
                                        'road_category' => $rd_catg_descr,
                                        'division_cd' => $division_cd,
                                        'division_name' => $division_name
                                    ]
                                ]);


                                // $json_data= json_decode($response->getBody()->getContents());
                                $json_data = json_decode($response->getBody());
                                log::info($json_data->status);
                                if ($json_data->status == False) {
                                    DB::rollback();
                                    return redirect()->route('road.add-road')
                                        ->with('failed', 'Failed to add road details. Uploaded KML File does not have Roads Layer Structure!!!!')
                                        ->with('kmlFormatIssue', 'KML File Not In Proper Format');
                                } else {
                                    DB::table('asset_road_details_draft')
                                        ->where('rd_system_id', $road_system_id)
                                        ->update([
                                            'lat' => $json_data->center_lat,
                                            'lng' => $json_data->center_lng
                                        ]);
                                }
                            }

                            // Upload the kml file here -- Saiful -- End
                            DB::commit();
                            return redirect()->route('road.add-road')
                                ->with('success', 'New road details added successfully with road system ID :  ' . $road_system_id);
                        } else {
                            DB::rollback();
                            return redirect()->route('road.add-road')
                                ->with('failed', 'Failed to add road details. Failed to update Road ID due to some server error!');
                        }
                    } else {
                        DB::rollback();
                        return redirect()->route('road.add-road')
                            ->with('failed', 'Failed to add road details. Failed to create road chainage data!');
                    }
                } else {
                    DB::rollback();
                    return redirect()->route('road.add-road')
                        ->with('failed', 'Failed to add road details. Some error occured in the serve side!');
                }
            } else {
                // update the current running number status 
                DB::rollback();
                DB::table('asset_rd_system_id_running_no')
                    ->where('user_type', 'P')
                    ->where('rd_catg_short_code', $categoryDetails->rd_catg_short_code)
                    ->update([
                        'expired' => 'Y'
                    ]);
                DB::commit();
                return redirect()->route('road.add-road')
                    ->with('failed', 'Failed to add road details. Current running number exceed the limit and Road ID can not be generated!');
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            // return view('error');
            return redirect()->route('road.add-road')
                ->with('failed', 'Failed to add road details. Some error occured in the serve side!');
        }
    }

    public function update(Request $request)
    {
        try {
            if ((isset($request->id)) and (isset($request->_token))) {
                $road_id = $request->id;
                //saiful -- 24-04-2026 -- Start
                $project_cd = $request->hdn_project_cd ?? null;
                $asset_plan_id = $request->hdn_asset_plan_id ?? null;
                //saiful -- 24-04-2026 -- End
                $deletedBy = Auth::user()->id;
                $deletedTime = now();
                $roadData = AssetRoadDetailsDraft::findOrFail($road_id);
                if ($roadData) {
                    $CopyStatus = DB::table('public.asset_road_details_draft_hist')->insertUsing([
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        'road_created_at_office_type',
                        'road_created_at_office_cd',
                        'road_type',
                        'lng',
                        'lat',
                        'division_name',
                        'division_cd',
                        'sent_for_finalize',
                        'sent_for_finalize_on',
                        'sent_for_finalize_by',
                        'is_rejected',
                        'reason_of_rejection',
                        'date_of_rejection',
                        'rejected_by',
                        'hist_created_by',
                        'hist_created_at',
                        'hist_remarks',
                        //saiful -- 24-04-2026 -- Start
                        'asset_plan_id'
                        //saiful -- 24-04-2026 -- End
                    ], function ($query) use ($road_id, $deletedTime, $deletedBy) {
                        $query->from('public.asset_road_details_draft')
                            ->where('rd_system_id', '=', $road_id)
                            ->select(
                                'rd_system_id',
                                'rd_category_cd',
                                'rd_number',
                                'rd_name',
                                'rd_type_cd',
                                'road_length',
                                'rd_owner_cd',
                                'created_at',
                                'updated_at',
                                'created_by',
                                'updated_by',
                                'road_created_at_office_type',
                                'road_created_at_office_cd',
                                'road_type',
                                'lng',
                                'lat',
                                'division_name',
                                'division_cd',
                                'sent_for_finalize',
                                'sent_for_finalize_on',
                                'sent_for_finalize_by',
                                'is_rejected',
                                'reason_of_rejection',
                                'date_of_rejection',
                                'rejected_by',
                                DB::raw("'$deletedBy' as hist_created_by"),
                                DB::raw("'$deletedTime' as hist_created_at"),
                                DB::raw("'Updated by $deletedBy at $deletedTime' as hist_remarks"),
                                //saiful -- 24-04-2026 -- Start
                                'asset_plan_id'
                                //saiful -- 24-04-2026 -- End
                            );
                    });
                    if ($CopyStatus) {
                        $roadData->rd_category_cd = $request->road_category;
                        $roadData->rd_name = $request->road_name;
                        $roadData->rd_type_cd = $request->road_type;
                        $roadData->road_length = $request->road_length;
                        $roadData->rd_owner_cd = $request->road_owner;
                        $roadData->updated_by = Auth::user()->id;
                        $roadData->updated_at = Carbon::now();
                        //saiful -- 24-04-2026 -- Start
                        $roadData->asset_plan_id = $request->hdn_asset_plan_id ?? null;
                        //saiful -- 24-04-2026 -- End
                        $status = $roadData->save();
                        if ($status) {

                            //saiful 24-04-2026 -- Start
                            //if request is comming from project module then update the prt_project_asset_plan table to set the status to 1 (In Progress) for the respective asset plan id, and also update the temp_asset_cd with the current road system id, so that it can be used in future while creating assets from project module
                            if ($project_cd != null) {
                                $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                                    ->where('id', $asset_plan_id)
                                    ->where('project_cd', $project_cd)
                                    ->update([
                                        'temp_asset_cd' => $request->id,
                                        'status' => 1,
                                        'updated_at' => now(),
                                        'remarks' => 'Draft Road Updated on: ' . now() . ", and temp_asset_cd updated from " . $request->hdn_temp_asset_cd . " To " . $request->id
                                    ]);
                            }
                            //saifu 24-04-2026 --End
                            // Upload the kml file here -- Saiful -- Start
                            if ($request->hasFile('edit_road_kml_file')) {
                                $userMappingDetails = DB::table('asset_user_mappings')
                                    ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                                    ->where('user_id', '=', Auth::user()->id)
                                    ->get()->first();
                                $configPath = config('customconfigpath.ROAD_DOCS_PATH');
                                $rootPath = config('filesystems.disks.external.root');
                                $file = $request->file('edit_road_kml_file');
                                $uniqueFileName = $request->id . '.kml';
                                $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/KML_FILES';

                                if (!Storage::exists($folderPath)) {
                                    Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                                }

                                // Store the file using the 'external' disk
                                $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                                // Combine the root path and folder path to get the complete file path
                                $completeFilePath = $rootPath . '/' . $filePath;

                                $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $request->id . '.geojson';

                                $objExistingKMLData = DB::table('asset_road_document_kml_file_details')
                                    ->select('id')
                                    ->where('rd_system_id', '=', $request->id)
                                    ->get()->first();
                                if (!$objExistingKMLData)
                                    AssetRoadDocumentKmlFileDetails::create([
                                        'rd_system_id' => $request->id,
                                        'file_path' => $completeFilePath,
                                        'geojson_file_path' => $geojson_file_path,
                                        'file_type' => 'kml',
                                        'created_by' => auth()->id(),
                                        'updated_by' => auth()->id(),
                                        'created_at_office_cd' => Auth::user()->office,
                                    ]);
                                else {
                                    $status = DB::table('asset_road_document_kml_file_details')
                                        ->where('rd_system_id', '=', $request->id)
                                        ->update([
                                            'file_path' => $completeFilePath,
                                            'geojson_file_path' => $geojson_file_path,
                                            'file_type' => 'kml',
                                            'created_by' => auth()->id(),
                                            'updated_by' => auth()->id(),
                                            'updated_at' => Carbon::now(),
                                            'created_at_office_cd' => Auth::user()->office
                                        ]);
                                }


                                //Call POST API To convert the KML file to Shape File
                                $kml_file_api = config('customconfigpath.KML_FILE_CONVERT_API');
                                // Create a Guzzle HTTP client
                                $client = new Client();

                                // Make a POST request to the API
                                $response = $client->request('POST', $kml_file_api, [
                                    'json' => [
                                        'uid' => auth()->id(),
                                        'kml_file_path' => $completeFilePath,
                                        'road_id' => $request->id,
                                        'road_name' => $request->road_name,
                                        'road_length' => $request->road_length,
                                        'road_category' => $request->road_category,
                                        'division_cd' => $userMappingDetails->division_cd
                                    ]
                                ]);


                                // $json_data= json_decode($response->getBody()->getContents());
                                $json_data = json_decode($response->getBody());
                                log::info($json_data->status);
                                if ($json_data->status == False) {
                                    LOG::info("Failed to update the road draft data!");
                                    return response()->json([
                                        'status' => 'failed',
                                        'message' => 'Failed to add road details. Uploaded KML File does not have Roads Layer Structure!!!! for Road System ID : ' . $request->id
                                    ]);
                                }
                            }
                            // Upload the kml file here -- Saiful -- End

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Draft data updated successfully!'
                            ]);
                        } else {
                            LOG::info("Failed to update the road draft data!");
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Failed to update the road draft data!'
                            ]);
                        }
                    }
                } else {
                    LOG::info("Failed to copy the record in history table!");
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to copy the record in history table!'
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

    public function destroy(Request $request)
    {
        try {
            if ($request->_token && $request->road_id) {
                $draftRoad = AssetRoadDetailsDraft::findOrFail($request->road_id);
                if ($draftRoad) {
                    // store the record for further uses
                    $roadID = $request->road_id;
                    $deletedBy = Auth::user()->id;
                    $deletedTime = now();
                    $status = DB::table('public.asset_road_details_draft_hist')->insertUsing([
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        'road_created_at_office_type',
                        'road_created_at_office_cd',
                        'road_type',
                        'lng',
                        'lat',
                        'division_name',
                        'division_cd',
                        'sent_for_finalize',
                        'sent_for_finalize_on',
                        'sent_for_finalize_by',
                        'remarks'
                    ], function ($query) use ($roadID, $deletedTime, $deletedBy) {
                        $query->from('public.asset_road_details_draft')
                            ->where('rd_system_id', '=', $roadID)
                            ->select(
                                'rd_system_id',
                                'rd_category_cd',
                                'rd_number',
                                'rd_name',
                                'rd_type_cd',
                                'road_length',
                                'rd_owner_cd',
                                'created_at',
                                'updated_at',
                                'created_by',
                                'updated_by',
                                'road_created_at_office_type',
                                'road_created_at_office_cd',
                                'road_type',
                                'lng',
                                'lat',
                                'division_name',
                                'division_cd',
                                'sent_for_finalize',
                                'sent_for_finalize_on',
                                'sent_for_finalize_by',
                                DB::raw("'Deleted by $deletedBy at $deletedTime' as remarks")
                            );
                    });
                    if ($status) {
                        $draftRoad->delete();
                        return redirect()->back()->with('success', 'Road deleted seccessfully');
                    } else {
                        return redirect()->back()->with('failed', 'Internal Server Error!');
                    }
                } else {
                    return redirect()->back()->with('failed', 'Failed to find the road!');
                }
            } else {
                return redirect()->back()->with('failed', 'Unauthorized access!');
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function getRoadLatLng(Request $request)
    {
        // return $request->header('X-CSRF-TOKEN');
        // return $request;
        DB::enableQueryLog();
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $latLng = DB::table('asset_road_details')
                    ->select('lat', 'lng')
                    ->where('rd_system_id', $request->id)
                    ->get()
                    ->first();

                $query = DB::getQueryLog();
                Log::info($query);
                if ($latLng) {
                    // Convert string values to floats
                    $lat = floatval($latLng->lat);
                    $lng = floatval($latLng->lng);

                    return response()->json([
                        'status' => 'success',
                        'lat' => $lat,
                        'lng' => $lng
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to get Lat Lng!'
                    ], 404);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ], 401);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server error!'
            ], 500);
        }
    }

    public function getRoadAbstract(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('Building controller: ');

            $roadDetails = DB::table('asset_road_details')
                ->select(
                    'asset_road_details.rd_category_cd',
                    'asset_master_road_category.rd_catg_descr',
                    DB::raw('count(asset_road_details.rd_system_id) as road_count'),
                    DB::raw('sum(asset_road_details.road_length) as road_length')
                )
                ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                ->groupBy('asset_road_details.rd_category_cd', 'asset_master_road_category.rd_catg_descr')
                ->get();

            $query = DB::getQueryLog();
            Log::info($query);
            return view('road.abstract.index', compact('roadDetails'));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }


    public function getRoadAbstractByDivision(Request $request)
    {
        // return $request->division;
        try {
            if ((isset($request->division)) and ($request->header('X-CSRF-TOKEN'))) {
                $roadDetails = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_category_cd',
                        'asset_master_road_category.rd_catg_descr',
                        DB::raw('count(asset_road_details.rd_system_id) as road_count'),
                        DB::raw('sum(asset_road_details.road_length) as road_length')
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->where('division_name', 'like', '%' . $request->division . '%')
                    ->groupBy('asset_road_details.rd_category_cd', 'asset_master_road_category.rd_catg_descr')
                    ->get();

                return response()->json([
                    'status' => 'success',
                    'message' => $roadDetails
                ]);
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


    public function getRoadsAssetsAbstractDetails(Request $request)
    {
        DB::enableQueryLog();
        $rd_system_id = $request->rd_id;
        try {
            $totalCulvert = DB::table('asset_road_cdwork_details')
                ->select(DB::raw('COUNT(DISTINCT rd_cdwork_cd) as total_culvert'))
                ->where('rd_system_id', $rd_system_id)
                ->first();
            $totalBridge = DB::table('asset_road_bridge_details')
                ->select(DB::raw('COUNT(DISTINCT rd_bridge_cd) as total_bridge'))
                ->where('rd_system_id', $rd_system_id)
                ->first();

            $totalPCI = DB::table('asset_road_pavement_condition_indexes')
                ->select(DB::raw('COUNT(DISTINCT pci_section_cd) as total_pci'))
                ->where('rd_system_id', $rd_system_id)
                ->first();

            $totalSurfaceTypes = DB::table('asset_road_surface_type_details')
                ->select(DB::raw('COUNT(DISTINCT rd_surface_cd) as total_surface_types'))
                ->where('rd_system_id', $rd_system_id)
                ->first();

            $totalHabitations = DB::table('asset_road_habitation_details')
                ->select(DB::raw('COUNT(DISTINCT habitation_cd) as total_habitation'))
                ->where('rd_system_id', $rd_system_id)
                ->first();
            $query = DB::getQueryLog();
            Log::info($query);
            $result = [
                'totalCulvert' => $totalCulvert,
                'totalBridge' => $totalBridge,
                'totalPCI' => $totalPCI,
                'totalSurfaceTypes' => $totalSurfaceTypes,
                'totalHabitations' => $totalHabitations
            ];
            return $result;
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function deleteRoadFromMap(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info("Inside deleteRoadFromMap Function in RoadController");
            $deletedBy = Auth::user()->id;
            $deletedTime = now();
            $strRoadIds = $request->hdnRoadIdsToDelete;
            $arrayRoadIds = explode(',', $strRoadIds);
            //Copy road data into hist table
            $CopyDataToHistTable = DB::table('public.asset_road_details_hist')->insertUsing([
                'rd_system_id',
                'rd_category_cd',
                'rd_number',
                'rd_name',
                'rd_type_cd',
                'road_length',
                'rd_owner_cd',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
                'road_created_at_office_type',
                'road_created_at_office_cd',
                'road_type',
                'district_name',
                'block_name',
                'lng',
                'lat',
                'division_name',
                'division_cd',
                'block_cd',
                'district_cd',
                'remarks',
                'approved_by',
                'approved_at',
                'is_road_data_merged_to_all_state_file',
                'is_road_data_merged_to_division_file',
                'state_data_merged_on',
                'division_data_merged_on',
                'hist_created_by',
                'hist_created_at'
            ], function ($query) use ($arrayRoadIds, $deletedTime, $deletedBy) {
                $query->from('public.asset_road_details')
                    ->whereIn('rd_system_id', $arrayRoadIds)
                    ->select(
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        'road_created_at_office_type',
                        'road_created_at_office_cd',
                        'road_type',
                        'district_name',
                        'block_name',
                        'lng',
                        'lat',
                        'division_name',
                        'division_cd',
                        'block_cd',
                        'district_cd',
                        DB::raw("'Road deleted from Map by $deletedBy at $deletedTime' as remarks"),
                        'approved_by',
                        'approved_at',
                        'is_road_data_merged_to_all_state_file',
                        'is_road_data_merged_to_division_file',
                        'state_data_merged_on',
                        'division_data_merged_on',
                        DB::raw("'$deletedBy' as hist_created_by"),
                        DB::raw("'$deletedTime' as hist_created_at")
                    );
            });


            $CopyDataToDeleteRequestTable = DB::table('public.asset_road_delete_request_from_map')->insertUsing([
                'rd_system_id',
                'division_cd',
                'requested_by',
                'requested_on',
                'is_raod_deleted_from_map',
                'remarks',
                'created_at',
                'updated_at'

            ], function ($query) use ($arrayRoadIds, $deletedTime, $deletedBy) {
                $query->from('public.asset_road_details')
                    ->whereIn('rd_system_id', $arrayRoadIds)
                    ->select(
                        'rd_system_id',
                        'division_cd',
                        DB::raw("'$deletedBy' as requested_by"),
                        DB::raw("'$deletedTime' as requested_on"),
                        DB::raw("'N' as is_raod_deleted_from_map"),
                        DB::raw("'Requested To delete Road from Map by $deletedBy at $deletedTime' as remarks"),
                        DB::raw("'$deletedTime' as created_at"),
                        DB::raw("'$deletedTime' as updated_at")
                    );
            });


            $deletedCount = 0;
            if ($CopyDataToHistTable) {
                $deletedCount = DB::table('asset_road_details')->whereIn('rd_system_id', $arrayRoadIds)->delete();
                Log::info($deletedCount . "Nos of data got Inserted in History Table");
            } else {

                Log::info($deletedCount . " Nos of data got Inserted in History Table Table");
                $retData = [
                    'message' => $deletedCount . " No.s of Roads Got Deleted Successfully",
                    'status' => 400
                ];
            }


            if ($CopyDataToDeleteRequestTable) {
                Log::info(count($arrayRoadIds) . "Nos of data got Inserted in asset_road_delete_request_from_map Table");
            } else {
                Log::info("0 Nos of data got Inserted in asset_road_delete_request_from_map Table");
            }


            $query = DB::getQueryLog();
            Log::info($query);
            $retData = [
                'message' => $deletedCount . " No.s of Roads Got Deleted Successfully",
                'status' => 200
            ];
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            $retData = [
                'message' => "Sorry Road cannot be Deleted at this Moment, Please Try After Some Time!!!!!",
                'status' => 500
            ];
        }

        return response()->json($retData);
    }

    public function CalculateLatLngByChainage(Request $request)
    {
        try {
            Log::info("Calculating location by chainage.");
            // getting position for the culvert
            $chainge_endpoint = config('customconfigpath.CHAINAGE_POSITION');
            $userid = Auth::user()->id;
            $client = new Client();
            $location = null;
            $response = $client->get($chainge_endpoint, [
                'query' => [
                    'uid' => $userid,
                    'road_id' => $request->road_id,
                    'distance' => $request->chainage,
                ],
            ]);
            // Get the response body as a string
            $responseBody = $response->getBody()->getContents();
            // Decode the JSON response
            $responseData = json_decode($responseBody, true);
            if ($responseData['status']) {
                $location = strval($responseData['coord'][0]) . ',' . strval($responseData['coord'][1]);
            } else {
                $location = "0,0";
            }
            Log::info("Culvert Location: " . $location);
            return response()->json([
                'status' => 200,
                'message' => 'Asset location calculated successfully.',
                'location' => $location,
            ]);
        } catch (Exception $e) {
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Asset location calculation failed!',
                'location' => null,
            ]);
        }
    }
}
