<?php

namespace App\Http\Controllers\ViewWings;

use Exception;
use App\Models\AssetMasterZone;
use App\Models\AssetMasterBlock;
use App\Models\AssetMasterCircle;
use App\Models\AssetMasterRdType;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterMlaConst;
use App\Models\AssetMasterPileType;
use Illuminate\Support\Facades\Log;
use App\Models\AssetMasterRoadOwner;
use App\Models\AssetMasterBridgeType;
use App\Models\AssetMasterLgdDistrict;
use App\Models\AssetMasterSubDivision;
use App\Models\AssetMasterSurfaceType;
use App\Models\AssetMasterPavementType;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetMasterShoulderType;
use App\Models\AssetMasterBaseLayerType;
use App\Models\AssetMasterRdCdWorksType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterFoundationType;
use App\Models\AssetMasterConstructionType;
use App\Models\AssetMasterSubBaseLayerType;
use App\Models\Road\Master\AssetMasterDrainageType;
use App\Models\Road\Master\AssetMasterMaintenanceType;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Road\Master\AssetMasterProtectionWallStructureType;
use App\Models\Road\Master\AssetMasterProtectionWallType;

class ViewRoadAndBridgeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function showRoadDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $user = Auth::user();
            $districts = AssetMasterLgdDistrict::all();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();
            $roadCategories = AssetMasterRoadCategory::all();
            $roadConditions = AssetMasterRoadCondition::all();
            $roadTypes = AssetMasterRdType::all();
            $roadOwners = AssetMasterRoadOwner::all();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('wings.road.index', compact('districts', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails', 'roadOwners', 'roadCategories', 'roadConditions', 'roadTypes'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showCdWorkDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $roadDetails = DB::table('asset_road_details')
                ->select('rd_system_id', 'rd_name')
                ->where('road_type', '=', 'SR')
                ->get();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();
            $culvertTypes = AssetMasterRdCdWorksType::all();
            $conditions = AssetMasterRoadCondition::all();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.road.cdworks', compact('roadDetails', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails', 'culvertTypes', 'conditions'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showBridgeDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $roadDetails = DB::table('asset_road_details')
                ->select('rd_system_id', 'rd_name')
                ->where('road_type', '=', 'SR')
                ->get();
            $bridgeTypes = AssetMasterBridgeType::all();
            $constructionTypes = AssetMasterConstructionType::all();
            $foundationTypes = AssetMasterFoundationType::all();
            $pileTypes = AssetMasterPileType::all();
            $conditions = AssetMasterRoadCondition::all();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.road.bridge', compact('roadDetails', 'bridgeTypes', 'constructionTypes', 'foundationTypes', 'pileTypes', 'conditions', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showPCIDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $roadDetails = DB::table('asset_road_details')
                ->select('rd_system_id', 'rd_name')
                ->where('road_type', '=', 'SR')
                ->get();
            $districtDetails = AssetMasterLgdDistrict::all();
            $blockDetails = AssetMasterBlock::all();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.road.pci', compact('roadDetails', 'districtDetails', 'blockDetails', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showProtectionWallDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $roadDetails = DB::table('asset_road_details')
                ->select('rd_system_id', 'rd_name')
                ->where('road_type', '=', 'SR')
                ->get();
            $districtDetails = AssetMasterLgdDistrict::all();
            $blockDetails = AssetMasterBlock::all();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();
            $protectionWallTypes = AssetMasterProtectionWallType::all();
            $superStructureTypes = AssetMasterProtectionWallStructureType::all();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.road.protectionWall', compact('roadDetails', 'districtDetails', 'blockDetails', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails', 'protectionWallTypes', 'superStructureTypes'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showSurfaceTypeDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $roadDetails = DB::table('asset_road_details')
                ->select('rd_system_id', 'rd_name')
                ->where('road_type', '=', 'SR')
                ->get();
            $surfaceTypes = AssetMasterSurfaceType::all();
            $conditions = AssetMasterRoadCondition::all();
            $baseLayerTypes = AssetMasterBaseLayerType::all();
            $subBaseLayerTypes = AssetMasterSubBaseLayerType::all();
            $pavementTypes = AssetMasterPavementType::all();
            $shoulderTypes = AssetMasterShoulderType::all();
            $maintenanceTypes = AssetMasterMaintenanceType::all();
            $drainageTypes = AssetMasterDrainageType::all();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();
            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.road.surfaceType', compact('roadDetails', 'surfaceTypes', 'conditions', 'baseLayerTypes', 'subBaseLayerTypes', 'pavementTypes', 'shoulderTypes', 'maintenanceTypes', 'drainageTypes', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showHabitationDetails()
    {
        try {
            DB::enableQueryLog();
            Log::info('View Road and Bridge controller: ');
            $roadDetails = DB::table('asset_road_details')
                ->select('rd_system_id', 'rd_name')
                ->where('road_type', '=', 'SR')
                ->get();
            $districtDetails = AssetMasterLgdDistrict::all();
            $blockDetails = AssetMasterBlock::all();
            $mlaConsts = AssetMasterMlaConst::all();
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->get();
            $subDivisionDetails = AssetMasterSubDivision::where('dept_cd', '14')->get();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.road.habitation', compact('roadDetails', 'districtDetails', 'blockDetails', 'mlaConsts', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }
}
