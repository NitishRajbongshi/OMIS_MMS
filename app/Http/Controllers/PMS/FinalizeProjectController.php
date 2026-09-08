<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinalizeProjectController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $department = $user->department;
        $officeDetails = session('userMapping');

        $circle_cd = $officeDetails->circle_cd ?? '';
        $zone_cd = $officeDetails->zone_cd ?? '';
        $division_cd = $officeDetails->division_cd ?? null;
        $sub_division_cd = $officeDetails->sub_division_cd ?? null;

        //dd($division_cd);

        $draftDetails = DB::table('projects.prt_project_details_draft as ppdd')
            ->join('asset_user_mappings as asp', 'asp.user_id', '=', 'ppdd.site_eng_id')
            ->where('asp.zone_cd', $zone_cd)
            ->when($circle_cd, fn($q) => $q->where('asp.circle_cd', $circle_cd))
            ->when($division_cd, fn($q) => $q->where('asp.division_cd', $division_cd))
            ->when($sub_division_cd, fn($q) => $q->where('asp.sub_division_cd', $sub_division_cd))
            ->where('ppdd.is_rejected', 'N')
            ->where('ppdd.sent_for_finalize', 'Y')
            ->orderBy('ppdd.project_cd', 'desc')
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
        $workItems_exist =  DB::table('projects.prt_project_work_sub_items_details_draft')
            ->pluck('project_cd')
            ->toArray();

        return view("pms.finalization.projectFinalize", compact(
            'draftDetails',
            'project_code_culvert',
            'project_code_rtw',
            'project_code_pvm',
            'project_code_bridge',
            'workItems_exist'
        ));
    }

    public function verification() {}

    public function approve(Request $request)
    {
        $user = Auth::user();
        $userid = $user->id;
        $projectCode = $request->project_cd;

        // Fetch draft data
        $draft = DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $projectCode)
            ->first();

        if (!$draft) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Insert into main project table
        DB::table('projects.prt_project_details')->insert([
            'project_cd'                => $projectCode,
            'project_name'              => $draft->project_name,
            'owner_dept_cd'             => $draft->owner_dept_cd,
            'division_cd'               => $draft->division_cd,
            'parent_asset_cd'           => $draft->parent_asset_cd,
            'project_start_date'        => $draft->project_start_date,
            'project_end_date'          => $draft->project_end_date,
            'project_status_cd'         => 1,
            'project_awarded_to'        => $draft->project_awarded_to,
            'site_eng_id'               => $userid,
            'site_incharge_name'        => $draft->site_incharge_name,
            'site_incharge_office_cd'   => $draft->site_incharge_office_cd,
            'site_incharge_ph_no'       => $draft->site_incharge_ph_no,

            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $userid,
            'updated_by' => $userid,
            'approved_by' => $userid,
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Inserted successfully']);
    }

    public function reject(Request $request)
    {
        $user = Auth::user();
        $userid = $user->id;
        $projectCode = $request->project_cd;
        $reason = $request->reason;

        // Check if draft exists
        $draft = DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $projectCode)
            ->first();

        if (!$draft) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Update draft row
        DB::table('projects.prt_project_details_draft')
            ->where('project_cd', $projectCode)
            ->update([
                'is_rejected'            => 'Y',
                'sent_for_finalize'      => 'N',
                'reason_of_rejection'    => $reason,
                'rejected_by'            => $userid,
                'date_of_rejection'      => now(),
                'updated_at'             => now(),
                'updated_by'             => $userid,
            ]);

        return response()->json(['message' => 'Rejected Successfully']);
    }


    public function verifiedProjects()
    {
        $user = Auth::user();

        $draftDetails = DB::table('projects.prt_project_details_draft')
            ->orderBy('project_cd', 'desc')
            ->where('created_by', '=', $user->id)
            ->where('sent_for_finalize', '=', 'Y')
            ->where('is_published', '=', 'Y')
            ->where('is_rejected', '=', 'N')
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

        return view("pms.verifiedProjects", compact('draftDetails', 'project_code_culvert', 'project_code_rtw', 'project_code_pvm', 'project_code_bridge'));
    }

    // new Function added by Pulak
    public function trackStatus(Request $request)
    {
        $user = Auth::user();
        $userDeptCd = $user->department;

        $mapping = (array) session('userMapping');

        $user_zone_cd = $mapping["zone_cd"] ?? null;
        $user_circle_cd = $mapping["circle_cd"] ?? null;
        $user_division_cd = $mapping["division_cd"] ?? null;
        $user_sub_division_cd = $mapping["sub_division_cd"] ?? null;
        $user_office_type_cd = $mapping["office_type_cd"] ?? null;

        // dd($user_division_cd);

        // ── Subquery: per-item progress ──────────────────────────────────
        $itemProgress = DB::table('projects.prt_project_details as p')
            ->leftJoin('projects.prt_project_work_items_details as w', 'w.project_cd', '=', 'p.project_cd')
            ->leftJoin('projects.prt_project_progress_details_work_item_wise as pr', 'pr.item_id', '=', 'w.id')
            ->select(
                'p.project_cd',
                'p.project_name',
                DB::raw('
                    CASE
                        WHEN w.quantity > 0
                        THEN (COALESCE(SUM(pr.quantity_done),0)/w.quantity)*100
                        ELSE 0
                    END as item_progress
                ')
            )
            ->where('pr.status', 'A')
            ->groupBy('p.project_cd', 'p.project_name', 'w.id', 'w.quantity');

        // ── Main query: finalized / approved projects ────────────────────
        $query = DB::table('projects.prt_project_details as p')
            ->leftJoinSub($itemProgress, 't', fn($join) => $join->on('p.project_cd', '=', 't.project_cd'))
            ->leftJoin('projects.prm_project_status as ps', 'p.project_status_cd', '=', 'ps.project_status_cd')
            ->leftJoin('public.department_details as d',   'p.owner_dept_cd',  '=', 'd.id')
            ->leftJoin('public.asset_master_divisions as div',        'p.division_cd',     '=', 'div.division_cd')
            ->leftJoin('public.asset_master_sub_divisions as sub_div', 'p.sub_division_cd', '=', 'sub_div.sub_div_cd')
            ->leftJoin('projects.prt_contractor_details as c',        'p.project_awarded_to', '=', 'c.regn_no')
            ->leftJoin('projects.prm_project_types as pt',            'p.project_type_cd', '=', 'pt.proj_type_cd');

        if ($userDeptCd) {
            $query->where('p.owner_dept_cd', $userDeptCd);
        }
        if ($user_office_type_cd === 'SDO') {
            Log::info($user_sub_division_cd);
            $query->where('p.sub_division_cd', $user_sub_division_cd);
        } elseif ($user_office_type_cd === 'DO') {
            $query->where('p.division_cd', $user_division_cd);
        } elseif ($user_office_type_cd === 'CO') {
            $query->where('div.circle_cd', $user_circle_cd);
        } elseif ($user_office_type_cd === 'ZO') {
            $query->where('div.zone_cd', $user_zone_cd);
        }

        $projects = $query->select(
            'p.project_cd',
            'p.project_name',
            'pt.proj_type_descr as project_type',
            'd.department_name',
            'div.division_name',
            'sub_div.sub_div_name',
            'p.project_start_date',
            'p.project_end_date',
            'p.est_proj_cost',
            'p.work_order_amount',
            'p.defect_liability_period',
            'ps.project_status_descr as project_status',
            'c.contractors_name as contractor_name',
            'p.approved_at',
            'p.created_at',
            DB::raw('
                CASE
                    WHEN MIN(t.item_progress) = 100 THEN 100
                    ELSE ROUND(AVG(t.item_progress),2)
                END as progress_percent
            ')
        )
            ->groupBy(
                'p.project_cd',
                'p.project_name',
                'pt.proj_type_descr',
                'd.department_name',
                'div.division_name',
                'sub_div.sub_div_name',
                'p.project_start_date',
                'p.project_end_date',
                'p.est_proj_cost',
                'p.work_order_amount',
                'p.defect_liability_period',
                'ps.project_status_descr',
                'c.contractors_name',
                'p.approved_at',
                'p.created_at'
            )
            ->orderByDesc('p.project_cd')
            ->get();

        // ── Draft projects: pending / rejected ───────────────────────────
        $draftQuery = DB::table('projects.prt_project_details_draft as d')
            ->leftJoin('public.department_details as dept',   'd.owner_dept_cd',  '=', 'dept.id')
            ->leftJoin('public.asset_master_divisions as div', 'd.division_cd',    '=', 'div.division_cd')
            ->leftJoin('public.asset_master_sub_divisions as sub_div', 'd.sub_division_cd', '=', 'sub_div.sub_div_cd')
            ->leftJoin('projects.prt_contractor_details as c', 'd.project_awarded_to', '=', 'c.regn_no')
            ->leftJoin('projects.prm_project_types as pt',    'd.project_type_cd', '=', 'pt.proj_type_cd')
            // user who submitted for finalization
            ->leftJoin('public.users as sender',  'd.sent_for_finalize_by', '=', 'sender.id')
            // user who rejected
            ->leftJoin('public.users as rejector', 'd.rejected_by',         '=', 'rejector.id');
        // filter: only drafts that are either pending finalization OR rejected (i.e. not yet published as approved)
        // ->where(function ($q) {
        //     $q->where('d.sent_for_finalize', 'Y')
        //       ->orWhere('d.is_rejected', 'Y');
        // });

        if ($userDeptCd) {
            $draftQuery->where('d.owner_dept_cd', $userDeptCd);
        }
        if ($user_office_type_cd === 'SDO') {
            $draftQuery->where('d.sub_division_cd', $user_sub_division_cd);
        } elseif ($user_office_type_cd === 'DO') {
            $draftQuery->where('d.division_cd', $user_division_cd);
        } elseif ($user_office_type_cd === 'CO') {
            $draftQuery->where('div.circle_cd', $user_circle_cd);
        } elseif ($user_office_type_cd === 'ZO') {
            $draftQuery->where('div.zone_cd', $user_zone_cd);
        }

        $draftProjects = $draftQuery->select(
            'd.project_cd',
            'd.project_name',
            'pt.proj_type_descr as project_type',
            'dept.department_name',
            'div.division_name',
            'sub_div.sub_div_name',
            'd.project_start_date',
            'd.project_end_date',
            'd.est_proj_cost',
            'd.work_order_amount',
            'd.defect_liability_period',
            'c.contractors_name as contractor_name',
            'd.sent_for_finalize',
            'd.sent_for_finalize_on',
            DB::raw("COALESCE(sender.name, 'N/A') as sent_by_name"),
            'd.is_rejected',
            'd.reason_of_rejection',
            'd.date_of_rejection',
            DB::raw("COALESCE(rejector.name, 'N/A') as rejected_by_name")
        )
            ->orderByDesc('d.project_cd')
            ->get();

        return view('pms.trackStatus', compact('projects', 'draftProjects'));
    }
    // new code ended by Pulak
}
