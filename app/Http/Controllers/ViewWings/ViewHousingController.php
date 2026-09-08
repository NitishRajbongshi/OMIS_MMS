<?php

namespace App\Http\Controllers\ViewWings;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDivision;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterSubDivision;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\Building\Master\AssetMasterBuildingCategory;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Road\Master\AssetMasterDeptOfState;

class ViewHousingController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function showBuilding()
    {
        try {
            DB::enableQueryLog();
            Log::info('View wing controller: ');
            $buildingClasses = AssetMasterBuildingClass::all();
            $buildingTypes = AssetMasterBuildingType::all();
            $buildingCategories = AssetMasterBuildingCategory::all();
            $divisions = AssetMasterDivision::where('dept_cd', '6')->get();
            $subDivisions = AssetMasterSubDivision::where('dept_cd', '6')->get();
            $departmentDetails = AssetMasterDeptOfState::all();
            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.building.index', compact('buildingClasses', 'buildingTypes', 'buildingCategories', 'divisions', 'subDivisions', 'departmentDetails'));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
}
