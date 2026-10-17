<?php

namespace App\Http\Controllers\MMIS;

use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Carbon\Carbon;				  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeightageController extends Controller
{

    public function index(Request $request)
    {
        try {

           $departments = DB::table('department_details')
            ->select('id', 'department_name')
            ->whereNotIn('id', [16, 18, 19, 20])
            ->orderBy('department_name')
            ->get();

            return view('mmis.parameter-weightage.index', compact('departments'));

			} catch (Exception $e) {

            Log::error('WeightageController@index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return view('error');
        }
    }

    public function parammaster(Request $request)
    {
        try {

           $parameters = DB::table('maintenance.master_mtn_parameter_master')
            ->select('id', 'parameter_code','parameter_name','description')
            ->get();

            return view('mmis.parameter-weightage.master', compact('parameters'));

        } catch (Exception $e) {

            Log::error('WeightageController@index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return view('error');
        }
    }

    public function getAssetList(Request $request)
    {
        $department = $request->department;

        if ($department == 3) {
            $department = 14;
        }

        $query = DB::table('asset_master_road_sub_assets')
            ->select('sub_asset_cd', 'sub_assets_descr')
            ->where('dept_cd', $department);

        if ($department == 14) {
            $query->whereNotIn('sub_asset_cd', [11, 2, 4, 3]);
        }

        if ($department == 3) {
            $query->whereNotIn('sub_asset_cd', [8]);
        }

        $assets = $query->get();

        return $assets;
    }

    public function getParameters(Request $request)
    {
        $department = $request->department;
        $asset = $request->asset;

        if ($department === null || $department === '' || $asset === null || $asset === '') {
            return response()->json([]);
        }

        $parameters = DB::table('maintenance.master_set_of_mtn_parameters_dept_wise as parameter_set')
            ->join(
                'maintenance.master_mtn_parameter_master as parameter',
                'parameter.id',
                '=',
                'parameter_set.parameter_id'
            )
            ->leftJoin(
                'maintenance.master_each_mtn_parameters_weightage as weightage',
                function ($join) {
                    $join->on('weightage.parameter_id', '=', 'parameter_set.parameter_id')
                        ->on('weightage.mtn_parameter_set_id', '=', 'parameter_set.id');
                }
            )
            ->where('parameter_set.dept_cd', $department)
            ->where('parameter_set.asset_type_cd', $asset)
            ->select(
                'parameter_set.id as parameter_set_id',
                'parameter.id as parameter_id',
                'parameter.parameter_name',
                'weightage.weightage'
            )
            ->orderBy('parameter.parameter_name')
            ->get();

        return response()->json($parameters);
    }

    public function saveWeightage(Request $request)
    {
        try {

            $weightages = $request->weightages;
            $histRemark = $request->hist_remark;

            foreach ($weightages as $weightage) {

                $existing = DB::table('maintenance.master_each_mtn_parameters_weightage')
                    ->where('mtn_parameter_set_id', $weightage['parameter_set_id'])
                    ->where('parameter_id', $weightage['parameter_id'])
                    ->first();

                if ($existing) {

                    DB::table('maintenance.master_each_mtn_parameters_weightage_hist')
                        ->insert([
                            'id' => $existing->id,
                            'mtn_parameter_set_id' => $existing->mtn_parameter_set_id,
                            'parameter_id' => $existing->parameter_id,
                            'weightage' => $existing->weightage,
                            'hist_remark' => $histRemark,
                            'hist_created_by' => Auth::id(),
                            'hist_created_at' => now(),
                        ]);
                }

                DB::table('maintenance.master_each_mtn_parameters_weightage')
                    ->updateOrInsert(
                        [
                            'mtn_parameter_set_id' => $weightage['parameter_set_id'],
                            'parameter_id' => $weightage['parameter_id'],
                        ],
                        [
                            'weightage' => $weightage['weightage'],
                            'updated_at' => now(),
                            'updated_by' => Auth::id(),
                            'created_by' => Auth::id(),
                        ]
                    );
            }

            return response()->json([
                'status' => true,
                'message' => 'Weightage saved successfully.'
            ]);

        } catch (Exception $e) {

            Log::error('WeightageController@saveWeightage: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save weightage.'
            ], 500);
        }
    }

	public function saveParameter(Request $request)
    {
        try {
            $request->validate([
                'param_name' => 'required|string',
                'param_code' => 'required|string',
                'param_desc' => 'required|string',
            ]);

            $exists = DB::table('maintenance.master_mtn_parameter_master')
                ->whereRaw('LOWER(parameter_code) = LOWER(?)', [$request->param_code])
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Parameter code already exists.'
                ], 422);
            }

            DB::table('maintenance.master_mtn_parameter_master')->insert([
                'parameter_name' => $request->param_name,
                'parameter_code' => $request->param_code,
                'description' => $request->param_desc,
                'parameter_type' =>'RANGE',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Parameter saved successfully.!'
            ]);

        } catch (Exception $e) {
            Log::error('WeightageController@saveParameter: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save parameter.'
            ], 500);
        }
    }

    public function mapping(Request $request)
    {
        try {

            $departments = DB::table('department_details')
                ->select('id', 'department_name')
                ->whereNotIn('id', [16, 18, 19, 20])
                ->orderBy('department_name')
                ->get();

            return view(
                'mmis.parameter-weightage.mapping',
                compact('departments')
            );

        } catch (Exception $e) {

            Log::error('WeightageController@mapping: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return view('error');
        }
    }

	public function updateParameter(Request $request)
    {
        try {
            DB::table('maintenance.master_mtn_parameter_master')
                ->where('id', $request->id)
                ->update([
                    'parameter_name' => $request->param_name,
                    'description' => $request->param_desc,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Parameter updated!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }

    public function getMappingParameters(Request $request)
    {
        $department = $request->department;
        $asset = $request->asset;

        if ($department === null || $department === '' ||
            $asset === null || $asset === '') {
            return response()->json([]);
        }

        $parameters = DB::table('maintenance.master_mtn_parameter_master as parameter')
            ->leftJoin(
                'maintenance.master_set_of_mtn_parameters_dept_wise as mapping',
                function ($join) use ($department, $asset) {

                    $join->on(
                        'mapping.parameter_id',
                        '=',
                        'parameter.id'
                    )
                    ->where('mapping.dept_cd', '=', $department)
                    ->where('mapping.asset_type_cd', '=', $asset);
                }
            )
            ->select(
                'parameter.id as parameter_id',
                'parameter.parameter_name',
                'mapping.id as mapping_id'
            )
            ->orderBy('parameter.parameter_name')
            ->get();

        return response()->json($parameters);
    }

    public function saveMapping(Request $request)
    {
        try {

            $department = $request->department;
            $asset = $request->asset;
            $parameterIds = $request->parameter_ids ?? [];

            $existingMappings = DB::table(
                'maintenance.master_set_of_mtn_parameters_dept_wise'
            )
            ->where('dept_cd', $department)
            ->where('asset_type_cd', $asset)
            ->get();

            // Check removed mappings before making any changes
            foreach ($existingMappings as $existing) {

                if (!in_array($existing->parameter_id, $parameterIds)) {

                    $weightage = DB::table(
                        'maintenance.master_each_mtn_parameters_weightage'
                    )
                    ->where('mtn_parameter_set_id', $existing->id)
                    ->first();

                    if ($weightage && $weightage->weightage != 0) {

                        $parameter = DB::table(
                            'maintenance.master_mtn_parameter_master'
                        )
                        ->where('id', $existing->parameter_id)
                        ->value('parameter_name');

                        return response()->json([
                            'status' => false,
                            'message' => 'Cannot remove "' . $parameter .
                                '" because it has weightage value ' .
                                $weightage->weightage .
                                '. Please make the weightage 0 before removing this parameter.'
                        ]);
                    }
                }
            }

            DB::transaction(function () use (
                $department,
                $asset,
                $parameterIds,
                $existingMappings
            ) {

                foreach ($parameterIds as $parameterId) {

                    $existing = $existingMappings->firstWhere(
                        'parameter_id',
                        $parameterId
                    );

                    if ($existing) {

                        DB::table(
                            'maintenance.master_set_of_mtn_parameters_dept_wise'
                        )
                        ->where('id', $existing->id)
                        ->update([
                            'dept_cd' => $department,
                            'asset_type_cd' => $asset,
                            'parameter_id' => $parameterId,
                        ]);

                    } else {

                        // Insert new mapping
                        $mappingId = DB::table(
                            'maintenance.master_set_of_mtn_parameters_dept_wise'
                        )->insertGetId([
                            'dept_cd' => $department,
                            'asset_type_cd' => $asset,
                            'parameter_id' => $parameterId,
                        ]);

                        // Check if other mappings already have weightage
                        $hasWeightage = DB::table(
                            'maintenance.master_each_mtn_parameters_weightage as weightage'
                        )
                        ->join(
                            'maintenance.master_set_of_mtn_parameters_dept_wise as mapping',
                            'mapping.id',
                            '=',
                            'weightage.mtn_parameter_set_id'
                        )
                        ->where('mapping.dept_cd', $department)
                        ->where('mapping.asset_type_cd', $asset)
                        ->exists();

                        // Only create weightage if other mappings already have weightage
                        if ($hasWeightage) {

                            DB::table(
                                'maintenance.master_each_mtn_parameters_weightage'
                            )->insert([
                                'mtn_parameter_set_id' => $mappingId,
                                'parameter_id' => $parameterId,
                                'weightage' => 0,
                                'created_by' => Auth::id(),
                                'created_at' => now(),
                            ]);
                        }
                    }
                }

                // Handle removed mappings
                foreach ($existingMappings as $existing) {

                    if (!in_array($existing->parameter_id, $parameterIds)) {

                        $weightage = DB::table(
                            'maintenance.master_each_mtn_parameters_weightage'
                        )
                        ->where('mtn_parameter_set_id', $existing->id)
                        ->first();

                        // Move weightage to history if weightage = 0
                        if ($weightage) {

                            DB::table(
                                'maintenance.master_each_mtn_parameters_weightage_hist'
                            )->insert([
                                'id' => $weightage->id,
                                'mtn_parameter_set_id' => $weightage->mtn_parameter_set_id,
                                'parameter_id' => $weightage->parameter_id,
                                'hist_remark' => 'Deleted due to parameter mapping removal',
                                'weightage' => $weightage->weightage,
                                'hist_created_by' => Auth::id(),
                                'hist_created_at' => now(),
                            ]);

                            // Delete weightage
                            DB::table(
                                'maintenance.master_each_mtn_parameters_weightage'
                            )
                            ->where('id', $weightage->id)
                            ->delete();
                        }

                        // Move mapping to history
                        DB::table(
                            'maintenance.master_set_of_mtn_parameters_dept_wise_hist'
                        )->insert([
                            'id' => $existing->id,
                            'dept_cd' => $existing->dept_cd,
                            'asset_type_cd' => $existing->asset_type_cd,
                            'parameter_id' => $existing->parameter_id,
                            'hist_created_by' => Auth::id(),
                            'hist_created_at' => now(),
                        ]);

                        // Delete mapping
                        DB::table(
                            'maintenance.master_set_of_mtn_parameters_dept_wise'
                        )
                        ->where('id', $existing->id)
                        ->delete();
                    }
                }

            });

            return response()->json([
                'status' => true,
                'message' => 'Parameter mapping saved successfully.'
            ]);

        } catch (Exception $e) {

            Log::error('WeightageController@saveMapping: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save parameter mapping.'
            ], 500);
        }
    }
}
