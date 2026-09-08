<?php

namespace App\Http\Controllers\MIS\NationalHighway;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class NationalHighwayMISController extends Controller
{
    public function searchNH(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                DB::enableQueryLog();
                Log::info('MIS controller: ');
                $category = $request->input('category');
                $type = $request->input('type');
                // $owner = $request->input('owner');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_details')
                    ->select('asset_road_details.rd_system_id', 'asset_road_details.rd_number', 'asset_road_details.rd_name', 'asset_road_details.road_length', 'asset_road_details.district_name', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->where('asset_road_details.road_type', '=', 'NH')
                    ->orderBy('created_at', 'desc');
                if ($category != 'null') {
                    $baseQuery->where('asset_road_details.rd_category_cd', $category);
                }

                if ($type != 'null') {
                    $baseQuery->where('asset_road_details.rd_type_cd', $type);
                }

                // if ($owner != 'null') {
                //     $baseQuery->where('asset_road_details.rd_owner_cd', $owner);
                // }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('sub_division_cd', $subDivisionCd);
                    });
                }
                $roadDetails = $baseQuery->get();
                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'NH data fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'NH details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchNHCdWorks(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('Office controller: ');
            if ($request->header('X-CSRF-TOKEN')) {
                $roadID = $request->input('road');
                $type = $request->input('cd_type');
                $apron = $request->input('apron');
                $condition = $request->input('condition');
                $constYear = $request->input('constructionYear');
                $rehabilYear = $request->input('rehabilationYear');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_cdwork_details')
                    ->select('asset_road_cdwork_details.*', 'asset_master_rd_cdworks_type.cdwoerk_descr', 'asset_master_road_condition.rd_condition_descr')
                    ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
                    ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                    ->whereIn('asset_road_cdwork_details.rd_system_id', function ($query) {
                        $query->select(DB::raw('distinct(ard.rd_system_id)'))
                            ->from('asset_road_details as ard')
                            ->where('ard.road_type', 'NH');
                    })
                    ->orderBy('updated_at', 'desc');

                if ($roadID) {
                    $baseQuery->where('asset_road_cdwork_details.rd_system_id', $roadID);
                }
                if ($type != 'null') {
                    $baseQuery->where('asset_road_cdwork_details.culvert_type_cd', $type);
                }
                if ($apron != 'null') {
                    $baseQuery->where('asset_road_cdwork_details.cdwork_has_safety_apron', $apron);
                }
                if ($condition != 'null') {
                    $baseQuery->where('asset_road_cdwork_details.cdwork_condition', $condition);
                }
                if ($constYear != 'null') {
                    $baseQuery->where('asset_road_cdwork_details.year_of_construction', $constYear);
                }
                if ($rehabilYear != 'null') {
                    $baseQuery->where('asset_road_cdwork_details.year_of_rehabilitation', $rehabilYear);
                }

                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_cdwork_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_cdwork_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_cdwork_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_cdwork_details.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.sub_division_cd', $subDivisionCd);
                    });
                }
                $roadDetails = $baseQuery->get();
                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'CD Works details fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'CD Works details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchNHBridge(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                DB::enableQueryLog();
                Log::info('Office controller: ');
                $roadID = $request->input('road');
                $bridgeype = $request->input('bridgeType');
                $constType = $request->input('constructionType');
                $constYear = $request->input('constructionYear');
                $foundType = $request->input('foundationType');
                $pileType = $request->input('pileType');
                $rehabYear = $request->input('rehabilitationYear');
                $sign = $request->input('sign');
                $footpath = $request->input('footPath');
                $condition = $request->input('condition');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_bridge_details')
                    ->select(
                        'asset_road_bridge_details.*',
                        'asset_master_bridge_type.bridge_type_descr',
                        'asset_master_construction_types.construction_type_descr',
                        'asset_master_foundation_types.foundation_descr',
                        'asset_master_abutment_types.abutment_type_descr',
                        'asset_master_super_structure_types.st_type_descr',
                        'asset_master_handrail_types.hand_rail_type_descr',
                        'asset_master_deck_types.deck_type_descr',
                        'asset_master_bearing_types.bearing_type_descr',
                        'asset_master_expansion_joints.expn_joint_descr',
                        'asset_master_road_condition.rd_condition_descr',
                        'asset_master_pile_types.pile_type_descr',
                        'asset_master_well_types.well_type_descr'
                    )
                    ->leftJoin('asset_master_bridge_type', 'asset_road_bridge_details.bridge_type_cd', '=', 'asset_master_bridge_type.bridge_type_cd')
                    ->leftJoin('asset_master_construction_types', 'asset_road_bridge_details.construction_type_cd', '=', 'asset_master_construction_types.construction_type_cd')
                    ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_details.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
                    ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_details.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                    ->leftJoin('asset_master_super_structure_types', 'asset_road_bridge_details.super_structure_type_cd', '=', 'asset_master_super_structure_types.st_type_cd')
                    ->leftJoin('asset_master_handrail_types', 'asset_road_bridge_details.handrail_type_cd', '=', 'asset_master_handrail_types.hand_rail_type_cd')
                    ->leftJoin('asset_master_deck_types', 'asset_road_bridge_details.deck_type_cd', '=', 'asset_master_deck_types.deck_type_cd')
                    ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_details.bearings', '=', 'asset_master_bearing_types.bearing_type_cd')
                    ->leftJoin('asset_master_expansion_joints', 'asset_road_bridge_details.expansion_join_cd', '=', 'asset_master_expansion_joints.expn_joint_cd')
                    ->leftJoin('asset_master_road_condition', 'asset_road_bridge_details.bridge_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                    ->leftJoin('asset_master_pile_types', 'asset_road_bridge_details.pile_type', '=', 'asset_master_pile_types.pile_type_cd')
                    ->leftJoin('asset_master_well_types', 'asset_road_bridge_details.well_type', '=', 'asset_master_well_types.well_type_cd')
                    ->whereIn('asset_road_bridge_details.rd_system_id', function ($query) {
                        $query->select(DB::raw('distinct(ard.rd_system_id)'))
                            ->from('asset_road_details as ard')
                            ->where('ard.road_type', 'NH');
                    })
                    ->orderBy('updated_at', 'desc');

                if ($roadID) {
                    $baseQuery->where('asset_road_bridge_details.rd_system_id', $roadID);
                }

                if ($bridgeype != 'null') {
                    $baseQuery->where('asset_road_bridge_details.bridge_type_cd', $bridgeype);
                }
                if ($constType != 'null') {
                    $baseQuery->where('asset_road_bridge_details.construction_type_cd', $constType);
                }
                if ($constYear != 'null') {
                    $baseQuery->where('asset_road_bridge_details.year_of_construction', $constYear);
                }
                if ($foundType != 'null') {
                    $baseQuery->where('asset_road_bridge_details.foundation_type_cd', $foundType);
                }
                if ($pileType != 'null') {
                    $baseQuery->where('asset_road_bridge_details.pile_type', $pileType);
                }
                if ($rehabYear != 'null') {
                    $baseQuery->where('asset_road_bridge_details.year_of_rehabilitation', $rehabYear);
                }
                if ($sign != 'null') {
                    $baseQuery->where('asset_road_bridge_details.signs', $sign);
                }
                if ($footpath != 'null') {
                    $baseQuery->where('asset_road_bridge_details.footh_path', $footpath);
                }
                if ($condition != 'null') {
                    $baseQuery->where('asset_road_bridge_details.bridge_condition', $condition);
                }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_bridge_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_bridge_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_bridge_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_bridge_details.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.sub_division_cd', $subDivisionCd);
                    });
                }

                $roadDetails = $baseQuery->get();
                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'CD Works details fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'CD Works details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchNHPCI(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $roadID = $request->input('road');
                $traficLight = $request->input('traficLight');
                $pci = $request->input('pci');
                $cracking = $request->input('cracking');
                $revelling = $request->input('revelling');
                $potHoles = $request->input('potHoles');
                $shoving = $request->input('shoving');
                $patching = $request->input('patching');
                $settlement = $request->input('settlement');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_pavement_condition_indexes')
                    ->select('asset_road_pavement_condition_indexes.*')
                    ->whereIn('asset_road_pavement_condition_indexes.rd_system_id', function ($query) {
                        $query->select(DB::raw('distinct(ard.rd_system_id)'))
                            ->from('asset_road_details as ard')
                            ->where('ard.road_type', 'NH');
                    })
                    ->orderBy('updated_at', 'desc');

                if ($roadID) {
                    $baseQuery->where('rd_system_id', $roadID);
                }
                if ($traficLight != 'null') {
                    $baseQuery->where('pv_traffic_light', $traficLight);
                }
                if ($pci != 'null') {
                    $pci_range = explode('-', $pci); // split the value by '-' and create an array
                    $lower = $pci_range[0]; // get the lower bound
                    $upper = $pci_range[1]; // get the upper bound
                    $baseQuery->whereBetween('pci_value', [$lower, $upper]);
                }
                if ($cracking != 'null') {
                    $cracking_range = explode('-', $cracking); // split the value by '-' and create an array
                    $lower = $cracking_range[0]; // get the lower bound
                    $upper = $cracking_range[1]; // get the upper bound
                    $baseQuery->whereBetween('cracking_percent', [$lower, $upper]);
                }
                if ($revelling != 'null') {
                    $revelling_range = explode('-', $revelling); // split the value by '-' and create an array
                    $lower = $revelling_range[0]; // get the lower bound
                    $upper = $revelling_range[1]; // get the upper bound
                    $baseQuery->whereBetween('ravelling_percent', [$lower, $upper]);
                }
                if ($potHoles != 'null') {
                    $potHoles_range = explode('-', $potHoles); // split the value by '-' and create an array
                    $lower = $potHoles_range[0]; // get the lower bound
                    $upper = $potHoles_range[1]; // get the upper bound
                    $baseQuery->whereBetween('pot_holes_percent', [$lower, $upper]);
                }
                if ($shoving != 'null') {
                    $shoving_range = explode('-', $shoving); // split the value by '-' and create an array
                    $lower = $shoving_range[0]; // get the lower bound
                    $upper = $shoving_range[1]; // get the upper bound
                    $baseQuery->whereBetween('shoving_percent', [$lower, $upper]);
                }
                if ($patching != 'null') {
                    $patching_range = explode('-', $patching); // split the value by '-' and create an array
                    $lower = $patching_range[0]; // get the lower bound
                    $upper = $patching_range[1]; // get the upper bound
                    $baseQuery->whereBetween('patching_percent', [$lower, $upper]);
                }
                if ($settlement != 'null') {
                    $settlement_range = explode('-', $settlement); // split the value by '-' and create an array
                    $lower = $settlement_range[0]; // get the lower bound
                    $upper = $settlement_range[1]; // get the upper bound
                    $baseQuery->whereBetween('settlement_depression_percent', [$lower, $upper]);
                }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_pavement_condition_indexes.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_pavement_condition_indexes.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_pavement_condition_indexes.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_pavement_condition_indexes.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.sub_division_cd', $subDivisionCd);
                    });
                }
                $roadDetails = $baseQuery->get();

                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'PCI details fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'PCI details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchNHProtectionWall(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $roadID = $request->input('road');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $wallTypeCd = $request->input('wall_type');
                $superstructureTypeCd = $request->input('superstructure_type');
                $baseQuery = DB::table('asset_protection_wall_details')
                    ->select(
                        'asset_protection_wall_details.*',
                        'asset_master_protection_wall_type.wall_type_descr',
                        'asset_master_protection_wall_structure_type.structure_type_descr',
                    )
                    ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
                    ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
                    ->whereIn('asset_protection_wall_details.rd_system_id', function ($query) {
                        $query->select(DB::raw('distinct(ard.rd_system_id)'))
                            ->from('asset_road_details as ard')
                            ->where('ard.road_type', 'NH');
                    })
                    ->orderBy('updated_at', 'desc');
                if ($roadID) {
                    $baseQuery->where('asset_protection_wall_details.rd_system_id', $roadID);
                }
                if ($wallTypeCd != 'null') {
                    $baseQuery->where('asset_protection_wall_details.wall_type_cd', $wallTypeCd);
                }
                if ($superstructureTypeCd != 'null') {
                    $baseQuery->where('asset_protection_wall_details.structure_type_cd', $superstructureTypeCd);
                }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_protection_wall_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_protection_wall_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_protection_wall_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_protection_wall_details.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.sub_division_cd', $subDivisionCd);
                    });
                }
                $protectionWallDetails = $baseQuery->get();
                Log::info($protectionWallDetails);
                if ($protectionWallDetails) {
                    if ($protectionWallDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $protectionWallDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Protection Wall details fetched successfully!',
                            'result' => $protectionWallDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Protection Wall details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchNHSurfaceType(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $roadID = $request->input('road');
                $type = $request->input('type');
                $condition = $request->input('condition');
                $baseLayerType = $request->input('baseLayerType');
                $subBaseLayerType = $request->input('subBaseLayerType');
                $pavementType = $request->input('pavementType');
                $shoulderType = $request->input('shoulderType');
                $landSlide = $request->input('landSlide');
                $constructionYear = $request->input('constructionYear');
                $maintenanceType = $request->input('maintenanceType');
                $drainage = $request->input('drainage');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_surface_type_details')
                    ->select('asset_road_surface_type_details.*', 'asset_master_surface_type.surface_descr', 'asset_master_road_condition.rd_condition_descr', 'office_details.office_name', 'asset_master_base_layer_types.base_layer_type_descr', 'asset_master_sub_base_layer_types.sub_base_layer_type_descr', 'asset_master_pavement_types.pavement_type_descr', 'asset_master_shoulder_types.shoulder_type_descr', "asset_master_maintenance_types.maintenance_type_descr", 'asset_master_drainage_types.drainage_descr')
                    ->leftJoin('asset_master_surface_type', 'asset_road_surface_type_details.surface_type_cd', '=', 'asset_master_surface_type.surface_cd')
                    ->leftJoin('asset_master_road_condition', 'asset_road_surface_type_details.surface_condition_cd', '=', 'asset_master_road_condition.rd_condition_cd')
                    ->leftJoin('office_details', 'asset_road_surface_type_details.created_at_office_cd', '=', 'office_details.id')
                    ->leftJoin('asset_master_base_layer_types', 'asset_road_surface_type_details.base_layer_type', '=', 'asset_master_base_layer_types.base_layer_type_cd')
                    ->leftJoin('asset_master_sub_base_layer_types', 'asset_road_surface_type_details.sub_base_layer_type', '=', 'asset_master_sub_base_layer_types.sub_base_layer_type_cd')
                    ->leftJoin('asset_master_pavement_types', 'asset_road_surface_type_details.pavement_type', '=', 'asset_master_pavement_types.pavement_type_cd')
                    ->leftJoin('asset_master_shoulder_types', 'asset_road_surface_type_details.shoulder_type', '=', 'asset_master_shoulder_types.shoulder_type_cd')
                    ->leftJoin('asset_master_maintenance_types', 'asset_road_surface_type_details.maintenance_type', '=', 'asset_master_maintenance_types.maintenance_type_cd')
                    ->leftJoin('asset_master_drainage_types', 'asset_road_surface_type_details.drainage', '=', 'asset_master_drainage_types.drainage_cd')
                    ->whereIn('asset_road_surface_type_details.rd_system_id', function ($query) {
                        $query->select(DB::raw('distinct(ard.rd_system_id)'))
                            ->from('asset_road_details as ard')
                            ->where('ard.road_type', 'NH');
                    })
                    ->orderBy('id', 'desc');

                if ($roadID) {
                    $baseQuery->where('asset_road_surface_type_details.rd_system_id', $roadID);
                }

                if ($type != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.surface_type_cd', $type);
                }
                if ($condition != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.surface_condition_cd', $condition);
                }
                if ($baseLayerType != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.base_layer_type', $baseLayerType);
                }
                if ($subBaseLayerType != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.sub_base_layer_type', $subBaseLayerType);
                }
                if ($pavementType != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.pavement_type', $pavementType);
                }
                if ($shoulderType != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.shoulder_type', $shoulderType);
                }
                if ($landSlide != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.land_slide', $landSlide);
                }
                if ($constructionYear != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.construction_year', $constructionYear);
                }
                if ($maintenanceType != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.maintenance_type', $maintenanceType);
                }
                if ($drainage != 'null') {
                    $baseQuery->where('asset_road_surface_type_details.drainage', $drainage);
                }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_surface_type_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_surface_type_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_surface_type_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_surface_type_details.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.sub_division_cd', $subDivisionCd);
                    });
                }
                $roadDetails = $baseQuery->get();

                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'SurfaceType details fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'SurfaceType details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchNHHabitation(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                // $roadID = $request->input('road');
                // $district = $request->input('district');
                $mlaConst = $request->input('mlaConst');
                $block = $request->input('block');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_habitation_details')
                    ->select('asset_road_habitation_details.*', 'asset_master_lgd_district.dist_name as district', 'asset_master_block.block_name as block', 'asset_master_village.village_name as village', 'asset_master_mla_const.const_descr as mla', 'asset_master_mp_const.const_desc as mp')
                    ->leftJoin('asset_master_lgd_district', 'asset_road_habitation_details.district_name', '=', 'asset_master_lgd_district.dist_code')
                    ->leftJoin('asset_master_block', 'asset_road_habitation_details.block_name', '=', 'asset_master_block.block_cd')
                    ->leftJoin('asset_master_village', 'asset_road_habitation_details.village_name', '=', 'asset_master_village.village_code')
                    ->leftJoin('asset_master_mla_const', 'asset_road_habitation_details.mla_constituency', '=', 'asset_master_mla_const.const_cd')
                    ->leftJoin('asset_master_mp_const', 'asset_road_habitation_details.mp_constituency', '=', 'asset_master_mp_const.const_cd')
                    ->orderBy('updated_at', 'desc');

                // if ($roadID) {
                //     $baseQuery->where('asset_road_habitation_details.rd_system_id', $roadID);
                // }
                // if ($district) {
                //     $baseQuery->where('asset_road_habitation_details.district_name', $district);
                // }
                if ($block) {
                    $baseQuery->where('asset_road_habitation_details.block_name', $block);
                }
                if ($mlaConst != 'null') {
                    $baseQuery->where('asset_road_habitation_details.mla_constituency', $mlaConst);
                }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_habitation_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_habitation_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_habitation_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_habitation_details.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('arcm.sub_division_cd', $subDivisionCd);
                    });
                }

                $roadDetails = $baseQuery->get();

                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Habitation details fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Habitation details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }
}
