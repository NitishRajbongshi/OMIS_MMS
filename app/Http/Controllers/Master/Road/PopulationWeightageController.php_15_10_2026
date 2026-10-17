<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PopulationWeightageController extends Controller
{
    public function index()
    {
        $populationWeightages = DB::table('public.asset_master_habitation_population_weightage')
            ->orderBy('lower_population')
            ->get();

        return view('master.road.populationWeightage', compact('populationWeightages'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'lower_population' => 'required|integer|min:0',
                'higher_population' => 'nullable|integer|min:0',
                'weightage' => 'required|numeric|min:0|max:100',
                'is_published' => 'required|in:Y,N',
            ]);

            $lower = (int) $request->lower_population;
            $higher = $request->higher_population !== null
                ? (int) $request->higher_population
                : null;

            // Higher value must be greater than lower value
            if ($higher !== null && $higher <= $lower) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Higher population must be greater than lower population.'
                ]);
            }

            // Get the last saved range
            $lastRange = DB::table('public.asset_master_habitation_population_weightage')
                ->orderByDesc('higher_population')
                ->first();

            if ($lastRange) {

                // If previous range has no higher value,
                // no new range can be added after it
                if ($lastRange->higher_population === null) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'No new range can be added because the previous range is already open-ended.'
                    ]);
                }

                $expectedLower = $lastRange->higher_population + 1;

                if ($lower !== $expectedLower) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Lower population must start from ' . $expectedLower . '.'
                    ]);
                }
            } else {

                // First range must start from 0
                if ($lower !== 0) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'The first population range must start from 0.'
                    ]);
                }
            }

            DB::table('public.asset_master_habitation_population_weightage')->insert([
                'lower_population' => $lower,
                'higher_population' => $higher,
                'weightage' => $request->weightage,
                'is_published' => $request->is_published,
                'created_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Population weightage added successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save data.'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'lower_population' => 'required|integer|min:0',
                'higher_population' => 'nullable|integer|min:0',
                'weightage' => 'required|numeric|min:0|max:100',
                'is_published' => 'required|in:Y,N',
            ]);

            $lower = (int) $request->lower_population;
            $higher = $request->higher_population !== null
                ? (int) $request->higher_population
                : null;

            // Higher value must be greater than lower value
            if ($higher !== null && $higher <= $lower) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Higher population must be greater than lower population.'
                ]);
            }

            $table = 'public.asset_master_habitation_population_weightage';

            // Get all other ranges, excluding the current record
            $ranges = DB::table($table)
                ->where('id', '!=', $id)
                ->orderBy('lower_population')
                ->get();

            // Check whether this is the first range
            $previousRange = $ranges
                ->where('higher_population', '<', $lower)
                ->sortByDesc('higher_population')
                ->first();

            if ($previousRange) {

                // Previous range must not be open-ended
                if ($previousRange->higher_population === null) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'No range can be added after an open-ended range.'
                    ]);
                }

                $expectedLower = $previousRange->higher_population + 1;

                if ($lower !== $expectedLower) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Lower population must start from ' . $expectedLower . '.'
                    ]);
                }

            } else {

                // First range must start from 0
                if ($lower !== 0) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'The first population range must start from 0.'
                    ]);
                }
            }

            // Check that the next range starts immediately after this range
            $nextRange = $ranges
                ->filter(function ($range) use ($lower) {
                    return $range->lower_population > $lower;
                })
                ->sortBy('lower_population')
                ->first();

            if ($nextRange) {

                if ($higher === null) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'An open-ended range cannot be saved before another range.'
                    ]);
                }

                $expectedNextLower = $higher + 1;

                if ($nextRange->lower_population != $expectedNextLower) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Higher population must be ' .
                            ($nextRange->lower_population - 1) .
                            ' because the next range starts from ' .
                            $nextRange->lower_population . '.'
                    ]);
                }
            }

            DB::table($table)
                ->where('id', $id)
                ->update([
                    'lower_population' => $lower,
                    'higher_population' => $higher,
                    'weightage' => $request->weightage,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Population weightage updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update failed.'
            ]);
        }
    }

}
