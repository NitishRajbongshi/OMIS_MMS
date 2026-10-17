<?php

namespace App\Http\Controllers\Road;

use App\Models\AssetMasterBlock;
use App\Models\Road\Master\AssetMasterVillage;
use App\Models\Road\Master\AssetMasterVillagePopulationByCensus;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserMenuDetail;
use App\Models\AssetMasterMpConst;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterMlaConst;
use App\Http\Controllers\Controller;
use App\Models\Road\AssetRoadDetail;
use App\Models\TempRoadModifyDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterLgdDistrict;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\Habitation\AssetRoadHabitationDetail;
use App\Models\Road\Habitation\AssetRoadHabitationDetailsDraft;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RoadHabitationController extends Controller
{
    public function __construct()
    {
        Log::info('Habitation Controller.');
        DB::enableQueryLog();
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
        return redirect()->route('road.add-habitation');
    }

    public function addHabitation(Request $request)
    {
        try {
            $userid = Auth::user()->id;
            $road_system_id = session('system_id');
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');
            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();

            $districts = AssetMasterLgdDistrict::all();
            $blocks = AssetMasterBlock::all();
            $villages = AssetMasterVillage::all();
            $mlaConsts = AssetMasterMlaConst::all();
            $mpConsts = AssetMasterMpConst::all();
            $habitationDetails = DB::table('asset_road_habitation_details_draft')
                ->select('asset_road_habitation_details_draft.*', 'asset_master_lgd_district.dist_name as district', 'asset_master_block.block_name as block', 'asset_master_village.village_name as village', 'asset_master_mla_const.const_descr as mla', 'asset_master_mp_const.const_desc as mp')
                ->leftJoin('asset_master_lgd_district', 'asset_road_habitation_details_draft.district_name', '=', 'asset_master_lgd_district.dist_code')
                ->leftJoin('asset_master_block', 'asset_road_habitation_details_draft.block_name', '=', 'asset_master_block.block_cd')
                ->leftJoin('asset_master_village', 'asset_road_habitation_details_draft.village_name', '=', 'asset_master_village.village_code')
                ->leftJoin('asset_master_mla_const', 'asset_road_habitation_details_draft.mla_constituency', '=', 'asset_master_mla_const.const_cd')
                ->leftJoin('asset_master_mp_const', 'asset_road_habitation_details_draft.mp_constituency', '=', 'asset_master_mp_const.const_cd')
                ->where('rd_system_id', '=', $road_system_id)
                ->where('sent_for_finalize', '=', 'N')
                ->get();

            $populationWeightages = DB::table('asset_master_habitation_population_weightage')
                ->where('is_published', 'Y')
                ->get();

            $habitationFacilities = DB::table('asset_road_habitation_facility_details_draft as hf')
                ->select(
                    'hf.id',
                    'hf.facility_id',
                    'hf.sub_facility_id',
                    'hf.habitation_cd',
                    'f.facility_name',
                    'f.weightage as facility_weightage',
                    'f.caulculation_type_id',
                    'sf.sub_facility_name',
                    'sf.weightage as sub_facility_weightage'
                )
                ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                ->get()
                ->groupBy('habitation_cd');


            $schemes = DB::table('asset_master_habitation_score_schemes')
                ->select('scheme_id', 'population_unit', 'score_per_unit', 'max_score','scheme_name')
                ->get();


            $facilities = DB::table('asset_master_habitation_facilities as f')
                ->where('f.is_published', 'Y')
                ->select('f.id', 'f.facility_name', 'f.scheme_id', 'f.weightage','f.caulculation_type_id')
                ->get();

            $subFacilities = DB::table('asset_master_habitation_sub_facilities as sf')
                ->where('sf.is_published', 'Y')
                ->select('sf.id', 'sf.sub_facility_name', 'sf.facility_id', 'sf.scheme_id', 'sf.weightage')
                ->get();

            Log::info(DB::getQueryLog());
            return view('road.habitation.index', compact(
                'roadChainage',
                'districts',
                'blocks',
                'villages',
                'mlaConsts',
                'mpConsts',
                'facilities',
                'subFacilities',
                'habitationDetails',
                'populationWeightages',
                'habitationFacilities',
                'schemes'
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

    public function storeHabitation(Request $request)
    {
        try {
            $system_id = $request->road_system_id;
            $userid = Auth::user()->id;

            $request->validate([
                'scheme_id' => 'required|integer|exists:asset_master_habitation_score_schemes,scheme_id',
                'district' => 'required',
                'block' => 'required',
                'village' => 'required',
                'mla_constituency_cd' => 'required',
                'mp_constituency_cd' => 'required',
                'total_population' => 'required',
            ]);

            $schemeId = (int) $request->scheme_id;
            $population = (float) $request->total_population;

            $randomNumber = mt_rand(100, 999);
            $currentTime = time();
            $randomCode = $userid . $currentTime . $randomNumber;

            if ($schemeId === 2) {

                // Scheme 2: Calculate score using population unit
                $scheme = DB::table('asset_master_habitation_score_schemes')
                    ->where('scheme_id', $schemeId)
                    ->first();

                if (!$scheme || !is_numeric($scheme->population_unit) || $scheme->population_unit <= 0) {
                    return redirect()->back()
                        ->withInput()
                        ->with('failed', 'Invalid population unit for the selected scheme.')
                        ->with('rd_system_id', $system_id);
                }

                $score = ($population / (float) $scheme->population_unit) * (float) $scheme->score_per_unit;

                // Round .5 and above upward
                $score = (int) round($score, 0, PHP_ROUND_HALF_UP);

                // Apply maximum score
                $populationWeightage = min($score,(float) $scheme->max_score);

            } else {
                $populationWeightage = DB::table('asset_master_habitation_population_weightage')
                    ->where('is_published', 'Y')
                    ->where('lower_population', '<=', $request->total_population)
                    ->where(function ($query) use ($request) {
                        $query->where('higher_population', '>=', $request->total_population)
                            ->orWhereNull('higher_population');
                    })
                    ->value('weightage') ?? 0;
            }

            // Calculate total sub-facility weightage
            $totalSubFacilityWeightage = 0;

            $facilityIds = $request->facility_ids ?? [];
            $subFacilityIds = $request->sub_facility_ids ?? [];
            $selectedSubFacilities = [];

            foreach ($subFacilityIds as $index => $subId) {

                $facilityId = $facilityIds[$index] ?? null;

                if (!$facilityId || !$subId) {
                    continue;
                }

                $selectedSubFacilities[$facilityId][] = $subId;
            }

            foreach ($selectedSubFacilities as $facilityId => $subIds) {

                // Get the selected facility and its calculation type
                $facility = DB::table('asset_master_habitation_facilities')
                    ->where('id', $facilityId)
                    ->where('scheme_id', $schemeId)
                    ->where('is_published', 'Y')
                    ->first();

                if (!$facility) {
                    continue;
                }

                // Get weightages of selected sub-facilities for this facility
                $subFacilityWeightages = DB::table('asset_master_habitation_sub_facilities')
                    ->whereIn('id', $subIds)
                    ->where('facility_id', $facilityId)
                    ->where('scheme_id', $schemeId)
                    ->where('is_published', 'Y')
                    ->pluck('weightage')
                    ->map(function ($weightage) {
                        return (float) ($weightage ?? 0);
                    });

                if ($subFacilityWeightages->isEmpty()) {
                    continue;
                }

                if ((int) $facility->caulculation_type_id === 1) {

                    // Type 1: Add all selected sub-facility weightages
                    $totalSubFacilityWeightage += $subFacilityWeightages->sum();

                } elseif ((int) $facility->caulculation_type_id === 2) {

                    // Type 2: Use only the highest selected sub-facility weightage
                    $totalSubFacilityWeightage += $subFacilityWeightages->max();
                }
            }

            // Final score = population weightage + conditional sub-facility weightage
            $totalWeightageScore = (float) $populationWeightage + $totalSubFacilityWeightage;

            $status = AssetRoadHabitationDetailsDraft::create([
                'habitation_cd' => $randomCode,
                'rd_system_id' => $system_id,
                'district_name' => $request->district,
                'block_name' => $request->block,
                'village_name' => $request->village,
                'mla_constituency' => $request->mla_constituency_cd,
                'mp_constituency' => $request->mp_constituency_cd,
                'total_population' => $request->total_population,
                'scheme_id' => $schemeId,
                'habitation_score' => $totalWeightageScore,
                'created_by' => $userid,
                'updated_by' => $userid,
                'remarks' => $request->remarks,
                'created_at_ofis_cd' => Auth::user()->office
            ]);
            Log::info(DB::getQueryLog());
            if ($status) {
                // Insert Facilities if available
                if ($request->has('sub_facility_ids')) {

                    foreach ($request->sub_facility_ids as $index => $subId) {

                        DB::table('asset_road_habitation_facility_details_draft')->insert([
                            'habitation_cd' => $randomCode,
                            'facility_id' => $request->facility_ids[$index],
                            'sub_facility_id' => $subId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                return redirect()->back()
                    ->with('success', 'Habitation details inserted successfully with code: ' . $randomCode)
                    ->with('rd_system_id', $system_id);
            } else {
                return redirect()->back()
                    ->with('failed', 'Failed to insert habitation data')
                    ->with('rd_system_id', $system_id);
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

    public function destroyHabitation(Request $request)
    {
        DB::transaction(function () use ($request) {
            DB::table('asset_road_habitation_facility_details_draft')
                ->where('habitation_cd', $request->habitation_cd)
                ->delete();
            DB::table('asset_road_habitation_details_draft')
                ->where('habitation_cd', $request->habitation_cd)
                ->delete();

        });

        return back()->with('success', 'Draft Habitation Deleted Successfully');
    }
    public function editHabitation(Request $request)
    {
        DB::transaction(function () use ($request) {
            $habitation = AssetRoadHabitationDetailsDraft::findOrFail($request->habitation_cd);

            $schemeId = (int) $habitation->scheme_id;
            $population = (float) $request->total_population;

            if ($schemeId === 2) {

            $scheme = DB::table('asset_master_habitation_score_schemes')
                ->where('scheme_id', $schemeId)
                ->first();

            if (!$scheme || !is_numeric($scheme->population_unit) || $scheme->population_unit <= 0) {
                    throw new \Exception(
                        'Invalid population unit for the selected scheme.'
                    );
                }

                $score = ($population / (float) $scheme->population_unit) * (float) $scheme->score_per_unit;

                $score = (int) round($score, 0, PHP_ROUND_HALF_UP);

                $populationWeightage = min($score,(float) $scheme->max_score);

            } else {
                $populationWeightage = DB::table('asset_master_habitation_population_weightage')
                    ->where('is_published', 'Y')
                    ->where('lower_population', '<=', $request->total_population)
                    ->where(function ($query) use ($request) {
                        $query->where('higher_population', '>=', $request->total_population)
                            ->orWhereNull('higher_population');
                    })
                    ->value('weightage') ?? 0;
            }


            $totalSubFacilityWeightage = 0;

            $facilityIds = $request->facility_ids ?? [];
            $subFacilityIds = $request->sub_facility_ids ?? [];
            $selectedSubFacilities = [];

            foreach ($subFacilityIds as $index => $subId) {

                $facilityId = $facilityIds[$index] ?? null;

                if (!$facilityId || !$subId) {
                    continue;
                }

                $selectedSubFacilities[$facilityId][] = $subId;
            }

            foreach ($selectedSubFacilities as $facilityId => $subIds) {

                // Get facility for the selected scheme
                $facility = DB::table('asset_master_habitation_facilities')
                    ->where('id', $facilityId)
                    ->where('scheme_id', $schemeId)
                    ->where('is_published', 'Y')
                    ->first();

                if (!$facility) {
                    continue;
                }

                // Get selected sub-facility weightages
                $subFacilityWeightages = DB::table('asset_master_habitation_sub_facilities')
                    ->whereIn('id', $subIds)
                    ->where('facility_id', $facilityId)
                    ->where('scheme_id', $schemeId)
                    ->where('is_published', 'Y')
                    ->pluck('weightage')
                    ->map(function ($weightage) {
                        return (float) ($weightage ?? 0);
                    });

                if ($subFacilityWeightages->isEmpty()) {
                    continue;
                }

                if ((int) $facility->caulculation_type_id === 1) {

                    // Type 1: Sum selected sub-facility weightages
                    $totalSubFacilityWeightage += $subFacilityWeightages->sum();

                } elseif ((int) $facility->caulculation_type_id === 2) {

                    // Type 2: Use the maximum selected sub-facility weightage
                    $totalSubFacilityWeightage += $subFacilityWeightages->max();
                }
            }

            $totalWeightageScore = (float) $populationWeightage + $totalSubFacilityWeightage;

            $habitation->update([
                'district_name' => $request->district,
                // 'block_name' => $request->block,
                // 'village_name' => $request->village,
                'mla_constituency' => $request->mla_constituency_cd,
                'mp_constituency' => $request->mp_constituency_cd,
                'total_population' => $request->total_population,
                'habitation_score' => $totalWeightageScore,
                'remarks' => $request->remarks,
            ]);
            DB::table('asset_road_habitation_facility_details_draft')
                ->where('habitation_cd', $request->habitation_cd)
                ->delete();

            foreach ($request->sub_facility_ids as $key => $sub) {
                DB::table('asset_road_habitation_facility_details_draft')
                    ->insert([
                        'habitation_cd' => $request->habitation_cd,
                        'facility_id' => $request->facility_ids[$key],
                        'sub_facility_id' => $sub
                    ]);
            }
        });

        return back()->with('success', 'Draft Habitation Updated Successfully');
    }
    public function getVillage(Request $request)
    {
        $villages = DB::table('asset_master_village')
            ->select('village_code', 'village_name')
            ->where('block_code', '=', $request->id)
            ->get();

        if ($villages) {
            $response = [
                'status' => 'success',
                'result' => $villages
            ];
        } else {
            $response = [
                'status' => 'failed',
                'result' => null
            ];
        }

        return response()->json($response);
    }

    public function getBlock(Request $request)
    {
        $block = DB::table('asset_master_block')
            ->select('block_cd', 'block_name')
            ->where('district_cd', '=', $request->id)
            ->get();

        if ($block) {
            $response = [
                'status' => 'success',
                'result' => $block
            ];
        } else {
            $response = [
                'status' => 'failed',
                'result' => null
            ];
        }

        return response()->json($response);
    }

    public function getPopulation(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $censusCode = DB::table('asset_master_village')
                    ->select('census2011_village_code')
                    ->where('village_code', '=', $request->id)
                    ->get()
                    ->first();
                if ($censusCode) {
                    $totPopulation = DB::table('asset_master_village_population_by_census')
                        ->select('total_population')
                        ->where('village_code', '=', $censusCode->census2011_village_code)
                        ->get()
                        ->first();

                    if ($totPopulation) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Total population fetch successfully!',
                            'result' => $totPopulation
                        ]);
                    } else {
                        return response()->json([
                            'status' => 204,
                            'message' => 'Total population not available',
                            'result' => null
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Census code is not available',
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
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function getWeightage(Request $request)
    {
        $population = $request->query('population');
        $schemeId = $request->query('scheme_id');

        Log::info('Selected scheme ID', [
            'scheme_id' => $request->scheme_id
        ]);

        Log::info('Selected Population', [
            'population' => $request->population
        ]);

        if (!is_numeric($population) || $population < 0) {
            return response()->json([
                'weightage' => null
            ]);
        }

        if ((int) $schemeId === 2) {

            $scheme = DB::table('asset_master_habitation_score_schemes')
                ->where('scheme_id', $schemeId)
                ->first();

            if (!$scheme || !is_numeric($scheme->population_unit) || $scheme->population_unit <= 0) {
                return response()->json([
                    'weightage' => null
                ]);
            }

            $score = ((float) $population / (float) $scheme->population_unit) * (float) $scheme->score_per_unit;

            // Round .5 and above up to the next integer
            $score = (int) round($score, 0, PHP_ROUND_HALF_UP);

            // Do not exceed the scheme's maximum score
            $weightage = min($score, (float) $scheme->max_score);

            return response()->json([
                'weightage' => $weightage
            ]);
        }

        $range = DB::table('asset_master_habitation_population_weightage')
            ->where('is_published', 'Y')
            ->where('lower_population', '<=', $population)
            ->where(function ($query) use ($population) {
                $query->where('higher_population', '>=', $population)
                    ->orWhereNull('higher_population');
            })
            ->first();

        return response()->json([
            'weightage' => $range ? $range->weightage : null
        ]);
    }

    public function getFacilityWeightage(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|integer|exists:asset_master_habitation_facilities,id',
        ]);

        $facility = DB::table('asset_master_habitation_facilities')
            ->where('id', $request->facility_id)
            ->select('weightage')
            ->first();

        if (!$facility) {
            return response()->json([
                'weightage' => null
            ]);
        }

        return response()->json([
            'weightage' => $facility->weightage
        ]);
    }

    public function getSubFacilityWeightage(Request $request)
    {
        $request->validate([
            'sub_facility_id' => 'required|integer|exists:asset_master_habitation_sub_facilities,id',
        ]);

        $subFacility = DB::table('asset_master_habitation_sub_facilities')
            ->where('id', $request->sub_facility_id)
            ->select('weightage')
            ->first();

        return response()->json([
            'weightage' => $subFacility ? $subFacility->weightage : null
        ]);
    }
}


