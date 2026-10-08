<?php

namespace App\Http\Controllers\AssetCriticality;

use App\Http\Controllers\Controller;
use App\Models\AssetMasterCircle;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterLgdDistrict;
use App\Models\AssetMasterRdType;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterRoadOwner;
use App\Models\AssetMasterSubDivision;
use App\Models\AssetMasterZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriticalityReportController extends Controller
{
    public function roadCriticalityReport()
    {
        DB::enableQueryLog();
        Log::info('View Road and Bridge controller: ');
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
        return view('criticalityReport.roadCriticalityReport', compact('districts', 'zoneDetails', 'circleDetails', 'divisionDetails', 'subDivisionDetails', 'roadOwners', 'roadCategories', 'roadConditions', 'roadTypes'));
    }
}
