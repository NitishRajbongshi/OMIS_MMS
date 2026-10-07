<?php

namespace App\Http\Controllers\AssetCriticality;

use App\Http\Controllers\Controller;
use App\Http\Requests\CriticalityIndex\StoreCriticalityIndexRequest;
use App\Http\Requests\CriticalityIndex\UpdateCriticalityIndexRequest;
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

    public function update(UpdateCriticalityIndexRequest $request, int $id)
    {
        $criticalityIndex = $request->input('criticality_index');
        $record = DB::table(
            'maintenance.master_asset_criticality_indexes'
        )
            ->where('id', $id)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Criticality Index record not found.',
            ], 404);
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
            return response()->json([
                'success' => false,
                'message' =>
                'Criticality Index parameter configuration not found.',
            ], 422);
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

        DB::table(
            'maintenance.master_asset_criticality_indexes'
        )
            ->where('id', $id)
            ->update([
                'criticality_index' => $criticalityIndex,
                'updated_at' => now(),
                'updated_by' => auth()->id(),
            ]);

        return response()->json([
            'success' => true,
            'message' =>
            'Criticality Index updated successfully.',
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

    public function list(Request $request)
    {
        $assetType = $request->input('asset_type');
        $search = trim($request->input('search', ''));

        $perPage = min(
            max((int) $request->input('per_page', 10), 1),
            100
        );

        if ($assetType !== null && $assetType !== '') {

            $config = config("asset_schema_lists.$assetType");

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid asset type.',
                ], 422);
            }

            $query = DB::table(
                'maintenance.master_asset_criticality_indexes as ci'
            )
                ->join(
                    $config['table'] . ' as a',
                    'a.' . $config['id_field'],
                    '=',
                    'ci.asset_id'
                )
                ->where(
                    'ci.asset_type',
                    (string) $assetType
                );

            if ($search !== '') {

                $idField = 'a.' . $config['id_field'];
                $nameField = 'a.' . $config['name_field'];

                $query->where(function ($q) use (
                    $idField,
                    $nameField,
                    $search
                ) {

                    $searchValue = "%{$search}%";

                    $q->whereRaw(
                        "CAST($idField AS TEXT) ILIKE ?",
                        [$searchValue]
                    );

                    $q->orWhereRaw(
                        "CAST($nameField AS TEXT) ILIKE ?",
                        [$searchValue]
                    );
                });
            }

            $records = $query
                ->select([
                    'ci.id',
                    'ci.asset_type',
                    'ci.asset_id',
                    'ci.criticality_index',
                    'ci.created_at',
                    'ci.created_by',

                    DB::raw(
                        'a.' .
                            $config['name_field'] .
                            ' as asset_name'
                    ),
                ])
                ->orderByDesc('ci.id')
                ->paginate($perPage);

            $data = collect($records->items())->map(function ($record) use ($config) {
                $assetName = $record->asset_name ?? '-';
                $assetLabel =
                    $config['display_format']
                    ?? '{id} — {name}';

                $assetLabel = str_replace(
                    ['{id}', '{name}'],
                    [
                        $record->asset_id,
                        $assetName,
                    ],
                    $assetLabel
                );

                return [
                    'id' => $record->id,

                    'asset_type' =>
                    $record->asset_type,

                    'asset_type_name' =>
                    $config['name'],

                    'asset_id' =>
                    $record->asset_id,

                    'asset_name' =>
                    $assetName,

                    'asset_label' =>
                    $assetLabel,

                    'criticality_index' =>
                    $record->criticality_index,

                    'created_at' =>
                    $record->created_at,

                    'created_by' =>
                    $record->created_by,
                ];
            })
                ->values();


            return response()->json([
                'success' => true,

                'data' => $data,

                'pagination' => [
                    'current_page' =>
                    $records->currentPage(),

                    'last_page' =>
                    $records->lastPage(),

                    'per_page' =>
                    $records->perPage(),

                    'total' =>
                    $records->total(),
                ],
            ]);
        }

        $allRecords = DB::table(
            'maintenance.master_asset_criticality_indexes'
        )
            ->orderByDesc('id')
            ->get();

        $filteredRecords = $allRecords
            ->map(function ($record) {

                $config = config(
                    "asset_schema_lists.{$record->asset_type}"
                );

                if (!$config) {

                    return null;
                }


                $asset = DB::table($config['table'])
                    ->where(
                        $config['id_field'],
                        $record->asset_id
                    )
                    ->select([
                        $config['id_field'] . ' as asset_id',
                        $config['name_field'] . ' as asset_name',
                    ])
                    ->first();


                if (!$asset) {

                    return null;
                }


                $assetName =
                    $asset->asset_name ?? '-';


                $assetLabel =
                    $config['display_format']
                    ?? '{id} — {name}';


                $assetLabel = str_replace(
                    ['{id}', '{name}'],
                    [
                        $record->asset_id,
                        $assetName,
                    ],
                    $assetLabel
                );


                return [
                    'id' =>
                    $record->id,

                    'asset_type' =>
                    $record->asset_type,

                    'asset_type_name' =>
                    $config['name'],

                    'asset_id' =>
                    $record->asset_id,

                    'asset_name' =>
                    $assetName,

                    'asset_label' =>
                    $assetLabel,

                    'criticality_index' =>
                    $record->criticality_index,

                    'created_at' =>
                    $record->created_at,

                    'created_by' =>
                    $record->created_by,
                ];
            })
            ->filter();

        if ($search !== '') {

            $searchLower = strtolower($search);

            $filteredRecords = $filteredRecords
                ->filter(function ($record) use ($searchLower) {

                    return str_contains(
                        strtolower($record['asset_id']),
                        $searchLower
                    )
                        ||
                        str_contains(
                            strtolower($record['asset_name']),
                            $searchLower
                        )
                        ||
                        str_contains(
                            strtolower($record['asset_label']),
                            $searchLower
                        )
                        ||
                        str_contains(
                            strtolower($record['asset_type_name']),
                            $searchLower
                        );
                });
        }

        $currentPage = max(
            (int) $request->input('page', 1),
            1
        );

        $total = $filteredRecords->count();

        $pagedRecords = $filteredRecords
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();


        $lastPage = max(
            (int) ceil($total / $perPage),
            1
        );

        return response()->json([
            'success' => true,

            'data' => $pagedRecords,

            'pagination' => [
                'current_page' =>
                $currentPage,

                'last_page' =>
                $lastPage,

                'per_page' =>
                $perPage,

                'total' =>
                $total,
            ],
        ]);
    }
}
