<?php

namespace App\Http\Controllers\Building;

use App\Http\Controllers\Controller;
use App\Models\Building\AssetBuildingUnitDetail;
use App\Models\Building\AssetBuildingUnitOccupancyDetail;
use App\Models\Building\Master\AssetMasterBuildingOccupantGrade;
use App\Models\Common\AssetMasterVerificationStatus;
use App\Models\Road\Master\AssetMasterDeptOfState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuildingOccupancyController extends Controller
{
    /**
     * Shared master data loader.
     */
    private function masterData(): array
    {
        return [
            'departments'          => AssetMasterDeptOfState::orderBy('dept_name')->get(),
            'grades'               => AssetMasterBuildingOccupantGrade::orderBy('grade_descr')->get(),
            'varificationStatuses' => AssetMasterVerificationStatus::all(),
        ];
    }

    /**
     * Resolve and scope the unit to the building, or abort 404.
     */
    private function resolveUnit(string $buildingId, string $unitId): AssetBuildingUnitDetail
    {
        return AssetBuildingUnitDetail::where('building_system_cd', $buildingId)
            ->where('unit_id', $unitId)
            ->firstOrFail();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $buildingId, string $unitId)
    {
        $unit        = $this->resolveUnit($buildingId, $unitId);
        $occupancies = AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
            ->orderByDesc('is_current')
            ->orderByDesc('occupied_from')
            ->get();

        return view('building.unit_allocation.index', array_merge(
            $this->masterData(),
            compact('buildingId', 'unit', 'occupancies')
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
    public function store(Request $request, string $buildingId, string $unitId)
    {
        $this->resolveUnit($buildingId, $unitId); // validates unit belongs to building

        $validated = $request->validate([
            'occupant_name'     => 'required|string|max:100',
            'occupant_dept_cd'  => 'required|integer|exists:asset_master_dept_of_state,id',
            'occupant_grade_cd' => 'required|string',
            'occupied_from'     => 'required|date',
            'occupied_to'       => 'nullable|date|after_or_equal:occupied_from',
            'remarks'           => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $unitId) {
            // Flip all existing records for this unit to not current
            AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
                ->where('is_current', 'Y')
                ->update(['is_current' => 'N']);

            // Create the new current occupancy record
            AssetBuildingUnitOccupancyDetail::create([
                ...$validated,
                'unit_id'    => $unitId,
                'is_current' => 'Y',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('building.occupancy.index', [$buildingId, $unitId])
            ->with('success', 'Occupancy record added successfully.');
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
     * @param  string  $occupancyId
     * @return \Illuminate\Http\Response
     */
    public function edit(string $buildingId, string $unitId, string $occupancyId)
    {
        $unit             = $this->resolveUnit($buildingId, $unitId);
        $currentOccupancy = AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
            ->where('occupancy_id', $occupancyId)
            ->firstOrFail();

        return view('building.unit_allocation.edit', array_merge(
            $this->masterData(),
            compact('buildingId', 'unit', 'currentOccupancy')
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $buildingId
     * @param  string  $unitId
     * @param  string  $occupancyId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $buildingId, string $unitId, string $occupancyId)
    {
        $this->resolveUnit($buildingId, $unitId);

        $occupancy = AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
            ->where('occupancy_id', $occupancyId)
            ->firstOrFail();

        $validated = $request->validate([
            'occupant_name'     => 'required|string|max:100',
            'occupant_dept_cd'  => 'required|integer|exists:asset_master_dept_of_state,id',
            'occupant_grade_cd' => 'required|string',
            'occupied_from'     => 'required|date',
            'occupied_to'       => 'nullable|date|after_or_equal:occupied_from',
            'remarks'           => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $occupancy, $unitId) {
            // If this record is being marked current, demote all others
            if (($validated['is_current'] ?? $occupancy->is_current) === 'Y') {
                AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
                    ->where('occupancy_id', '!=', $occupancy->occupancy_id)
                    ->where('is_current', 'Y')
                    ->update(['is_current' => 'N']);
            }

            $occupancy->update([
                ...$validated,
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('building.occupancy.index', [$buildingId, $unitId])
            ->with('success', 'Occupancy record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $buildingId
     * @param  string  $unitId
     * @param  string  $occupancyId
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $buildingId, string $unitId, string $occupancyId)
    {
        $this->resolveUnit($buildingId, $unitId);

        $occupancy = AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
            ->where('occupancy_id', $occupancyId)
            ->firstOrFail();

        $wasCurrent = $occupancy->is_current === 'Y';
        $occupancy->delete();

        // If deleted record was current, promote the most recent remaining record
        if ($wasCurrent) {
            $latest = AssetBuildingUnitOccupancyDetail::where('unit_id', $unitId)
                ->orderByDesc('occupied_from')
                ->first();

            if ($latest) {
                $latest->update(['is_current' => 'Y']);
            }
        }

        return redirect()
            ->route('building.occupancy.index', [$buildingId, $unitId])
            ->with('success', 'Occupancy record deleted successfully.');
    }
}
