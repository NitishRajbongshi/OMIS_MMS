<?php

namespace App\Http\Controllers\AssetCriticality;

use App\Http\Controllers\Controller;
use App\Http\Requests\CriticalityIndex\StoreCriticalityIndexRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CriticalityIndexController extends Controller
{
    public function index()
    {
        $assetTypes = collect(config('asset_schema_lists'))
            ->map(function ($asset, $code) {
                return [
                    'code' => (string) $code,
                    'name' => $asset['name'],
                ];
            })
            ->values();
        return view('criticality.index', compact('assetTypes'));
    }

    public function store(StoreCriticalityIndexRequest $request)
    {

        $assetType = $request->input('asset_type');
        $assetId = $request->input('asset_id');
        $criticalityIndex = $request->input('criticality_index');
        /*
    |--------------------------------------------------------------------------
    | 1. Resolve asset type configuration
    |--------------------------------------------------------------------------
    */

        $config = config("asset_schema_lists.$assetType");

        if (!$config) {
            throw ValidationException::withMessages([
                'asset_type' => 'Invalid asset type selected.',
            ]);
        }

        $parameter = DB::table(
            'maintenance.master_mtn_parameter_master as p'
        )
            ->join(
                'maintenance.master_mtn_parameter_range as r',
                'r.parameter_id',
                '=',
                'p.id'
            )
            ->where(
                'p.parameter_code',
                'CRITICALITY_INDEX'
            )
            ->where(
                'p.parameter_type',
                'RANGE'
            )
            ->where(
                'p.status',
                'ACTIVE'
            )
            ->where(
                'r.status',
                'ACTIVE'
            )
            ->select([
                'p.id',
                'p.parameter_code',
                'p.parameter_name',
                'r.lower_range',
                'r.upper_range',
            ])
            ->first();

        if (!$parameter) {
            throw ValidationException::withMessages([
                'criticality_index' =>
                'Criticality Index parameter configuration not found.',
            ]);
        }

        if (
            $criticalityIndex < $parameter->lower_range ||
            $criticalityIndex > $parameter->upper_range
        ) {
            throw ValidationException::withMessages([
                'criticality_index' =>
                "Criticality Index must be between " .
                    "{$parameter->lower_range} and " .
                    "{$parameter->upper_range}.",
            ]);
        }

        $assetExists = DB::table($config['table'])
            ->where(
                $config['id_field'],
                $assetId
            )
            ->exists();

        if (!$assetExists) {
            throw ValidationException::withMessages([
                'asset_id' => 'Selected asset does not exist.',
            ]);
        }

        $alreadyExists = DB::table(
            'maintenance.master_asset_criticality_indexes'
        )
            ->where(
                'asset_type',
                $assetType
            )
            ->where(
                'asset_id',
                $assetId
            )
            ->exists();

        if ($alreadyExists) {
            throw ValidationException::withMessages([
                'asset_id' =>
                'Criticality Index has already been assigned to this asset.',
            ]);
        }

        DB::table(
            'maintenance.master_asset_criticality_indexes'
        )->insert([
            'asset_type' => $assetType,
            'asset_id' => $assetId,
            'criticality_index' => $criticalityIndex,
            'created_at' => now(),
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
            'Criticality Index assigned successfully.',
        ]);
    }

    public function assets(string $assetType)
    {
        $config = config("asset_schema_lists.$assetType");

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid asset type.',
            ], 422);
        }

        $assets = DB::table($config['table'] . ' as a')
            ->leftJoin(
                'maintenance.master_asset_criticality_indexes as ci',
                function ($join) use ($assetType, $config) {
                    $join->on(
                        'ci.asset_id',
                        '=',
                        'a.' . $config['id_field']
                    )->where(
                        'ci.asset_type',
                        '=',
                        (string) $assetType
                    );
                }
            )
            ->whereNull('ci.id')
            ->select([
                DB::raw(
                    'a.' . $config['id_field'] . ' as asset_id'
                ),
                DB::raw(
                    'a.' . $config['name_field'] . ' as asset_name'
                ),
            ])
            ->orderBy('a.' . $config['id_field'])
            ->get();

        $assets = $assets->map(function ($asset) use ($config) {

            $label = $config['display_format'] ?? '{id} — {name}';

            $label = str_replace(
                ['{id}', '{name}'],
                [$asset->asset_id, $asset->asset_name],
                $label
            );

            return [
                'asset_id' => $asset->asset_id,
                'asset_name' => $asset->asset_name,
                'asset_label' => $label,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $assets,
        ]);
    }

    public function parameter()
    {
        $parameter = DB::table(
            'maintenance.master_mtn_parameter_master as p'
        )
            ->join(
                'maintenance.master_mtn_parameter_range as r',
                'r.parameter_id',
                '=',
                'p.id'
            )
            ->where('p.parameter_code', 'CRITICALITY_INDEX')
            ->where('p.parameter_type', 'RANGE')
            ->where('p.status', 'ACTIVE')
            ->where('r.status', 'ACTIVE')
            ->select([
                'p.id',
                'p.parameter_code',
                'p.parameter_name',
                'r.lower_range',
                'r.upper_range',
            ])
            ->first();

        if (!$parameter) {
            return response()->json([
                'success' => false,
                'message' => 'Criticality Index parameter configuration not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'parameter_code' => $parameter->parameter_code,
                'parameter_name' => $parameter->parameter_name,
                'lower_range' => $parameter->lower_range,
                'upper_range' => $parameter->upper_range,
            ],
        ]);
    }
}
