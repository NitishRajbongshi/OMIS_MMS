<?php

namespace App\Http\Controllers\PMS\WorkPlan;

use App\Http\Controllers\Controller;
use App\Models\PMS\PrtProjectDetailsDraft;
use App\Models\PMS\PrtProjectWorkItemsDetail;
use App\Models\PMS\PrtProjectWorkPlanDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkPlanController extends Controller
{
    private function getProject(string $projectCd): object|null
    {
        return DB::table('projects.prt_project_details_draft as project_details')
            ->select([
                'project_details.project_cd',
                'project_details.project_name',
                'project_type.proj_type_descr as project_type',
                'owner_dept.department_name as owner_department',
                'division.division_name',
                'sub_division.sub_div_name',
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
            ->where('project_details.sent_for_finalize', '=', 'N')
            ->where('project_details.project_cd', '=', $projectCd)
            ->first();
    }

    public function index(
        PrtProjectDetailsDraft $project,
        PrtProjectWorkItemsDetail $workItem
    ) {
        $user = Auth::user();
        $project = $this->getProject($project->project_cd);

        $workPlans =  DB::connection('pgsql_pms')
            ->table('projects.prt_project_work_plan_details as workPlan')
            ->select([
                'workPlan.id',
                'workPlan.project_cd',
                'workPlan.wid_precedence_item_cd',
                'workPlan.plan_start_date',
                'workPlan.plan_end_date',
                'workPlan.wid_id',
                'item.item_name'
            ])
            ->leftJoin('projects.prt_project_work_items_details as workItem', 'workPlan.wid_id', '=', 'workItem.id')
            ->leftJoin('projects.prm_item_of_work as item', 'workPlan.wid_precedence_item_cd', '=', 'item.item_cd')
            ->where('workPlan.project_cd', $project->project_cd)
            ->where('workPlan.wid_id', $workItem->id)			 
            ->get();

		$listOfPredessor = DB::connection('pgsql_pms')
            ->table('projects.prt_project_work_items_details as work_item')
            ->select([
                'work_item.id',
                'work_item.item_cd',
                'item.item_name'
            ])
            ->leftJoin('projects.prm_item_of_work as item', 'work_item.item_cd', '=', 'item.item_cd')
            ->where('work_item.project_cd', '=', $project->project_cd)
            ->where('work_item.item_cd', '!=', $workItem->item_cd)
            ->get();

        return view('pms.workPlan.index', compact('user', 'project', 'workItem', 'workPlans', 'listOfPredessor'));
    }

    public function store(
        Request $request,
        PrtProjectDetailsDraft $project,
        PrtProjectWorkItemsDetail $workItem
    ) {
        $validated = $request->validate([
            'wid_precedence_item_cd' => ['nullable', 'integer', 'min:0'],
            'plan_start_date' => ['required', 'date'],
            'plan_end_date' => ['required', 'date', 'after_or_equal:plan_start_date'],
        ]);

        DB::transaction(function () use ($validated, $project, $workItem) {
            PrtProjectWorkPlanDetails::create([
                'project_cd' => $project->project_cd,
                'wid_id' => $workItem->id,
                'wid_precedence_item_cd' => $validated['wid_precedence_item_cd'],
                'plan_start_date' => $validated['plan_start_date'],
                'plan_end_date' => $validated['plan_end_date'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('pms.work-plan.index', [$project->project_cd, $workItem->id])
            ->with('success', 'Work plan entry added successfully.');
    }

    public function edit(
        PrtProjectDetailsDraft $project,
        PrtProjectWorkItemsDetail $workItem,
        PrtProjectWorkPlanDetails $workPlan
    ) {
        $user = Auth::user();
        $project = $this->getProject($project->project_cd);

        return view('pms.workPlan.edit', compact('user', 'project', 'workItem', 'workPlan'));
    }

    public function update(
        Request $request,
        PrtProjectDetailsDraft $project,
        PrtProjectWorkItemsDetail $workItem,
        PrtProjectWorkPlanDetails $workPlan
    ) {
        $validated = $request->validate([
            'wid_precedence_item_cd' => ['nullable', 'integer', 'min:0'],
            'plan_start_date' => ['required', 'date'],
            'plan_end_date' => ['required', 'date', 'after_or_equal:plan_start_date'],
        ]);

        DB::transaction(function () use ($validated, $workPlan) {
            $workPlan->update([
                'wid_precedence_item_cd' => $validated['wid_precedence_item_cd'],
                'plan_start_date' => $validated['plan_start_date'],
                'plan_end_date' => $validated['plan_end_date'],
                'updated_by' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('pms.work-plan.index', [$project->project_cd, $workItem->id])
            ->with('success', 'Work plan entry updated successfully.');
    }

    public function destroy(
        PrtProjectDetailsDraft $project,
        PrtProjectWorkItemsDetail $workItem,
        PrtProjectWorkPlanDetails $workPlan
    ) {
        $workPlan->delete();

        return redirect()
            ->route('pms.work-plan.index', [$project->project_cd, $workItem->id])
            ->with('success', 'Work plan entry deleted successfully.');
    }
}
