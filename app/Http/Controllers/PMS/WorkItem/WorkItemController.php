<?php

namespace App\Http\Controllers\PMS\WorkItem;

use App\Http\Controllers\Controller;
use App\Models\PMS\Master\PrmItemOfWork;
use App\Models\PMS\PrtProjectDetailsDraft;
use App\Models\PMS\PrtProjectWorkItemsDetail;
use App\Models\PMS\PrtProjectWorkPlanDetails;											 
use App\Models\PMS\PrtProjectWorkSubItemsDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PrtProjectDetailsDraft $project)
    {
        $user = Auth::user();
        $userDept = $user->department;
        $projectCd = $project->project_cd;
        $project = DB::table('projects.prt_project_details_draft as project_details')
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
            ->leftJoin(
                'projects.prm_project_status as project_status',
                'project_details.project_status_cd',
                '=',
                'project_status.project_status_cd'
            )
            ->where('sent_for_finalize', '=', 'N')
            ->where('project_cd', '=', $projectCd)
            ->orderBy('project_cd', 'desc')
            ->get()->first();

        // master data
        $WorkItemList = PrmItemOfWork::where('dept_cd', $userDept)->orderBy('item_cd', 'asc')->get();
        // list of created data
        $projectIowDetails = DB::table('projects.prt_project_work_items_details as iow')
            ->leftJoin('projects.prm_item_of_work as item', 'iow.item_cd', '=', 'item.item_cd')
            ->leftJoin('projects.prt_project_iow_boq_mapping as mapping', 'mapping.iow_id', '=', 'iow.id')
            ->leftJoin('projects.prm_boq_items as boq', 'mapping.boq_item_id', '=', 'boq.boq_item_id')
            ->leftJoin('projects.prm_item_units AS unit', 'item.unit_cd', '=', 'unit.unit_cd')
            ->select(
                'iow.id',
                'iow.project_cd',
                'iow.item_cd',
                'iow.quantity',
                'iow.est_start_date',
                'iow.est_end_date',
                'mapping.boq_item_id',
                'boq.boq_item_name',
                'item.item_name',
                'item.unit_cd',
                'unit.unit_descr AS unit'
            )
            ->where('iow.project_cd', $project->project_cd)
            ->orderBy('item.item_cd', 'asc')
            ->get();

        foreach ($projectIowDetails as $item) {
            $item->sub_items = DB::connection('pgsql_pms')
                ->table('projects.prt_project_work_sub_items_details as d')
                ->join('projects.prm_item_sub_item_of_work as m', function ($join) {
                    $join->on('d.item_cd', '=', 'm.item_cd')
                        ->on('d.sub_item_cd', '=', 'm.sub_item_cd');
                })
                ->where('d.project_cd', $item->project_cd)
                ->where('d.item_cd', $item->item_cd)
                ->where('d.work_item_details_id', $item->id)
                ->select('m.sub_item_name')
                ->get();
        }

        return view('pms.IOW.index', compact('user', 'project', 'WorkItemList', 'projectIowDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, PrtProjectDetailsDraft $project)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PrtProjectDetailsDraft $project)
    {
        $request->validate([
            'item_cd'        => 'required',
            'quantity'       => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'sub_item_cd'    => 'nullable|array',
            'sub_item_cd.*'  => 'integer',
        ]);

        DB::connection('pgsql_pms')->transaction(function () use ($request, $project) {
            $work_item = $project->itemOfWorks()->create([
                'item_cd'        => $request->item_cd,
                'quantity'       => $request->quantity,
                'est_start_date' => $request->est_start_date,
                'est_end_date'   => $request->est_end_date,
                'created_by'     => Auth::id(),
                'updated_by'     => Auth::id(),
            ]);

            if ($request->has('sub_item_cd')) {
                foreach ($request->sub_item_cd as $sub_cd) {
                    PrtProjectWorkSubItemsDetail::create([
                        'project_cd'  => $project->project_cd,
                        'item_cd'     => $request->item_cd,
                        'sub_item_cd' => $sub_cd,
                        'created_by'  => Auth::id(),
                        'updated_by'  => Auth::id(),
                        'work_item_details_id' => $work_item->id,
                    ]);
                }
            }
        });

        return redirect()
            ->route('pms.work-item.index', $project)
            ->with('success', 'Item of Work added successfully');
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
     * @param  PrtProjectDetailsDraft  $project
     * @param  PrtProjectWorkItemsDetail  $workItem
     * @return \Illuminate\Http\Response
     */
    public function edit(PrtProjectDetailsDraft $project, PrtProjectWorkItemsDetail $workItem)
    {
        $WorkItemList = PrmItemOfWork::where('dept_cd', Auth::user()->department)->get();
        $itemOfWork = $workItem;

        $checkedSubItemCds = DB::connection('pgsql_pms')
            ->table('projects.prt_project_work_sub_items_details')
            ->where('project_cd', $project->project_cd)
            ->where('item_cd', $workItem->item_cd)
            ->where('work_item_details_id', $workItem->id)
            ->pluck('sub_item_cd')
            ->toArray();

        return view('pms.IOW.edit', compact('project', 'itemOfWork', 'WorkItemList', 'checkedSubItemCds'));
    }

    public function update(Request $request, PrtProjectDetailsDraft $project, PrtProjectWorkItemsDetail $workItem)
    {
        $request->validate([
            'item_cd'        => 'required',
            'quantity'       => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'sub_item_cd'    => 'nullable|array',
            'sub_item_cd.*'  => 'integer',
        ]);

        DB::connection('pgsql_pms')->transaction(function () use ($request, $project, $workItem) {
            PrtProjectWorkSubItemsDetail::where('project_cd', $project->project_cd)
                ->where('item_cd', $workItem->item_cd)
                ->where('work_item_details_id', $workItem->id)
                ->delete();

            $workItem->update([
                'item_cd'        => $request->item_cd,
                'quantity'       => $request->quantity,
                'est_start_date' => $request->est_start_date,
                'est_end_date'   => $request->est_end_date,
                'updated_by'     => Auth::id(),
            ]);

            if ($request->has('sub_item_cd')) {
                foreach ($request->sub_item_cd as $sub_cd) {
                    PrtProjectWorkSubItemsDetail::create([
                        'project_cd'  => $project->project_cd,
                        'item_cd'     => $request->item_cd,
                        'sub_item_cd' => $sub_cd,
                        'created_by'  => Auth::id(),
                        'updated_by'  => Auth::id(),
						'work_item_details_id' => $workItem->id,										
                    ]);
                }
            }
        });

        return redirect()
            ->route('pms.work-item.index', $project)
            ->with('success', 'Item of Work updated successfully');
    }

    public function destroy(PrtProjectDetailsDraft $project, PrtProjectWorkItemsDetail $workItem)
    {
        DB::connection('pgsql_pms')->transaction(function () use ($project, $workItem) {
			PrtProjectWorkPlanDetails::where('project_cd', $project->project_cd)
                ->where('wid_id', $workItem->id)
                ->delete();
				
            PrtProjectWorkSubItemsDetail::where('project_cd', $project->project_cd)
                ->where('item_cd', $workItem->item_cd)
                ->where('work_item_details_id', $workItem->id)
                ->delete();

            $workItem->delete();
        });

        return redirect()
            ->route('pms.work-item.index', $project)
            ->with('success', 'Item of Work deleted successfully');
    }

    public function getItemsDetail(string $id)
    {
        try {
            $items = DB::table('projects.prt_project_work_items_details AS d')
                ->leftJoin('projects.prm_item_of_work AS w', 'd.item_cd', '=', 'w.item_cd')
                ->leftJoin('projects.prm_item_units AS unit', 'w.unit_cd', '=', 'unit.unit_cd')
                ->select([
					'd.id as wid_id',	  
                    'w.item_name AS name',
                    'unit.unit_descr AS unit',
                    'd.quantity AS qty',
                    'd.item_cd AS item_cd'
                ])
                ->where('d.project_cd', $id)
                ->get();

			foreach ($items as $item) {

                $item->sub_items = DB::table('projects.prt_project_work_sub_items_details AS ps')
                    ->leftJoin(
                        'projects.prm_item_sub_item_of_work AS s',
                        'ps.sub_item_cd',
                        '=',
                        's.sub_item_cd'
                    )
                    ->where('ps.project_cd', $id)
                    ->where('ps.item_cd', $item->item_cd)
                    ->pluck('s.sub_item_name');

                $item->work_plans = DB::table('projects.prt_project_work_plan_details as workPlan')
                    ->leftJoin('projects.prm_item_of_work as item', 'workPlan.wid_precedence_item_cd', '=', 'item.item_cd')
                    ->where('workPlan.project_cd', $id)
                    ->where('workPlan.wid_id', $item->wid_id)
                    ->get([
                        'workPlan.plan_start_date',
                        'workPlan.plan_end_date',
                        'workPlan.wid_precedence_item_cd',
                        'item.item_name as precedence_item_name',
                    ]);
            }
						   
            if ($items->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'value' => []
                ]);
            }
            Log::info("Fetched " . $items->count() . " items for project_cd: $id");
            return response()->json([
                'status' => 'success',
                'value' => $items
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubItems(string $item_cd, string $id)
    {
        try {
            $subItems = DB::table('projects.prt_project_work_sub_items_details as d')
                ->select(['d.*', 'm.sub_item_name as name'])
                ->join('projects.prm_item_sub_item_of_work as m', function ($join) {
                    $join->on('d.item_cd', '=', 'm.item_cd')
                        ->on('d.sub_item_cd', '=', 'm.sub_item_cd');
                })
                ->where('d.item_cd', $item_cd)
                ->where('project_cd', $id)
                ->get();
            return response()->json([
                'status' => 'success',
                'subitems' => $subItems
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // Nitish: get available sub-items if available for a work-item
    public function subItems(string $id)
    {
        try {
            $subItems = DB::table('projects.prm_item_sub_item_of_work as sub_item')
                ->select([
                    'sub_item.sub_item_cd',
                    'sub_item.sub_item_name',
                    'work_item.item_name',
                ])
                ->leftJoin('projects.prm_item_of_work as work_item', 'work_item.item_cd', '=', 'sub_item.item_cd')
                ->where('sub_item.item_cd', $id)
                ->get();
            return response()->json([
                'status' => 'success',
                'subitems' => $subItems
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
