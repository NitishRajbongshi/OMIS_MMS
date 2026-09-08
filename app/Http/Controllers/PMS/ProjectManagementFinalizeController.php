<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectManagementFinalizeController extends Controller
{
    // function to show list of verified projects
    public function index()
    {
        $user = Auth::user();

        $types = ['NEW', 'UPG', 'MTN'];
        $projects = DB::table('projects.prt_project_details as project_details')
            ->select([
                'project_details.project_cd',
                'project_details.project_name',
                'project_details.project_type_cd',
                'project_details.owner_dept_cd',
                'project_details.division_cd',
                'project_details.sub_division_cd',
                'project_details.parent_asset_cd',
                'project_details.project_start_date',
                'project_details.project_end_date',
                'project_details.est_proj_cost',
                'project_details.defect_liability_period',
                'project_details.work_order_amount',
				//by dipshikha
                'project_details.work_order_no',
                'project_details.work_order_issue_date',
                'project_details.scheme_cd',
                'scheme.scheme_name',
                //end			  
                'project_details.project_status_cd',
                'project_details.project_awarded_to',
                'project_details.site_eng_id',
                'project_details.site_incharge_name',
                'project_details.site_incharge_office_cd',
                'project_details.site_incharge_ph_no',
                'project_details.latitude',
                'project_details.longitude',
                'project_details.others',
                'project_type.proj_type_descr as project_type',
                'owner_dept.department_name as owner_department',
                'division.division_name',
                'sub_division.sub_div_name',
                'site_incharge.office_name as site_incharge_office_name',
                'project_status.project_status_descr as project_status',
                'contractor.contractors_name as contractor_name',
            ])
			//by dipshikha
            ->leftJoin(
                'projects.prm_scheme_details as scheme',
                'project_details.scheme_cd',
                '=',
                'scheme.scheme_id'
            )
            //end
            ->leftJoin(			  
                'projects.prm_project_types as project_type',
                'project_details.project_type_cd',
                '=',
                'project_type.proj_type_cd'
            )
            ->leftJoin(
                'public.department_details as owner_dept',
                'project_details.owner_dept_cd',
                '=',
                'owner_dept.id'
            )
            ->leftJoin(
                'public.asset_master_divisions as division',
                'project_details.division_cd',
                '=',
                'division.division_cd'
            )
            ->leftJoin(
                'public.asset_master_sub_divisions as sub_division',
                'project_details.sub_division_cd',
                '=',
                'sub_division.sub_div_cd'
            )
            ->leftJoin(
                'projects.prm_project_status as project_status',
                'project_details.project_status_cd',
                '=',
                'project_status.project_status_cd'
            )
            ->leftJoin(
                'projects.prm_site_incharge_office_details as site_incharge',
                'project_details.site_incharge_office_cd',
                '=',
                'site_incharge.office_cd'
            )
            ->leftJoin(
                'projects.prt_contractor_details as contractor',
                'project_details.project_awarded_to',
                '=',
                'contractor.regn_no'
            )
            ->orderBy('project_cd', 'desc')
            ->whereIn('project_type_cd', $types)
            ->where('owner_dept_cd', '=', $user->department)
            ->get();

        $project_code_culvert = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '0')
            ->pluck('project_cd')
            ->toArray();
        $project_code_bridge = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '1')
            ->pluck('project_cd')
            ->toArray();
        $project_code_rtw = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '16')
            ->pluck('project_cd')
            ->toArray();
        $project_code_pvm = DB::table('projects.prt_project_sub_asset_details_draft')
            ->where('sub_asset_type_cd', '=', '2')
            ->pluck('project_cd')
            ->toArray();
        $workItems_exist = DB::table('projects.prt_project_work_items_details')
            ->distinct('project_cd')
            ->pluck('project_cd')
            ->toArray();

        return view(
            "pms.approved.verifiedProject",
            compact(
                'user',
				'projects',
                'project_code_culvert',
                'project_code_rtw',
                'project_code_pvm',
                'project_code_bridge',
                'workItems_exist'
            )
        );
    }

    // function to show list of projects to finalize
    public function create()
    {
        $user = Auth::user();
        // In case of switching between the offices
        // start
        $office_cd = $user->office;
        $users_office_type_cd = $user->office_type_cd;
        $zone_cd = null;
        $circle_cd = null;
        $division_cd = null;
        $sub_division_cd = null;
        $subDivisionList = []; // To store the sub divisions list under the user's jurisdiction

        $userMapping = DB::table('asset_user_mappings')
            ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
            ->where('user_id', '=', $user->id)
            ->get()->first();

        $zone_cd = $userMapping->zone_cd;
        $circle_cd = $userMapping->circle_cd;
        $division_cd = $userMapping->division_cd;
        $sub_division_cd = $userMapping->sub_division_cd;
        if (session('office_charge_type') == 1) {
            $office_cd = session('office_cd');
            $users_office_type_cd = session('users_office_type_cd');
            $officeDivisionDtls = DB::table('office_details as ofd')
                ->select('ofd.zone_cd', 'ofd.circle_cd', 'ofd.division_cd', 'ofd.sub_division_cd')
                ->where('ofd.id', '=', $office_cd)
                ->get()->first();
            if ($officeDivisionDtls) {
                $zone_cd = $officeDivisionDtls->zone_cd;
                $circle_cd = $officeDivisionDtls->circle_cd;
                $division_cd =  $officeDivisionDtls->division_cd;
                $sub_division_cd = $officeDivisionDtls->sub_division_cd;
            }
        }
        // end

        $types = ['NEW', 'UPG', 'MTN'];
        $baseQuery = DB::table('projects.prt_project_details_draft as project_details')
            ->select([
                'project_details.project_cd',
                'project_details.project_name',
                'project_details.project_type_cd',
                'project_details.owner_dept_cd',
                'project_details.division_cd',
                'project_details.sub_division_cd',
                'project_details.parent_asset_cd',
                'project_details.project_start_date',
                'project_details.project_end_date',
                'project_details.est_proj_cost',
                'project_details.defect_liability_period',
                'project_details.work_order_amount',
                'project_details.project_status_cd',
                'project_details.project_awarded_to',
                'project_details.site_eng_id',
                'project_details.site_incharge_name',
                'project_details.site_incharge_office_cd',
                'project_details.site_incharge_ph_no',
                'project_details.latitude',
                'project_details.longitude',
                'project_details.others',
                'project_details.reason_of_rejection',
                'project_type.proj_type_descr as project_type',
                'owner_dept.department_name as owner_department',
                'division.division_name',
                'sub_division.sub_div_name',
                'site_incharge.office_name as site_incharge_office_name',
                'project_status.project_status_descr as project_status',
                'contractor.contractors_name as contractor_name',
            ])
            ->leftJoin(
                'projects.prm_project_types as project_type',
                'project_details.project_type_cd',
                '=',
                'project_type.proj_type_cd'
            )
            ->leftJoin(
                'public.department_details as owner_dept',
                'project_details.owner_dept_cd',
                '=',
                'owner_dept.id'
            )
            ->leftJoin(
                'public.asset_master_divisions as division',
                'project_details.division_cd',
                '=',
                'division.division_cd'
            )
            ->leftJoin(
                'public.asset_master_sub_divisions as sub_division',
                'project_details.sub_division_cd',
                '=',
                'sub_division.sub_div_cd'
            )
            ->leftJoin(
                'projects.prm_project_status as project_status',
                'project_details.project_status_cd',
                '=',
                'project_status.project_status_cd'
            )
            ->leftJoin(
                'projects.prm_site_incharge_office_details as site_incharge',
                'project_details.site_incharge_office_cd',
                '=',
                'site_incharge.office_cd'
            )
            ->leftJoin(
                'projects.prt_contractor_details as contractor',
                'project_details.project_awarded_to',
                '=',
                'contractor.regn_no'
            )

            ->where('sent_for_finalize', '=', 'Y')
            ->where('owner_dept_cd', '=', $user->department)
            ->whereIn('project_type_cd', $types)
            ->orderBy('project_cd', 'desc');

        if ($users_office_type_cd == 'HQ') {
            $projects = $baseQuery->get();
        }

        if ($users_office_type_cd == 'ZO') {
            $subDivisions = DB::table('office_details')
                ->where('zone_cd', $zone_cd)
                ->where('department_id', session('user_dept_cd'))
                ->select('sub_division_cd')->get();
            foreach ($subDivisions as $item) {
                $subDivisionList[] = $item->sub_division_cd;
            }
            $projects = $baseQuery
                ->whereIn('sub_division_cd', $subDivisionList)
                ->get();
        }

        if ($users_office_type_cd == 'CO') {
            $subDivisions = DB::table('office_details')
                ->where('circle_cd', $circle_cd)
                ->where('department_id', session('user_dept_cd'))
                ->select('sub_division_cd')->get();
            foreach ($subDivisions as $item) {
                $subDivisionList[] = $item->sub_division_cd;
            }
            $projects = $baseQuery
                ->whereIn('sub_division_cd', $subDivisionList)
                ->get();
        }

        if ($users_office_type_cd == 'DO') {
            $subDivisions = DB::table('office_details')
                ->where('division_cd', $division_cd)
                ->where('department_id', session('user_dept_cd'))
                ->select('sub_division_cd')->get();
            foreach ($subDivisions as $item) {
                $subDivisionList[] = $item->sub_division_cd;
            }
            $projects = $baseQuery
                ->whereIn('sub_division_cd', $subDivisionList)
                ->get();
        }

        if ($users_office_type_cd == 'SDO') {
            $projects = $baseQuery
                ->where('sub_division_cd', $sub_division_cd)
                ->get();
        }

        $workItems_exist =  DB::table('projects.prt_project_work_items_details')
            ->pluck('project_cd')
            ->toArray();

        return view("pms.finalization.projectFinalize", compact(
            'user',
            'projects',
			'workItems_exist'
        ));
    }

    private function acceptProjectSubAssetDetails($projectId)
    {
        $status = DB::table('projects.prt_project_sub_asset_details')->insertUsing([
            'project_cd',
            'parent_asset_cd',
            'sub_asset_type_cd',
            'sub_asset_sr_no',
            'start_chainage',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ], function ($query) use ($projectId) {
            $query->from('projects.prt_project_sub_asset_details_draft')
                ->where('project_cd', '=', $projectId)
                ->select(
                    'project_cd',
                    'parent_asset_cd',
                    'sub_asset_type_cd',
                    'sub_asset_sr_no',
                    'start_chainage',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                );
        });
        Log::info("status: " . $status);
        if ($status) {
            $deleteStatus = DB::table('projects.prt_project_sub_asset_details_draft')
                ->where('project_cd', '=', $projectId)
                ->delete();
            return $deleteStatus;
            return true;
        }
    }

    public function acceptProject(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $projectId = $request->project_id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                // $subAssetStatus = $this->acceptProjectSubAssetDetails($projectId, $currentTime, $approvedBy);
                // Log::info("subAssetStatus: " . $subAssetStatus);
                // if ($subAssetStatus) {
                $status = DB::table('projects.prt_project_details')->insertUsing([
                    'project_cd',
                    'project_name',
                    'project_type_cd',
                    'owner_dept_cd',
                    'division_cd',
                    'sub_division_cd',
                    'parent_asset_cd',
                    'project_start_date',
                    'project_end_date',
                    'est_proj_cost',
                    'defect_liability_period',
                    'project_status_cd',
                    'project_awarded_to',
					//by dipshikha
                    'work_order_no',
                    'scheme_cd',
                    'work_order_issue_date',
                    //			  
                    'site_eng_id',
                    'site_incharge_name',
                    'site_incharge_office_cd',
                    'site_incharge_ph_no',
                    'latitude',
                    'longitude',
                    'is_published',
                    'others',
                    'approved_by',
                    'approved_at',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'work_order_amount'
                ], function ($query) use ($projectId, $currentTime, $approvedBy) {
                    $query->from('projects.prt_project_details_draft')
                        ->where('project_cd', '=', $projectId)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'project_cd',
                            'project_name',
                            'project_type_cd',
                            'owner_dept_cd',
                            'division_cd',
                            'sub_division_cd',
                            'parent_asset_cd',
                            'project_start_date',
                            'project_end_date',
                            'est_proj_cost',
                            'defect_liability_period',
                            'project_status_cd',
                            'project_awarded_to',
							//by dipshikha
                            'work_order_no',
                            'scheme_cd',
                            'work_order_issue_date',
                            //							
                            'site_eng_id',
                            'site_incharge_name',
                            'site_incharge_office_cd',
                            'site_incharge_ph_no',
                            'latitude',
                            'longitude',
                            'is_published',
                            'others',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at"),
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            'work_order_amount'
                        );
                });
                if ($status > 0) {
                    DB::table('projects.prt_project_details_draft')
                        ->where('project_cd', '=', $projectId)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalized all data successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Data not available to finalize!'
                    ]);
                }
                // } else {
                //     return response()->json([
                //         'status' => 204,
                //         'message' => 'Failed to finalized sub asset details!'
                //     ]);
                // }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function rejectProject(Request $request)
    {
        Log::info('rejectProject');
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $projectId = $request->project_cd;
                $reason = $request->reason;
                $status = DB::table('projects.prt_project_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('project_cd', $projectId)
                    ->update([
                        'is_rejected' => 'Y',
                        'reason_of_rejection' => $reason,
                        'date_of_rejection' => Carbon::now(),
                        'rejected_by' => Auth::user()->id,
                        'sent_for_finalize' => 'N',
                        'updated_at' => Carbon::now()
                    ]);

                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Project data rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Failed to reject project data!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }
}
