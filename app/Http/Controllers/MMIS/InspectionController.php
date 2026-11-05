<?php

namespace App\Http\Controllers\MMIS;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller
{
    private function inspectionQuery()
    {
        $user = Auth::user();

        $officeType = $user->office_type_cd;

        /*
        |--------------------------------------------------------------------------
        | Get user's office mapping
        |--------------------------------------------------------------------------
        */

        $mapping = DB::table('asset_user_mappings')
            ->select(
                'zone_cd',
                'circle_cd',
                'division_cd',
                'sub_division_cd'
            )
            ->where('user_id', $user->id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Office Charge
        |--------------------------------------------------------------------------
        |
        | If the user is currently holding charge of another office,
        | use that office's hierarchy.
        |
        */

        if (session('office_charge_type') == 1) {

            $officeCd = session('office_cd');

            $officeType = session(
                'users_office_type_cd',
                $officeType
            );

            $mapping = DB::table('office_details')
                ->select(
                    'zone_cd',
                    'circle_cd',
                    'division_cd',
                    'sub_division_cd'
                )
                ->where('id', $officeCd)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Base inspection query
        |--------------------------------------------------------------------------
        */

        $query = DB::table(
            'maintenance.mtn_inspection_details as i'
        );

        /*
        |--------------------------------------------------------------------------
        | HQ
        |--------------------------------------------------------------------------
        |
        | HQ can see all inspections.
        |
        */

        if ($officeType === 'HQ') {
            return $query;
        }

        /*
        |--------------------------------------------------------------------------
        | No office mapping
        |--------------------------------------------------------------------------
        |
        | If the user has no mapping, don't expose inspections.
        |
        */

        if (!$mapping) {
            return $query->whereRaw('1 = 0');
        }

        /*
        |--------------------------------------------------------------------------
        | Join inspection creator's office mapping
        |--------------------------------------------------------------------------
        |
        | mtn_inspection_details.created_by = users.id
        |
        */

        $query->join(
            'asset_user_mappings as creator_map',
            'creator_map.user_id',
            '=',
            'i.created_by'
        );

        /*
        |--------------------------------------------------------------------------
        | Apply office hierarchy
        |--------------------------------------------------------------------------
        */

        switch ($officeType) {

            case 'SDO':

                $query->where(
                    'creator_map.sub_division_cd',
                    $mapping->sub_division_cd
                );

                break;

            case 'DO':

                $query->where(
                    'creator_map.division_cd',
                    $mapping->division_cd
                );

                break;

            case 'CO':

                $query->where(
                    'creator_map.circle_cd',
                    $mapping->circle_cd
                );

                break;

            case 'ZO':

                $query->where(
                    'creator_map.zone_cd',
                    $mapping->zone_cd
                );

                break;

            default:

                // Unknown office type:
                // Only show inspections created by this user.

                $query->where(
                    'i.created_by',
                    $user->id
                );

                break;
        }

        return $query;
    }


    // =========================================================================
    // INDEX
    // =========================================================================

    /**
     * Inspection page.
     * Only the asset-type dropdown is loaded here.
     * Inspection rows are loaded through AJAX.
     */
    public function index(Request $request)
    {
        $selectedType = $request->get('asset_type', '10');

        /*
        |--------------------------------------------------------------------------
        | Asset Types
        |--------------------------------------------------------------------------
        |
        | Only asset types having inspections visible to the
        | logged-in user's office jurisdiction.
        |
        */

        $assetTypes = $this->inspectionQuery()
            ->join(
                'public.asset_master_road_sub_assets as a',
                'a.sub_asset_cd',
                '=',
                'i.insp_asset_type'
            )
            ->select(
                'a.sub_asset_cd',
                'a.sub_assets_descr'
            )
            ->distinct()
            ->orderBy('a.sub_assets_descr')
            ->get();

        return view(
            'mmis.inspection.index',
            compact(
                'assetTypes',
                'selectedType'
            )
        );
    }


    // =========================================================================
    // ASSET DATA
    // =========================================================================

    /**
     * AJAX:
     * Rows of the main inspection table for the selected asset type.
     */
    public function assetData(Request $request)
    {
        $selectedType = $request->get('asset_type', '10');

        $inspections = $this->inspectionQuery()

            /*
            |--------------------------------------------------------------------------
            | Sub Asset Name
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'public.asset_master_road_sub_assets as a',
                'a.sub_asset_cd',
                '=',
                'i.insp_asset_type'
            )
																						 
            /*
            |--------------------------------------------------------------------------
            | Bridge
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_bridge_details as arbd',
                function ($join) {

                    $join->whereRaw(
                        'arbd.rd_bridge_cd::text = i.insp_asset_id::text'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | CD Work / Culvert
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_cdwork_details as arcd',
                function ($join) {

                    $join->whereRaw(
                        'arcd.rd_cdwork_cd::text = i.insp_asset_id::text'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Protection Wall
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_protection_wall_details as apwd',
                function ($join) {

                    $join->whereRaw(
                        'apwd.protection_wall_cd::text = i.insp_asset_id::text'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Road
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_details as ard',
                function ($join) {

                    $join->whereRaw(
                        'ard.rd_system_id::text = COALESCE(
                            arbd.rd_system_id::text,
                            arcd.rd_system_id::text,
                            apwd.rd_system_id::text,
                            i.insp_asset_id::text
                        )'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Selected Asset Type
            |--------------------------------------------------------------------------
            */

            ->where(
                'i.insp_asset_type',
                $selectedType
            )

            /*
            |--------------------------------------------------------------------------
            | Select
            |--------------------------------------------------------------------------
            */

            ->select(
                'i.insp_asset_type',
                'i.insp_asset_id',
                'ard.rd_name as insp_asset_location',
                'a.sub_assets_descr',
                DB::raw('COUNT(*) as total_inspections')
            )

            /*
            |--------------------------------------------------------------------------
            | Group
            |--------------------------------------------------------------------------
            */

            ->groupBy(
                'i.insp_asset_type',
                'i.insp_asset_id',
                'ard.rd_name',
                'a.sub_assets_descr'
            )

            ->orderBy('a.sub_assets_descr')
            ->orderBy('i.insp_asset_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Prepare DataTable Data
        |--------------------------------------------------------------------------
        */

        $data = $inspections->map(function ($row) {

            return [
                'asset_type' => $row->sub_assets_descr,
                'asset_id'   => $row->insp_asset_id,
                'asset_name' => $row->insp_asset_location,
                'total'      => $row->total_inspections,

                'latest_url' => route(
                    'mmis.inspection.latest',
                    [
                        $row->insp_asset_type,
                        $row->insp_asset_id
                    ]
                ),
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }


    // =========================================================================
    // LATEST INSPECTIONS
    // =========================================================================

    /**
     * AJAX:
     * Latest 3 inspections of one asset.
     */
    public function latestInspections($assetType, $assetId)
    {
        $query = $this->inspectionQuery()
																									
            ->leftJoin(
                'maintenance.mtn_inspection_score as sc',
                'sc.inspection_id',
                '=',
                'i.id'
            )

            ->where(
                'i.insp_asset_type',
                $assetType
            )

            ->where(
                'i.insp_asset_id',
                $assetId
            );

        /*
        |--------------------------------------------------------------------------
        | Total count
        |--------------------------------------------------------------------------
        */

        $total = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Latest 3
        |--------------------------------------------------------------------------
        */

        $rows = $query
            ->select(
                'i.id',
                'i.insp_cd',
                'i.insp_date',
                'sc.mas_score'
            )
            ->orderBy(
                'i.insp_date',
                'desc'
            )
            ->limit(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Prepare Response
        |--------------------------------------------------------------------------
        */

        $items = $rows->map(function ($row) {

            return [
                'insp_cd' => $row->insp_cd,

                'insp_date' => $row->insp_date
                    ? \Carbon\Carbon::parse(
                        $row->insp_date
                    )->format('d-m-Y')
                    : '-',

                'mas_score' => $row->mas_score,

                'show_url' => route(
                    'mmis.inspection.show',
                    $row->id
                ),

                'pdf_url' => route(
                    'mmis.inspection.pdf',
                    $row->id
                ),
            ];
        });

        return response()->json([
            'total' => $total,
            'items' => $items,

            'list_url' => route(
                'mmis.inspection.assetList',
                [
                    $assetType,
                    $assetId
                ]
            ),
        ]);
    }


    // =========================================================================
    // ALL INSPECTIONS OF ONE ASSET
    // =========================================================================

    /**
     * Full list of inspections of one asset.
     */
    public function assetInspections($assetType, $assetId)
    {
        $inspections = $this->inspectionQuery()

            /*
            |--------------------------------------------------------------------------
            | Sub Asset
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'public.asset_master_road_sub_assets as a',
                'a.sub_asset_cd',
                '=',
                'i.insp_asset_type'
            )

            /*
            |--------------------------------------------------------------------------
            | Bridge
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_bridge_details as arbd',
                function ($join) {

                    $join->whereRaw(
                        'arbd.rd_bridge_cd::text = i.insp_asset_id::text'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | CD Work
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_cdwork_details as arcd',
                function ($join) {

                    $join->whereRaw(
                        'arcd.rd_cdwork_cd::text = i.insp_asset_id::text'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Protection Wall
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_protection_wall_details as apwd',
                function ($join) {

                    $join->whereRaw(
                        'apwd.protection_wall_cd::text = i.insp_asset_id::text'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Road
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_details as ard',
                function ($join) {

                    $join->whereRaw(
                        'ard.rd_system_id::text = COALESCE(
                            arbd.rd_system_id::text,
                            arcd.rd_system_id::text,
                            apwd.rd_system_id::text,
                            i.insp_asset_id::text
                        )'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Master Tables
            |--------------------------------------------------------------------------
            |
            | Change "..._descr" columns if your actual column
            | names are different.
            |
            */

            ->leftJoin(
                'maintenance.master_mtn_inspection_types as it',
                'it.inspection_type_cd',
                '=',
                'i.insp_type_cd'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c',
                'c.condition_type_cd',
                '=',
                'i.cndtn_overall'
            )

            ->leftJoin(
                'maintenance.master_mtn_risk_types as rt',
                'rt.risk_type_cd',
                '=',
                'i.risk_type_cd'
            )

            ->leftJoin(
                'maintenance.mtn_inspection_score as s',
                's.inspection_id',
                '=',
                'i.id'
            )

            /*
            |--------------------------------------------------------------------------
            | Asset
            |--------------------------------------------------------------------------
            */

            ->where(
                'i.insp_asset_type',
                $assetType
            )

            ->where(
                'i.insp_asset_id',
                $assetId
            )

            /*
            |--------------------------------------------------------------------------
            | Select
            |--------------------------------------------------------------------------
            */

            ->select(
                'i.id',
                'i.insp_cd',
                'i.insp_date',
                'i.inspector_name',
                'i.inspector_designation',
                'i.start_section',
                'i.end_section',

                'it.inspection_type_descr as insp_type',

                'c.condition_type_descr as overall_condition',

                'rt.risk_type_descr as risk_type',

                's.mas_score',

                'a.sub_assets_descr as asset_type',

                'ard.rd_name as asset_location'
            )

            ->orderBy(
                'i.insp_date',
                'desc'
            )

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Wrong asset / no inspection
        |--------------------------------------------------------------------------
        */

        if ($inspections->isEmpty()) {
            abort(404);
        }

        $first = $inspections->first();

        return view(
            'mmis.inspection.inspectionList',
            [
                'assetId'        => $assetId,
                'asset_type'     => $first->asset_type,
                'asset_location' => $first->asset_location,
                'inspections'    => $inspections,
            ]
        );
    }


    // =========================================================================
    // SHOW
    // =========================================================================

    /**
     * Show full inspection details.
     */
    public function show($id)
    {
        /*
        |--------------------------------------------------------------------------
        | First check that the inspection is accessible
        |--------------------------------------------------------------------------
        */

        $this->getAccessibleInspection($id);

        $data = $this->getInspectionData($id);

        return view(
            'mmis.inspection.show',
            $data
        );
    }


    // =========================================================================
    // DOWNLOAD PDF
    // =========================================================================

    /**
     * Download inspection report as PDF.
     */
    public function downloadPdf($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Access check
        |--------------------------------------------------------------------------
        */

        $this->getAccessibleInspection($id);

        $data = $this->getInspectionData($id);

        $pdf = Pdf::loadView(
            'mmis.inspection.inspectionPdf',
            $data
        )->setPaper(
            'a4',
            'portrait'
        );

        return $pdf->download(
            'Inspection_' .
            $data['inspection']->insp_cd .
            '.pdf'
        );
    }


    // =========================================================================
    // PRINT PDF
    // =========================================================================

    public function printPdf($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Access check
        |--------------------------------------------------------------------------
        */

        $this->getAccessibleInspection($id);

        $data = $this->getInspectionData($id);

        $pdf = Pdf::loadView(
            'mmis.inspection.inspectionPdf',
            $data
        )->setPaper(
            'a4',
            'portrait'
        );

        return $pdf->stream(
            'Inspection_' .
            $data['inspection']->insp_cd .
            '.pdf'
        );
    }


    // =========================================================================
    // ACCESS CHECK
    // =========================================================================

    /**
     * Check whether a particular inspection is accessible
     * to the logged-in user's office jurisdiction.
     */
    private function getAccessibleInspection($id)
    {
        $inspection = $this->inspectionQuery()
            ->where(
                'i.id',
                $id
            )
            ->select(
                'i.id'
            )
            ->first();

        if (!$inspection) {
            abort(404);
        }

        return $inspection;
    }


    // =========================================================================
    // GET INSPECTION DATA
    // =========================================================================

    /**
     * Collect all data of one inspection.
     *
     * Used by:
     * - Show page
     * - Download PDF
     * - Print PDF
     */
    private function getInspectionData($id)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Inspection Details
        |--------------------------------------------------------------------------
        */
																													   
        $inspection = $this->inspectionQuery()
																			  
            ->leftJoin(
                'maintenance.master_mtn_inspection_types as it',
                'it.inspection_type_cd',
                '=',
                'i.insp_type_cd'
            )

            ->leftJoin(
                'public.asset_master_road_sub_assets as a',
                'a.sub_asset_cd',
                '=',
                'i.insp_asset_type'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_overall',
                'c_overall.condition_type_cd',
                '=',
                'i.cndtn_overall'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_pavement',
                'c_pavement.condition_type_cd',
                '=',
                'i.cndtn_pavement'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_drainage',
                'c_drainage.condition_type_cd',
                '=',
                'i.cndtn_drainage'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_shoulder',
                'c_shoulder.condition_type_cd',
                '=',
                'i.cndtn_shoulder'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_structural',
                'c_structural.condition_type_cd',
                '=',
                'i.cndtn_structural'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_safety',
                'c_safety.condition_type_cd',
                '=',
                'i.cndtn_safety_features'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_signage',
                'c_signage.condition_type_cd',
                '=',
                'i.cndtn_signage'
            )

            ->leftJoin(
                'maintenance.master_mtn_conditions as c_priority',
                'c_priority.condition_type_cd',
                '=',
                'i.recmnd_priority'
            )

            ->leftJoin(
                'maintenance.master_mtn_recm_actions as ra',
                'ra.action_type_cd',
                '=',
                'i.recmnd_action_type_cd'
            )

            ->leftJoin(
                'maintenance.master_mtn_risk_types as rt',
                'rt.risk_type_cd',
                '=',
                'i.risk_type_cd'
            )

            /*
            |--------------------------------------------------------------------------
            | Bridge
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_bridge_details as arbd',
                function ($join) {

                    $join->whereRaw(
                        "arbd.rd_bridge_cd::text =
                         i.insp_asset_id::text
                         AND i.insp_asset_type = '1'"
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Culvert
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_cdwork_details as arcd',
                function ($join) {

                    $join->whereRaw(
                        "arcd.rd_cdwork_cd::text =
                         i.insp_asset_id::text
                         AND i.insp_asset_type = '0'"
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Protection Wall
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_protection_wall_details as apwd',
                function ($join) {

                    $join->whereRaw(
                        "apwd.protection_wall_cd::text =
                         i.insp_asset_id::text
                         AND i.insp_asset_type = '12'"
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Road
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'asset_road_details as ard',
                function ($join) {

                    $join->whereRaw(
                        'ard.rd_system_id::text = COALESCE(
                            arbd.rd_system_id::text,
                            arcd.rd_system_id::text,
                            apwd.rd_system_id::text,
                            i.insp_asset_id::text
                        )'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Maintenance Score
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'maintenance.mtn_inspection_score as s',
                's.inspection_id',
                '=',
                'i.id'
            )

            /*
            |--------------------------------------------------------------------------
            | Inspection ID
            |--------------------------------------------------------------------------
            */

            ->where(
                'i.id',
                $id
            )

            /*
            |--------------------------------------------------------------------------
            | Select
            |--------------------------------------------------------------------------
            */

            ->select(
                'i.*',

                'it.inspection_type_descr as insp_type',

                'a.sub_assets_descr as asset_type',

                'ard.rd_name as road_name',

                'c_overall.condition_type_descr as overall_condition',

                'c_pavement.condition_type_descr as pavement_condition',

                'c_drainage.condition_type_descr as drainage_condition',

                'c_shoulder.condition_type_descr as shoulder_condition',

                'c_structural.condition_type_descr as structural_condition',

                'c_safety.condition_type_descr as safety_condition',

                'c_signage.condition_type_descr as signage_condition',

                'c_priority.condition_type_descr as priority_name',

                'ra.action_type_descr as action_name',

                'rt.risk_type_descr as risk_name',

                's.mas_score'
            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | Inspection doesn't exist / isn't accessible
        |--------------------------------------------------------------------------
        */

        if (!$inspection) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Observations
        |--------------------------------------------------------------------------
        */

        $observations = DB::table(
            'maintenance.mtn_insp_observation_details as o'
        )

            ->leftJoin(
                'maintenance.master_mtn_item_activities as act',
                'act.activity_cd',
                '=',
                'o.activity_cd'
            )

            ->leftJoin(
                'maintenance.master_mtn_grading_details as g',
                'g.grading_cd',
                '=',
                'o.grading_cd'
            )

            ->where(
                'o.insp_id',
                $id
            )

            ->select(
                'o.id',
                'o.obsrv_desc',
                'o.obsrv_weightage',

                'act.activity_descr as activity_name',

                'g.grading_descr as grading_name'
            )

            ->orderBy('o.id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 3. PCI Details
        |--------------------------------------------------------------------------
        */

        $pci = null;

        if ($inspection->pci_section_cd) {

            $pci = DB::table(
                'public.asset_road_pavement_condition_indexes'
            )
                ->where(
                    'pci_section_cd',
                    $inspection->pci_section_cd
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Bridge Details
        |--------------------------------------------------------------------------
        */

        $bridge = null;
												  
        if ((string) $inspection->insp_asset_type === '1') {

            $bridge = DB::table(
                'asset_road_bridge_details'
            )
                ->whereRaw(
                    'rd_bridge_cd::text = ?',
                    [
                        $inspection->insp_asset_id
                    ]
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Culvert Details
        |--------------------------------------------------------------------------
        */

        $culvert = null;

        if ((string) $inspection->insp_asset_type === '0') {

            $culvert = DB::table(
                'public.asset_road_cdwork_details'
            )
                ->whereRaw(
                    'rd_cdwork_cd::text = ?',
                    [
                        $inspection->insp_asset_id
                    ]
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Protection Wall Details
        |--------------------------------------------------------------------------
        */

        $wall = null;

        if ((string) $inspection->insp_asset_type === '12') {

            $wall = DB::table(
                'public.asset_protection_wall_details'
            )
                ->whereRaw(
                    'protection_wall_cd::text = ?',
                    [
                        $inspection->insp_asset_id
                    ]
                )
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return compact(
            'inspection',
            'observations',
            'pci',
            'bridge',
            'culvert',
            'wall'
        );
    }
}