<?php

namespace App\Http\Controllers\Api\V1\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    protected array $masterMap = [
        'conditions' => [
            'table'        => 'maintenance.master_mtn_conditions',
            'code_col'     => 'condition_type_cd',
            'descr_col'    => 'condition_type_descr',
        ],
        'defect_types' => [
            'table'        => 'maintenance.master_mtn_defect_types',
            'code_col'     => 'defect_type_cd',
            'descr_col'    => 'defect_type_descr',
        ],
        'inspection_types' => [
            'table'        => 'maintenance.master_mtn_inspection_types',
            'code_col'     => 'inspection_type_cd',
            'descr_col'    => 'inspection_type_descr',
        ],
        'recm_actions' => [
            'table'        => 'maintenance.master_mtn_recm_actions',
            'code_col'     => 'action_type_cd',
            'descr_col'    => 'action_type_descr',
        ],
        'risk_types' => [
            'table'        => 'maintenance.master_mtn_risk_types',
            'code_col'     => 'risk_type_cd',
            'descr_col'    => 'risk_type_descr',
        ],
        'severity_types' => [
            'table'        => 'maintenance.master_mtn_severity_types',
            'code_col'     => 'severity_type_cd',
            'descr_col'    => 'severity_type_descr',
        ],
        'grading_details' => [
            'table'        => 'maintenance.master_mtn_grading_details',
            'code_col'     => 'grading_cd',
            'descr_col'    => 'grading_descr',
        ],
        'item_activities' => [
            'table'        => 'maintenance.master_mtn_item_activities',
            'code_col'     => 'activity_cd',
            'title_col'    => 'activity_title',
            'descr_col'    => 'activity_descr',
        ],
    ];

    public function index(Request $request)
    {
        $requested = $request->filled('types')
            ? explode(',', $request->query('types'))
            : array_keys($this->masterMap);

        $invalid = array_diff($requested, array_keys($this->masterMap));

        if (!empty($invalid)) {
            return response()->json([
                'success' => false,
                'message' => 'Unknown master data type(s) requested.',
                'invalid_types' => array_values($invalid),
                'available_types' => array_keys($this->masterMap),
            ], 422);
        }

        $result = [];

        foreach ($requested as $key) {
            $result[$key] = $this->fetchMasterList($key);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 200);
    }

    protected function fetchMasterList(string $key): array
    {
        $config = $this->masterMap[$key];

        // Master data changes rarely — cache per type for a day.
        // Bump the cache key version (or flush manually) after edits.
        return Cache::remember("master_data:v2:{$key}", now()->addDay(), function () use ($config) {
            $columns = [
                "{$config['code_col']} as code",
                "{$config['descr_col']} as description",
            ];

            if (isset($config['title_col'])) {
                $columns[] = "{$config['title_col']} as title";
            }

            return DB::table($config['table'])
                ->select($columns)
                ->orderBy($config['descr_col'])
                ->get()
                ->map(function ($row) use ($config) {
                    $item = [
                        'code' => $row->code,
                        'description' => $row->description,
                    ];

                    if (isset($config['title_col'])) {
                        $item['title'] = $row->title;
                    }

                    return $item;
                })
                ->toArray();
        });
    }
}
