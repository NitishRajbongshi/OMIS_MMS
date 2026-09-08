<?php

namespace App\Http\Controllers\Building;

use App\Http\Controllers\Controller;
use App\Models\Building\AssetBuildingDetail;
use App\Models\Building\AssetBuildingUnitDetail;
use App\Models\Building\Master\AssetMasterBuildingUnitType;
use App\Models\Common\AssetMasterVerificationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $buildingId)
    {
        $building = AssetBuildingDetail::findOrFail($buildingId);

        // Master Data
        $buildingUnitTypes = AssetMasterBuildingUnitType::all();
        $varificationStatuses = AssetMasterVerificationStatus::all();
		
        // calculate total added plinth area of a building
        $totalAddedPlinthArea = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->sum('plinth_area');

        $buildingUnits        = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->orderBy('floor_no')
            ->orderBy('unit_no')
            ->get();

        return view('building.building_unit.index', compact(
            'buildingId',
            'building',
            'buildingUnitTypes',
            'varificationStatuses',
            'buildingUnits',
            'totalAddedPlinthArea'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $buildingId)
    {
		$building = AssetBuildingDetail::findOrFail($buildingId);
        $totalAddedPlinthArea = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->sum('plinth_area');
        $remainingPlinthArea = ($building->plinth_area ?? 0) - $totalAddedPlinthArea;
		
        $validated = $request->validate([
            'unit_type_cd'     => 'required|string|max:50',
            'unit_name'        => 'required|string|max:255',
            'unit_no'          => 'required|string|max:50',
            'floor_no'         => 'required|integer',
            'plinth_area'      => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($remainingPlinthArea) {
                    if ($value > $remainingPlinthArea) {
                        $fail("The plinth area cannot exceed the remaining plinth area of {$remainingPlinthArea} sq.ft.");
                    }
                }
            ],
            'has_water_supply' => 'required|in:Y,N',
            'has_electricity'  => 'required|in:Y,N',
            'has_sanitary'     => 'required|in:Y,N',
            'remarks'          => 'nullable|string|max:1000',
        ]);

        $maxUnits = $building->total_no_of_units ?? 1;

        $existingUnitsCount = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)->count();

        if ($existingUnitsCount >= $maxUnits) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Cannot create more than {$maxUnits} units for this building.");
        }

        AssetBuildingUnitDetail::create([
            ...$validated,
            'building_system_cd' => $buildingId,
            'status_cd'          => '2',
            'created_by'         => Auth::id(),
            'updated_by'         => Auth::id(),
        ]);

        return redirect()
            ->route('building.unit.index', $buildingId)
            ->with('success', 'Building unit added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $buildingId
     * @param  string  $unitId
     * @return \Illuminate\Http\Response
     */
    public function edit(string $buildingId, string $unitId)
    {
        $building = AssetBuildingDetail::findOrFail($buildingId);
        $currentUnit = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        $buildingUnitTypes    = AssetMasterBuildingUnitType::all();
        $varificationStatuses = AssetMasterVerificationStatus::all();
		
		 $totalAddedPlinthArea = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->sum('plinth_area');
			
        return view('building.building_unit.edit', compact(
            'buildingId',
            'building',
            'currentUnit',
            'buildingUnitTypes',
            'varificationStatuses',
            'totalAddedPlinthArea'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $buildingId
     * @param  string $unitId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $buildingId, string $unitId)
    {
        $unit = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->where('unit_id', $unitId)
            ->firstOrFail();
			
		$building = AssetBuildingDetail::findOrFail($buildingId);
        $totalAddedPlinthArea = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->sum('plinth_area');
        $remainingPlinthArea = ($building->plinth_area ?? 0) - ($totalAddedPlinthArea - $unit->plinth_area);

        $validated = $request->validate([
            'unit_type_cd'     => 'required|string|max:50',
            'unit_name'        => 'required|string|max:255',
            'unit_no'          => 'required|string|max:50',
            'floor_no'         => 'required|integer',
            'plinth_area'      => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($remainingPlinthArea) {
                    if ($value > $remainingPlinthArea) {
                        $fail("The plinth area cannot exceed the remaining plinth area of {$remainingPlinthArea} sq.ft.");
                    }
                }
            ],
            'has_water_supply' => 'required|in:Y,N',
            'has_electricity'  => 'required|in:Y,N',
            'has_sanitary'     => 'required|in:Y,N',
            'remarks'          => 'nullable|string|max:1000',
        ]);

        $unit->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('building.unit.index', $buildingId)
            ->with('success', 'Building unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $buildingId
     * @param  string $unitId
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $buildingId, string $unitId)
    {
        $unit = AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->where('unit_id', $unitId)
            ->firstOrFail();

        $unit->delete();

        return redirect()
            ->route('building.unit.index', $buildingId)
            ->with('success', 'Building unit deleted successfully.');
    }
}
