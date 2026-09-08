<?php

namespace App\Http\Controllers\PMS\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Road\AssetRoadDetail;
use GuzzleHttp\Client;
use App\Models\AssetRoadChainageMapping;
use App\Models\Building\AssetBuildingDetail;
class ProjectCompletionReportContrller extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $userDeptCd = $user->department;
            $userDesgCd = $user->designation;
            Log::info("userDesgCd: " . $userDesgCd);
            $userMapping = session('userMapping');
            $array = json_decode(json_encode($userMapping), true);

            $user_zone_cd = $array["zone_cd"];
            $user_circle_cd = $array["circle_cd"];
            $user_division_cd = $array["division_cd"];
            $user_sub_division_cd = $array["sub_division_cd"];
            $user_office_type_cd = $array["office_type_cd"];
            $user_office_cd = $array["office_cd"];
            $scopeColumn = null;
            $scopeValue = null;
            $hasWorkflowAccess = false;
            Log::info("userDesgCd: " . $userDesgCd);
            switch ($userDesgCd) {
                case 8:
                case 11:
                case 16:
                case 21: #all depts SDOs
                    $scopeColumn = 'p.sub_division_cd';
                    $scopeValue = $user_sub_division_cd;
                    $hasWorkflowAccess = true;
                    $itemProgress = DB::table('prt_project_details as p')
                        ->leftJoin('prt_project_work_items_details as w', 'w.project_cd', '=', 'p.project_cd')

                        ->leftJoin('prt_project_progress_details_work_item_wise as pr', function ($join) {
                            $join->on('pr.item_id', '=', 'w.id')
                                ->where('pr.status', '=', 'A');
                        })
                        ->select(
                            'p.project_cd',
                            DB::raw('
                                    CASE 
                                        WHEN w.quantity > 0 
                                        THEN (COALESCE(SUM(pr.quantity_done),0)/w.quantity)*100
                                        ELSE 0
                                    END as item_progress
                                ')
                        )
                        ->where('p.is_published', "Y")
                        ->groupBy('p.project_cd', 'p.project_name', 'w.id', 'w.quantity');
                    $query = DB::query()
                        ->fromSub($itemProgress, 't')
                        ->join('prt_project_details as p', 'p.project_cd', '=', 't.project_cd')
                        ->leftJoin('prm_project_types as pt', 'p.project_type_cd', '=', 'pt.proj_type_cd')
                        ->leftJoin('asset_master_divisions as d', 'p.division_cd', '=', 'd.division_cd')
                        ->leftJoin('asset_master_sub_divisions as sd', 'p.sub_division_cd', '=', 'sd.sub_div_cd')
                        ->select(
                            'p.project_cd',
                            'p.project_name',
                            'p.project_start_date',
                            'p.project_end_date',
                            'd.division_name',
                            'sd.sub_div_name',
                            'pt.proj_type_descr',
                            DB::raw("
                                    CASE 
                                        WHEN MIN(t.item_progress) = 100 THEN 100
                                        ELSE ROUND(AVG(t.item_progress), 2)
                                    END AS progress_percent
                                ")
                        )
                        ->where('p.owner_dept_cd', $userDeptCd)
                        ->where('p.sub_division_cd', $user_sub_division_cd)
                        ->whereNotExists(function ($q) {
                            $q->select(DB::raw(1))
                                ->from('prt_project_report_movements as prm')
                                ->whereColumn('prm.project_cd', 'p.project_cd');
                        })
                        ->groupBy(
                            'p.project_cd',
                            'p.project_name',
                            'p.project_start_date',
                            'p.project_end_date',
                            'd.division_name',
                            'sd.sub_div_name',
                            'pt.proj_type_descr'
                        )
                        ->havingRaw('MIN(t.item_progress) = 100')
                        ->get();
                    Log::info($query->toArray());
                    break;

                case 6:
                case 12:
                case 17:
                case 22: #all depts EE
                    $scopeColumn = 'p.division_cd';
                    $scopeValue = $user_division_cd;
                    $hasWorkflowAccess = true;
                    $query = $this->getPendingWorkflowProjects(
                        $userDeptCd,
                        $userDesgCd,
                        $scopeColumn,
                        $scopeValue
                    );
                    Log::info($query->toArray());
                    break;

                case 4:
                case 13:
                case 18:
                case 23: #all depts SE
                    $scopeColumn = 'd.circle_cd';
                    $scopeValue = $user_circle_cd;
                    $hasWorkflowAccess = true;
                    $query = $this->getPendingWorkflowProjects(
                        $userDeptCd,
                        $userDesgCd,
                        $scopeColumn,
                        $scopeValue
                    );
                    Log::info($query->toArray());
                    break;

                case 2:
                case 15:
                case 20:
                case 25: #all depts topmost officials
                    $hasWorkflowAccess = true;
                    $query = $this->getPendingWorkflowProjects(
                        $userDeptCd,
                        $userDesgCd
                    );
                    Log::info($query->toArray());
                    break;
            }

            if ($hasWorkflowAccess) {
                $reverted_projects = $this->getPendingWorkflowProjects(
                    $userDeptCd,
                    $userDesgCd,
                    $scopeColumn,
                    $scopeValue,
                    2
                );

                $rejected_projects = $this->getPendingWorkflowProjects(
                    $userDeptCd,
                    $userDesgCd,
                    $scopeColumn,
                    $scopeValue,
                    4,
                    false
                );
            }
        } catch (Exception $e) {
            Log::error("Error: ", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
        $query = $query ?? collect();
        $reverted_projects = $reverted_projects ?? collect();
        $rejected_projects = $rejected_projects ?? collect();
        return view('pms.report.projectCompletionReport', compact(
            'query',
            'reverted_projects',
            'rejected_projects',
            'userDesgCd'
        ));
    }

    private function getPendingWorkflowProjects(
        $userDeptCd,
        $userDesgCd,
        $scopeColumn = null,
        $scopeValue = null,
        $movementStatus = 1,
        $addressedToUser = true
    ) {
        $itemProgress = DB::table('prt_project_details as p')
            ->leftJoin('prt_project_work_items_details as w', 'w.project_cd', '=', 'p.project_cd')
            ->leftJoin('prt_project_progress_details_work_item_wise as pr', function ($join) {
                $join->on('pr.item_id', '=', 'w.id')
                    ->where('pr.status', '=', 'A');
            })
            ->select(
                'p.project_cd',
                DB::raw('
                    CASE
                        WHEN w.quantity > 0
                        THEN (COALESCE(SUM(pr.quantity_done),0)/w.quantity)*100
                        ELSE 0
                    END as item_progress
                ')
            )
            ->where('p.is_published', 'Y')
            ->groupBy('p.project_cd', 'p.project_name', 'w.id', 'w.quantity');

        return DB::query()
            ->fromSub($itemProgress, 't')
            ->join('prt_project_details as p', 'p.project_cd', '=', 't.project_cd')
            ->join('prt_project_report_movements as prm', 'prm.project_cd', '=', 'p.project_cd')
            ->leftJoin('prm_project_types as pt', 'p.project_type_cd', '=', 'pt.proj_type_cd')
            ->leftJoin('asset_master_divisions as d', 'p.division_cd', '=', 'd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sd', 'p.sub_division_cd', '=', 'sd.sub_div_cd')
            ->select(
                'p.project_cd',
                'p.project_name',
                'p.project_start_date',
                'p.project_end_date',
                DB::raw("(
                    SELECT completion_prm.prj_completion_date
                    FROM prt_project_report_movements AS completion_prm
                    WHERE completion_prm.report_id = prm.report_id
                        AND completion_prm.prj_completion_date IS NOT NULL
                    ORDER BY completion_prm.id DESC
                    LIMIT 1
                ) AS prj_completion_date"),
                'd.division_name',
                'sd.sub_div_name',
                'pt.proj_type_descr',
                DB::raw("
                    CASE
                        WHEN MIN(t.item_progress) = 100 THEN 100
                        ELSE ROUND(AVG(t.item_progress), 2)
                    END AS progress_percent
                ")
            )
            ->where('p.owner_dept_cd', $userDeptCd)
            ->when($scopeColumn, function ($query) use ($scopeColumn, $scopeValue) {
                $query->where($scopeColumn, $scopeValue);
            })
            ->where('prm.dept_id', $userDeptCd)
            ->where('prm.movement_status', $movementStatus)
            ->when($addressedToUser, function ($query) use ($userDesgCd) {
                $query->where('prm.to_desg_id', $userDesgCd);
            })
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('prt_project_report_movements as newer_prm')
                    ->whereColumn('newer_prm.report_id', 'prm.report_id')
                    ->whereColumn('newer_prm.id', '>', 'prm.id');
            })
            ->groupBy(
                'p.project_cd',
                'p.project_name',
                'p.project_start_date',
                'p.project_end_date',
                'prm.report_id',
                'd.division_name',
                'sd.sub_div_name',
                'pt.proj_type_descr'
            )
            ->get();
    }

    public function getMovementRemarks($projectCd)
    {
        $userDeptCd = (int) Auth::user()->department;

        $projectExists = DB::table('prt_project_details')
            ->where('project_cd', $projectCd)
            ->where('owner_dept_cd', $userDeptCd)
            ->exists();

        if (!$projectExists) {
            abort(404);
        }

        $remarks = DB::table('prt_project_report_movements as prm')
            ->leftJoin('users as u', 'u.id', '=', 'prm.from_user_id')
            ->leftJoin('desg_details as dd', 'dd.id', '=', 'prm.from_desg_id')
            ->leftJoin('prt_project_report_movement_status as ms', 'ms.id', '=', 'prm.movement_status')
            ->where('prm.project_cd', $projectCd)
            ->where('prm.dept_id', $userDeptCd)
            ->orderBy('prm.id')
            ->get([
                'u.name as official_name',
                'dd.desg_name as designation_name',
                'ms.descr as movement_status',
                'prm.officials_remark',
                'prm.created_at',
            ])
            ->map(function ($movement) {
                $movement->movement_date = $movement->created_at
                    ? Carbon::parse($movement->created_at)->format('d-M-Y h:i A')
                    : '-';
                unset($movement->created_at);

                return $movement;
            });

        return response()->json($remarks);
    }

    public function storeMovement(Request $request)
    {
        $validated = $request->validate([
            'project_cd' => ['required', 'string', 'max:50'],
            'action_type' => ['required', 'integer', 'in:1,2,3,4'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'completion_date' => ['nullable', 'date'],
        ]);

        $user = Auth::user();
        $userId = Auth::id();
        $userDeptCd = (int) $user->department;
        $userDesgCd = (int) $user->designation;
        $actionType = (int) $validated['action_type'];
        $projCompletedDate = $validated['completion_date'] ?? null;
        $sdoDesignationIds = [8, 11, 16, 21];

        $toDesgId = DB::transaction(function () use ($validated, $userId, $userDeptCd, $userDesgCd, $actionType, $projCompletedDate, $sdoDesignationIds) {
            $project = DB::table('prt_project_details')
                ->where('project_cd', $validated['project_cd'])
                ->where('owner_dept_cd', $userDeptCd)
                ->lockForUpdate()
                ->first(['project_cd']);

            if (!$project) {
                throw ValidationException::withMessages([
                    'project_cd' => 'The selected project is not available for your department.',
                ]);
            }

            $currentStep = DB::table('prm_project_workflow_steps')
                ->where('dept_id', $userDeptCd)
                ->where('desg_id', $userDesgCd)
                ->first();

            if (!$currentStep) {
                throw ValidationException::withMessages([
                    'action_type' => 'No completion-report workflow step is configured for your designation.',
                ]);
            }

            $allowedActions = [
                1 => [1],
                2 => [1, 2],
                3 => [1, 2],
                4 => [2, 3, 4],
            ];

            if (!in_array($actionType, $allowedActions[(int) $currentStep->step_no] ?? [], true)) {
                throw ValidationException::withMessages([
                    'action_type' => 'The selected action is not allowed at your workflow step.',
                ]);
            }

            if (in_array($userDesgCd, $sdoDesignationIds, true) && empty($projCompletedDate)) {
                throw ValidationException::withMessages([
                    'completion_date' => 'The project completion date is required.',
                ]);
            }

            $report = DB::table('prt_project_report_details')
                ->where('project_cd', $validated['project_cd'])
                ->lockForUpdate()
                ->first();

            $latestMovement = null;

            if ($report) {
                $latestMovement = DB::table('prt_project_report_movements')
                    ->where('report_id', $report->id)
                    ->orderByDesc('id')
                    ->first();

                if ($latestMovement && in_array((int) $latestMovement->movement_status, [3, 4], true)) {
                    throw ValidationException::withMessages([
                        'action_type' => 'This completion report is already closed.',
                    ]);
                }

                if ($latestMovement && (int) $latestMovement->to_desg_id !== $userDesgCd) {
                    throw ValidationException::withMessages([
                        'action_type' => 'This completion report is not pending at your designation.',
                    ]);
                }

                $reportId = $report->id;
            } else {
                if ((int) $currentStep->step_no !== 1) {
                    throw ValidationException::withMessages([
                        'action_type' => 'Only the first workflow step can initiate a completion report.',
                    ]);
                }

                $reportId = DB::table('prt_project_report_details')->insertGetId([
                    'project_cd' => $validated['project_cd'],
                    'created_by' => $userId,
                    'created_at' => now(),
                ]);
            }

            $toDesgId = null;
            $movementCompletionDate = in_array($userDesgCd, $sdoDesignationIds, true)
                ? $projCompletedDate
                : DB::table('prt_project_report_movements')
                    ->where('report_id', $reportId)
                    ->whereNotNull('prj_completion_date')
                    ->orderByDesc('id')
                    ->value('prj_completion_date');

            if (in_array($actionType, [1, 2], true)) {
                $targetStepNo = (int) $currentStep->step_no + ($actionType === 1 ? 1 : -1);
                $targetStep = DB::table('prm_project_workflow_steps')
                    ->where('wf_id', $currentStep->wf_id)
                    ->where('dept_id', $userDeptCd)
                    ->where('step_no', $targetStepNo)
                    ->first();

                if (!$targetStep) {
                    throw ValidationException::withMessages([
                        'action_type' => 'The target workflow step is not configured.',
                    ]);
                }

                $toDesgId = $targetStep->desg_id;
            }

            DB::table('prt_project_report_movements')->insert([
                'report_id' => $reportId,
                'project_cd' => $validated['project_cd'],
                'wf_id' => $currentStep->wf_id,
                'from_user_id' => $userId,
                'from_desg_id' => $userDesgCd,
                'to_desg_id' => $toDesgId,
                'movement_status' => $actionType,
                'dept_id' => $userDeptCd,
                'officials_remark' => $validated['remarks'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
                'prj_completion_date' => $movementCompletionDate,
            ]);

            if ($actionType === 3) {
                if (empty($movementCompletionDate)) {
                    throw ValidationException::withMessages([
                        'completion_date' => 'The project completion date is required before approval.',
                    ]);
                }

                DB::table('prt_project_details')
                    ->where('project_cd', $validated['project_cd'])
                    ->where('owner_dept_cd', $userDeptCd)
                    ->update([
                        'prj_completion_date' => $movementCompletionDate,
                        'project_status_cd' => 2,
                    ]);

                $this->createAssetPoolAfterProjectCompletion($validated['project_cd']);
            }
            return $toDesgId;
        });

        if (in_array($actionType, [1, 2], true)) {
            $toDesignation = DB::table('desg_details')
                ->where('id', $toDesgId)
                ->value('desg_name') ?? 'the designated official';

            $successMessage = $actionType === 1
                ? "Completion report Verified and Forwarded to {$toDesignation} successfully."
                : "Completion report Verified and Reverted Back to {$toDesignation} successfully.";
        } else {
            $actionName = [3 => 'approved', 4 => 'rejected'][$actionType];
            $successMessage = "Completion report {$actionName} successfully.";

        }

        return redirect()
            ->route('project.pms-completion-report')
            ->with('success', $successMessage);
    }

    function createAssetPoolAfterProjectCompletion($proj_cd)
    {
        Log::info("Project is completed. Updating project status...");
        $userid = Auth()->user()->id;
        //get the detials of project and add the details in asset_plan table
        $proj_details = DB::table("prt_project_details as P")
            ->select("P.project_cd", "P.project_type_cd", "P.parent_asset_cd", "P.division_cd", "P.sub_division_cd", "P.owner_dept_cd", "P.others")
            ->where("P.project_cd", $proj_cd)
            ->first();
        $quantities = DB::table("prt_project_work_items_details as p")
            ->select('p.item_cd', DB::raw('SUM(p.quantity) as total_qty'))
            ->where('p.project_cd', $proj_cd)
            ->whereIn('p.item_cd', [8, 9, 11])
            ->groupBy('p.item_cd')
            ->pluck('total_qty', 'item_cd');

        $new_culvert_qty = $quantities[8] ?? 0;
        $new_bridge_qty = $quantities[9] ?? 0;
        $new_retain_wall_qty = $quantities[11] ?? 0;
        $div_cd = null;
        $sub_div_cd = null;
        $owner_dept = null;
        if ($proj_details) {
            $div_cd = $proj_details->division_cd;
            $sub_div_cd = $proj_details->sub_division_cd;
            $owner_dept = $proj_details->owner_dept_cd;
        }
        if ($proj_details) {
            Log::info("Project details fetched. Inserting asset plan...");
            Log::info("Project Type: " . $proj_details->project_type_cd);

            $assetPlanExists = DB::table('prt_project_asset_plan')
                ->where('project_cd', $proj_cd)
                ->exists();

            if ($assetPlanExists) {
                Log::info("Asset plan already exists for project: " . $proj_cd);
                return;
            }

            $others = json_decode($proj_details->others, true); // true = array
            switch ($proj_details->project_type_cd) {
                case 'NEW':
                    if ($owner_dept == 14 || $owner_dept == 3) {
                        $new_temp_road_id = $others['new_road_id'] ?? null;
                        $new_road_name = $others['new_road_name'] ?? null;
                        $new_road_length = $others['new_road_length'] ?? 0;
                        $new_road_type = $others['road_type'] ?? null;
                        $new_road_owner = $others['road_owner'] ?? null;
                        $new_road_category = $others['road_category'] ?? null;
                        $number_of_bridge = (int) $new_bridge_qty;
                        $number_of_culvert = (int) $new_culvert_qty;
                        $number_of_retain_wall = (int) $new_retain_wall_qty;
                        // To write function for Createing New Road createNewRoad();
                        $new_road_id = $this->createNewRoadFromProject($proj_cd, $new_temp_road_id, $new_road_name, $new_road_length, $new_road_type, $new_road_owner, $new_road_category, $div_cd, $owner_dept, 'NEW', null, null);
                        if ($number_of_bridge > 0)
                            DB::table('prt_project_asset_plan')->insert([
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => '1',
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => $number_of_bridge,
                                'parent_asset_cd' => $new_road_id,
                                'status' => '0',
                                'group_type_cd' => 'NEW',
                                'remarks' => 'New Bridge, New Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ]);

                        if ($number_of_culvert > 0)
                            DB::table('prt_project_asset_plan')->insert([
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => '0',
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => $number_of_culvert,
                                'parent_asset_cd' => $new_road_id,
                                'status' => '0',
                                'group_type_cd' => 'NEW',
                                'remarks' => 'New Culvert, New Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ]);

                        if ($number_of_retain_wall > 0)
                            DB::table('prt_project_asset_plan')->insert([
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => '12',
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => $number_of_retain_wall,
                                'parent_asset_cd' => $new_road_id,
                                'status' => '0',
                                'group_type_cd' => 'NEW',
                                'remarks' => 'New Retaining Wall, New Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ]);
                    }
                    if ($owner_dept == 6) {//Hosing Department
                        $tech_type_cd = $others['tech_type_cd'] ?? null;
                        $bld_lat = $others['new_building_lat'] ?? null;
                        $bld_lng = $others['new_building_lng'] ?? 0;
                        $bld_class_cd = $others['new_building_class_cd'] ?? null;
                        $bld_location_cd = $others['new_building_location_cd'] ?? null;
                        $bld_maintain_by_npwd = $others['new_building_maintain_by_npwd'] ?? null;
                        $bld_type_cd = $others['building_type_cd'] ?? null;
                        $bld_owning_dept_cd = $others['asset_owning_dept_cd'] ?? null;
                        $new_bld_id = $this->createNewBuildingFromProject($proj_cd, $tech_type_cd, $bld_owning_dept_cd, $bld_type_cd, $bld_lat, $bld_lng, $bld_class_cd, $bld_location_cd, $bld_maintain_by_npwd, $div_cd, $sub_div_cd);
                    }

                    break;
                case 'UPG':
                    $data = [];
                    if ($owner_dept == 14 || $owner_dept == 3) {
                        $basejson = $others['upgradation'] ?? [];
                        $new_temp_road_id = $basejson['merged_road_id'] ?? '0';
                        $new_road_name = $basejson['merged_road_name'] ?? null;
                        $new_road_length = (float) ($basejson['merged_road_length'] ?? 0);
                        $new_road_type = $basejson['merged_road_type'] ?? null;
                        $new_road_owner = $basejson['merged_road_owner'] ?? null;
                        $new_road_category = $basejson['merged_road_category'] ?? null;

                        $new_bridges = (int) $new_bridge_qty;
                        $new_culverts = (int) $new_culvert_qty;
                        $new_retaining_walls = (float) $new_retain_wall_qty;
                        $upgraded_roads = $basejson['upgraded_roads'] ?? [];
                        $upgraded_asset_dtls = $basejson['upgraded_asset_dtls'] ?? [];


                        $new_road_id = null;
                        if ($new_temp_road_id != null) {
                            $new_road_id = $this->createNewRoadFromProject($proj_cd, $new_temp_road_id, $new_road_name, $new_road_length, $new_road_type, $new_road_owner, $new_road_category, $div_cd, $owner_dept, 'UPG', $upgraded_roads, $upgraded_asset_dtls);
                        }

                        if ($new_bridges > 0)
                            $data[] = [
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => '1',
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => $new_bridges,
                                'parent_asset_cd' => $new_road_id,
                                'upgraded_asset_cd' => null,
                                'maintained_asset_cd' => null,
                                'status' => '0',
                                'group_type_cd' => 'UPG_NEW',
                                'remarks' => 'New Bridge, Upgradation Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ];

                        if ($new_culverts > 0)
                            $data[] = [
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => '0',
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => $new_culverts,
                                'parent_asset_cd' => $new_road_id,
                                'upgraded_asset_cd' => null,
                                'maintained_asset_cd' => null,
                                'status' => '0',
                                'group_type_cd' => 'UPG_NEW',
                                'remarks' => 'New Culvert, Upgradation Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ];

                        if ($new_retaining_walls > 0)
                            $data[] = [
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => '12',
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => $new_retaining_walls,
                                'parent_asset_cd' => $new_road_id,
                                'upgraded_asset_cd' => null,
                                'maintained_asset_cd' => null,
                                'status' => '0',
                                'group_type_cd' => 'UPG_NEW',
                                'remarks' => 'New Retaining Wall, Upgradation Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ];


                        foreach ($upgraded_asset_dtls as $asset) {
                            $parent_asset_id = $asset['parent_asset_id'] ?? null;
                            $bridges = $asset['bridges'] ?? [];
                            $culverts = $asset['culverts'] ?? [];
                            $retaining_walls = $asset['retaining_walls'] ?? [];
                            foreach ($bridges as $sub_aset_cd) {
                                $data[] = [
                                    'project_cd' => $proj_details->project_cd,
                                    'project_type_cd' => $proj_details->project_type_cd,
                                    'work_item_id' => null,
                                    'asset_type_cd' => '1',
                                    'temp_asset_cd' => null,
                                    'temp_asset_name' => null,
                                    'new_asset_length' => null,
                                    'no_of_new_asset' => null,
                                    'parent_asset_cd' => $new_road_id,
                                    'upgraded_asset_cd' => $sub_aset_cd,
                                    'maintained_asset_cd' => null,
                                    'status' => '0',
                                    'group_type_cd' => 'UPG_REDIFINE',
                                    'remarks' => 'Upgradation Of Existing Bridge, Upgradation Project Type',
                                    'created_at' => now(),
                                    'created_by' => $userid
                                ];
                            }

                            foreach ($culverts as $sub_aset_cd) {
                                $data[] = [
                                    'project_cd' => $proj_details->project_cd,
                                    'project_type_cd' => $proj_details->project_type_cd,
                                    'work_item_id' => null,
                                    'asset_type_cd' => '0',
                                    'temp_asset_cd' => null,
                                    'temp_asset_name' => null,
                                    'new_asset_length' => null,
                                    'no_of_new_asset' => null,
                                    'parent_asset_cd' => $new_road_id,
                                    'upgraded_asset_cd' => $sub_aset_cd,
                                    'maintained_asset_cd' => null,
                                    'status' => '0',
                                    'group_type_cd' => 'UPG_REDIFINE',
                                    'remarks' => 'Upgradation Of Existing Culvert, Upgradation Project Type',
                                    'created_at' => now(),
                                    'created_by' => $userid
                                ];
                            }


                            foreach ($retaining_walls as $sub_aset_cd) {
                                $data[] = [
                                    'project_cd' => $proj_details->project_cd,
                                    'project_type_cd' => $proj_details->project_type_cd,
                                    'work_item_id' => null,
                                    'asset_type_cd' => '12',
                                    'temp_asset_cd' => null,
                                    'temp_asset_name' => null,
                                    'new_asset_length' => null,
                                    'no_of_new_asset' => null,
                                    'parent_asset_cd' => $new_road_id,
                                    'upgraded_asset_cd' => $sub_aset_cd,
                                    'maintained_asset_cd' => null,
                                    'status' => '0',
                                    'group_type_cd' => 'UPG_REDIFINE',
                                    'remarks' => 'Upgradation Of Existing Reatining Wall, Upgradation Project Type',
                                    'created_at' => now(),
                                    'created_by' => $userid
                                ];
                            }
                        }
                        if (!empty($data)) {
                            DB::table('prt_project_asset_plan')->insert($data);
                        }
                    }

                    break;
                case 'MTN':
                    $data = [];
                    if ($owner_dept == 14 || $owner_dept == 3) {
                        Log::info("Processing Maintenance Project Type...");
                        $basejson = $others['maintenance'] ?? [];
                        $asset_dtls = $basejson['asset_dtls'] ?? [];
                        $asset_type_cd = $asset_dtls["asset_type_cd"] ?? null;
                        $sub_asset_dtls = $basejson['sub_asset_dtls'] ?? [];

                        Log::info("asset_dtls: " . json_encode($asset_dtls));
                        Log::info("asset_type_cd :" . $asset_type_cd);
                        Log::info("sub_asset_dtls" . json_encode($sub_asset_dtls));

                        foreach ($asset_dtls as $asset_cd) {
                            Log::info("Has Assets");
                            $data[] = [
                                'project_cd' => $proj_cd,
                                'project_type_cd' => $proj_details->project_type_cd,
                                'work_item_id' => null,
                                'asset_type_cd' => $asset_type_cd,
                                'temp_asset_cd' => null,
                                'temp_asset_name' => null,
                                'new_asset_length' => null,
                                'no_of_new_asset' => null,
                                'parent_asset_cd' => null,
                                'upgraded_asset_cd' => null,
                                'maintained_asset_cd' => $asset_cd,
                                'status' => '0',
                                'group_type_cd' => 'MTN',
                                'remarks' => 'Maintenance of Road, Under Maintenance Project Type',
                                'created_at' => now(),
                                'created_by' => $userid
                            ];
                        }

                        foreach ($sub_asset_dtls as $group) {
                            $asset_type_cd = $group['sub_asset_type_cd'] ?? null;
                            $sub_list = $group['sub_asset_list'] ?? [];
                            $subAssetParentCD = null;
                            foreach ($sub_list as $sub_asset_cd) {
                                Log::info("sub_asset_cd : " . json_encode($sub_asset_cd));
                                if ($asset_type_cd === '0')
                                    $subAssetParent = DB::table('asset_road_cdwork_details as sba')
                                        ->select('sba.rd_system_id')
                                        ->where('sba.rd_cdwork_cd', $sub_asset_cd)
                                        ->first();
                                if ($asset_type_cd === '1')
                                    $subAssetParent = DB::table('asset_road_bridge_details as sba')
                                        ->select('sba.rd_system_id')
                                        ->where('sba.rd_bridge_cd', $sub_asset_cd)
                                        ->first();
                                if ($asset_type_cd === '2')
                                    $subAssetParent = DB::table('asset_road_pavement_details as sba')
                                        ->select('sba.rd_system_id')
                                        ->where('sba.rd_pavement_cd', $sub_asset_cd)
                                        ->first();
                                if ($asset_type_cd === '12')
                                    $subAssetParent = DB::table('asset_protection_wall_details as sba')
                                        ->select('sba.rd_system_id')
                                        ->where('sba.protection_wall_cd', $sub_asset_cd)
                                        ->first();
                                $subAssetParentCD = $subAssetParent->rd_system_id ?? null;
                                $data[] = [
                                    'project_cd' => $proj_cd,
                                    'project_type_cd' => $proj_details->project_type_cd,
                                    'work_item_id' => null,
                                    'asset_type_cd' => $asset_type_cd,
                                    'temp_asset_cd' => null,
                                    'temp_asset_name' => null,
                                    'new_asset_length' => null,
                                    'no_of_new_asset' => null,
                                    'parent_asset_cd' => $subAssetParentCD,
                                    'upgraded_asset_cd' => null,
                                    'maintained_asset_cd' => $sub_asset_cd,
                                    'status' => '0',
                                    'group_type_cd' => 'MTN',
                                    'remarks' => 'Maintenance of Sub Asset, Under Maintenance Project Type',
                                    'created_at' => now(),
                                    'created_by' => $userid
                                ];
                            }
                        }
                    } //end if
                    Log::info("data : " . json_encode($data));
                    if (!empty($data)) {
                        DB::table('prt_project_asset_plan')->insert($data);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    function createNewRoadFromProject($proj_cd, $new_temp_road_id, $rd_name, $rd_len, $rd_type, $rd_owner, $rd_catg, $div_cd, $owner_dept, $proj_type, $upgraded_roads, $upgraded_sub_asset_dtls)
    {
        $connection = DB::connection();
        $connection->beginTransaction();

        try {
            $userMapping = session('userMapping');
            $array = json_decode(json_encode($userMapping), true);

            $user_zone_cd = $array["zone_cd"];
            $user_circle_cd = $array["circle_cd"];
            $user_division_cd = $array["division_cd"];
            $user_sub_division_cd = $array["sub_division_cd"];
            $user_office_type_cd = $array["office_type_cd"];
            $user_office_cd = $array["office_cd"];
            $divShrtCd = 'NL';
            $division_name = null;
            $zone_cd = null;
            if ($div_cd !== null) {
                $divDetails = DB::table('asset_master_divisions')
                    ->select('div_short_code', 'division_cd', 'division_name', 'zone_cd')
                    ->where('division_cd', $div_cd)
                    ->get()->first();
                if (!$divDetails) {
                    throw new Exception('Division details not found for the selected project.');
                }
                $divShrtCd = $divDetails->div_short_code;
                $division_name = $divDetails->division_name;
                $zone_cd = $divDetails->zone_cd;
            }
            $categoryDetails = DB::table('asset_master_road_category')
                ->select('rd_catg_short_code', 'rd_catg_descr')
                ->where('rd_catg_cd', $rd_catg)
                ->get()->first();
            if (!$categoryDetails) {
                throw new Exception('Road category details not found for the selected project.');
            }
            $rd_catg_descr = $categoryDetails->rd_catg_descr;

            $system_id = DB::table('asset_rd_system_id_running_no')
                ->select('start_no', 'end_no', 'current_running_no', 'expired', 'rd_catg_short_code')
                ->where('user_type', '=', 'P')
                ->where('rd_catg_short_code', $categoryDetails->rd_catg_short_code)
                ->lockForUpdate()
                ->get()->first();
            if (!$system_id) {
                throw new Exception('Road system id running number is not configured for this road category.');
            }

            $catShrtCd = $categoryDetails->rd_catg_short_code ? $categoryDetails->rd_catg_short_code : 'YY';
            $current_number = $system_id->current_running_no;
            $curr_sys_id = str_pad($current_number, 4, '0', STR_PAD_LEFT);
            $road_system_id = $divShrtCd . $catShrtCd . $curr_sys_id;

            $totalRoadLength = 0;
            $assetPlanId = $connection->table('projects.prt_project_asset_plan')->insertGetId([
                'project_cd' => $proj_cd,
                'project_type_cd' => "NEW",
                'work_item_id' => null,
                'asset_type_cd' => '10',
                'temp_asset_cd' => null,
                'temp_asset_name' => null,
                'new_asset_length' => null,
                'no_of_new_asset' => 1,
                'parent_asset_cd' => null,
                'status' => '1',
                'group_type_cd' => 'NEW',
                'remarks' => 'New Road, New Project Type',
                'created_at' => now(),
                'created_by' => Auth::id()
            ]);
            $roadData = [
                'rd_system_id' => $road_system_id,
                'rd_category_cd' => $rd_catg,
                'rd_number' => $road_system_id,
                'rd_name' => $rd_name,
                'rd_type_cd' => $rd_type,
                'road_length' => $rd_len,
                'rd_owner_cd' => $rd_owner,
                'road_created_at_office_type' => $user_office_type_cd,
                'road_created_at_office_cd' => $user_office_cd,
                'road_type' => $owner_dept == '14' ? 'SR' : 'NH',
                'division_name' => $division_name,
                'division_cd' => $div_cd,
                'asset_plan_id' => $assetPlanId,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ];
            // Keep the asset-plan and road inserts on the same PDO connection so
            // the road foreign key can see the uncommitted asset-plan row.
            $create_obj = new AssetRoadDetail();
            $create_obj->setConnection($connection->getName());
            $create_obj->fill($roadData);
            $create_obj->save();
            $created_road_system_id = null;
            if ($create_obj) {

                $chainageData = [
                    'rd_system_id' => $road_system_id,
                    'chainage_from' => '0',
                    'chainage_to' => $rd_len,
                    'zone_cd' => $zone_cd,
                    'circle_cd' => null,
                    'division_cd' => null,
                    'sub_division_cd' => null,
                    'remaining_chainage_length' => '0',
                    'chainage_step_id' => '0',
                    'chainage_created_at_office_cd' => $user_office_cd,
                    'chainage_created_by' => Auth::id(),
                    'calculated_length' => $rd_len,
                    'chainage_created_at' => 'HQ'
                ];
                $chainageStatus = AssetRoadChainageMapping::create($chainageData);
                $created_road_system_id = $create_obj->rd_system_id;
                DB::table('asset_rd_system_id_running_no')
                    ->where('user_type', 'P')
                    ->where('rd_catg_short_code', $categoryDetails->rd_catg_short_code)
                    ->update([
                        'current_running_no' => $current_number + 1,
                        'expired' => 'N'
                    ]);

                $kmlrecord = DB::table('asset_road_document_kml_file_details')
                    ->where('project_cd', $proj_cd)
                    // ->where('rd_system_id', $new_temp_road_id)
                    ->first();

                $file_path = $kmlrecord?->file_path;
                $geojson_file_path = $kmlrecord->geojson_file_path;
                $newPath = dirname($geojson_file_path) . DIRECTORY_SEPARATOR . $road_system_id . ".geojson";
                DB::table('asset_road_document_kml_file_details')
                    ->where('project_cd', $proj_cd)
                    ->update([
                        'rd_system_id' => $road_system_id,
                        'geojson_file_path' => $newPath,
                        'updated_at' => now(),
                    ]);
                //Need to Delete the geojson file named after project_cdn-- Start
                $oldgeojsonFilePath = dirname($geojson_file_path) . DIRECTORY_SEPARATOR . $proj_cd . ".geojson";
                if (File::exists($oldgeojsonFilePath)) {
                    File::delete($oldgeojsonFilePath);
                }
                //Need to Delete the geojson file named after project_cdn-- End
                //Call POST API To convert the KML file to Shape File
                $kml_file_api = config('customconfigpath.KML_FILE_CONVERT_API');
                // Create a Guzzle HTTP client
                $client = new Client();

                // Make a POST request to the API
                $response = $client->request('POST', $kml_file_api, [
                    'json' => [
                        'uid' => auth()->id(),
                        'kml_file_path' => $file_path,
                        'road_id' => $road_system_id,
                        'road_name' => $rd_name,
                        'road_length' => $rd_len,
                        'road_category' => $rd_catg_descr,
                        'division_cd' => $div_cd,
                        'division_name' => $division_name
                    ]
                ]);
                // $json_data= json_decode($response->getBody()->getContents());
                $json_data = json_decode($response->getBody());
                if ($json_data->status == true) {
                    DB::table('asset_road_details')
                        ->where('rd_system_id', $road_system_id)
                        ->update([
                            'lat' => $json_data->center_lat,
                            'lng' => $json_data->center_lng
                        ]);
                } else {

                }

                if ($proj_type == "UPG") {
                    $this->moveRoadsFromMainTableToHist($upgraded_roads, $road_system_id, $upgraded_sub_asset_dtls);
                }
            }

            $connection->commit();
            return $road_system_id;
        } catch (\Throwable $e) {
            if ($connection->transactionLevel() > 0) {
                $connection->rollBack();
            }

            Log::error('Road creation from completed project failed.', [
                'project_cd' => $proj_cd,
                'temp_road_id' => $new_temp_road_id,
                'project_type' => $proj_type,
                'asset_plan_id' => $assetPlanId ?? null,
                'road_system_id' => $road_system_id ?? null,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            throw $e;
        }
    }

    function createNewBuildingFromProject($proj_cd, $tech_type_cd, $bld_owning_dept_cd, $bld_type_cd, $bld_lat, $bld_lng, $bld_class_cd, $bld_location_cd, $bld_maintain_by_npwd, $division, $sub_division)
    {
        $connection = DB::connection();
        $connection->beginTransaction();

        try {
            $userMapping = session('userMapping');
            $array = json_decode(json_encode($userMapping), true);
            $user_zone_cd = $array["zone_cd"];
            $user_circle_cd = $array["circle_cd"];
            $user_division_cd = $array["division_cd"];
            $user_sub_division_cd = $array["sub_division_cd"];
            $user_office_type_cd = $array["office_type_cd"];
            $user_office_cd = $array["office_cd"];
            $divShrtCd = 'NL';
            $division_name = null;
            $zone_cd = null;
            $dist_cd = null;
            // get the district code
            $district = DB::table('asset_master_divisions')
                ->select('district_cd')
                ->where('division_cd', session('userMapping')->division_cd)
                ->get()->first();
            if ($district)
                $dist_cd = $district->district_cd;
            $assetPlanId = $connection->table('projects.prt_project_asset_plan')->insertGetId([
                'project_cd' => $proj_cd,
                'project_type_cd' => "NEW",
                'work_item_id' => null,
                'asset_type_cd' => '10',
                'temp_asset_cd' => null,
                'temp_asset_name' => null,
                'new_asset_length' => null,
                'no_of_new_asset' => 1,
                'parent_asset_cd' => null,
                'status' => '1',
                'group_type_cd' => 'NEW',
                'remarks' => 'New Building, New Project Type',
                'created_at' => now(),
                'created_by' => Auth::id()
            ]);

            $building = new AssetBuildingDetail();
            // The asset plan and building must use the same PDO connection so the
            // building foreign key can see the uncommitted asset-plan row.
            $building->setConnection($connection->getName());
            $building_code = rand(10, 99) . $user_office_cd . now()->year . rand(100, 999);
            $building->building_system_cd = $building_code;
            $building->qtr_no = null;
            $building->bld_qtr_name = null;
            $building->is_maintained_by_npwd = $bld_maintain_by_npwd;
            $building->building_class_cd = $bld_class_cd;
            $building->building_location_cd = $bld_location_cd;
            $building->building_type_cd = $bld_type_cd;
            $building->asset_owning_dept_cd = $bld_owning_dept_cd;
            $building->lat = $bld_lat;
            $building->lon = $bld_lng;
            $building->created_at = Carbon::now();
            $building->updated_at = Carbon::now();
            $building->created_by = Auth::id();
            $building->created_at_office_cd = $user_office_cd;
            $building->division_cd = $division;
            $building->sub_division_cd = $sub_division;
            $building->dist_cd = $dist_cd;
            $building->asset_plan_id = $assetPlanId;
            $building->save();

            $connection->commit();

            return $building_code;
        } catch (\Throwable $e) {
            if ($connection->transactionLevel() > 0) {
                $connection->rollBack();
            }

            Log::error('Building creation from completed project failed.', [
                'project_cd' => $proj_cd,
                'asset_plan_id' => $assetPlanId ?? null,
                'building_system_cd' => $building_code ?? null,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            throw $e;
        }
    }
    function moveRoadsFromMainTableToHist($upgraded_roads, $new_road_id, $upgraded_sub_asset_dtls)
    {
        DB::beginTransaction();

        try {
            $roadIds = collect($upgraded_roads)
                ->filter()
                ->unique()
                ->values()
                ->all();
            if (empty($roadIds)) {
                DB::commit();
                return 0;
            }
            log::info("roadIds: ", $roadIds);
            $histCreatedBy = Auth::id();
            $histCreatedAt = now();
            $remarks = 'Road moved to history after upgradation project completion.';

            $existingRoadIds = DB::table('public.asset_road_details')
                ->whereIn('rd_system_id', $roadIds)
                ->lockForUpdate()
                ->pluck('road_length', 'rd_system_id')
                ->all();

            if (count($existingRoadIds) !== count($roadIds)) {
                throw new Exception('One or more upgraded roads were not found in asset_road_details.');
            }

            $fullRoadIds = [];
            $partialRoadSegments = [];

            foreach ($upgraded_sub_asset_dtls as $asset) {
                $roadId = $asset['parent_asset_id'] ?? null;

                if (!$roadId || !array_key_exists($roadId, $existingRoadIds)) {
                    continue;
                }

                $startChainage = (float) ($asset['start_chainage'] ?? 0);
                $endChainage = (float) ($asset['end_chainage'] ?? 0);
                $roadLength = (float) $existingRoadIds[$roadId];

                if ($this->isFullRoadChainage($startChainage, $endChainage, $roadLength)) {
                    $fullRoadIds[] = $roadId;
                    continue;
                }

                $partialRoadSegments[$roadId][] = [
                    'start_chainage' => $startChainage,
                    'end_chainage' => $endChainage,
                ];
            }

            $fullRoadIds = collect($fullRoadIds)->unique()->values()->all();

            foreach ($fullRoadIds as $fullRoadId) {
                unset($partialRoadSegments[$fullRoadId]);
            }

            $this->moveRoadSubAssetsToNewRoad($fullRoadIds, $new_road_id, $histCreatedBy, $histCreatedAt);
            $this->moveRoadSubAssetsToNewRoad(array_keys($partialRoadSegments), $new_road_id, $histCreatedBy, $histCreatedAt, $partialRoadSegments);

            if (empty($fullRoadIds)) {
                DB::commit();
                return 0;
            }

            $copiedRoadCount = DB::table('public.asset_road_details_hist')->insertUsing([
                'rd_system_id',
                'rd_category_cd',
                'rd_number',
                'rd_name',
                'rd_type_cd',
                'road_length',
                'rd_owner_cd',
                'road_created_at_office_type',
                'road_created_at_office_cd',
                'road_type',
                'district_name',
                'block_name',
                'lng',
                'lat',
                'division_name',
                'division_cd',
                'block_cd',
                'district_cd',
                'included_in_core_network',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
                'remarks',
                'approved_by',
                'approved_at',
                'is_road_data_merged_to_all_state_file',
                'is_road_data_merged_to_division_file',
                'state_data_merged_on',
                'division_data_merged_on',
                'hist_created_by',
                'hist_created_at',
                'asset_plan_id'
            ], function ($query) use ($fullRoadIds, $histCreatedBy, $histCreatedAt, $remarks) {
                $query->from('public.asset_road_details')
                    ->whereIn('rd_system_id', $fullRoadIds)
                    ->select(
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'road_created_at_office_type',
                        'road_created_at_office_cd',
                        'road_type',
                        'district_name',
                        'block_name',
                        'lng',
                        'lat',
                        'division_name',
                        'division_cd',
                        'block_cd',
                        'district_cd',
                        'included_in_core_network',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        DB::raw("'" . str_replace("'", "''", $remarks) . "' as remarks"),
                        'approved_by',
                        'approved_at',
                        'is_road_data_merged_to_all_state_file',
                        'is_road_data_merged_to_division_file',
                        'state_data_merged_on',
                        'division_data_merged_on',
                        DB::raw("'" . $histCreatedBy . "' as hist_created_by"),
                        DB::raw("'" . $histCreatedAt . "' as hist_created_at"),
                        'asset_plan_id'
                    );
            });

            if ($copiedRoadCount !== count($fullRoadIds)) {
                throw new Exception('Failed to copy all upgraded roads to asset_road_details_hist.');
            }

            $deletedRoadCount = DB::table('public.asset_road_details')
                ->whereIn('rd_system_id', $fullRoadIds)
                ->delete();

            if ($deletedRoadCount !== count($fullRoadIds)) {
                throw new Exception('Failed to delete all upgraded roads from asset_road_details.');
            }

            DB::commit();
            return $deletedRoadCount;
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::error('Failed to move upgraded roads to history.', [
                'upgraded_roads' => $upgraded_roads,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function isFullRoadChainage(float $startChainage, float $endChainage, float $roadLength): bool
    {
        return abs($startChainage) < 0.001 && abs($endChainage - $roadLength) < 0.001;
    }

    function moveRoadsFromMainTableToHistCopy($upgraded_roads, $new_road_id, $upgraded_sub_asset_dtls)
    {
        DB::beginTransaction();

        try {
            $roadIds = collect($upgraded_roads)
                ->filter()
                ->unique()
                ->values()
                ->all();
            foreach ($upgraded_roads as $road) {
                $roadLength = collect($upgraded_sub_asset_dtls)
                    ->where('parent_asset_id', $road)
                    ->sum(function ($item) {
                        return (float) $item['end_chainage'] - (float) $item['start_chainage'];
                    });
            }
            if (empty($roadIds)) {
                DB::commit();
                return 0;
            }
            log::info("roadIds: ", $roadIds);
            $histCreatedBy = Auth::id();
            $histCreatedAt = now();
            $remarks = 'Road moved to history after upgradation project completion.';

            $existingRoadIds = DB::table('public.asset_road_details')
                ->whereIn('rd_system_id', $roadIds)
                ->lockForUpdate()
                ->pluck('rd_system_id', 'road_length')
                ->all();

            if (count($existingRoadIds) !== count($roadIds)) {
                throw new Exception('One or more upgraded roads were not found in asset_road_details.');
            }

            $this->moveRoadSubAssetsToNewRoad($roadIds, $new_road_id, $histCreatedBy, $histCreatedAt);

            $copiedRoadCount = DB::table('public.asset_road_details_hist')->insertUsing([
                'rd_system_id',
                'rd_category_cd',
                'rd_number',
                'rd_name',
                'rd_type_cd',
                'road_length',
                'rd_owner_cd',
                'road_created_at_office_type',
                'road_created_at_office_cd',
                'road_type',
                'district_name',
                'block_name',
                'lng',
                'lat',
                'division_name',
                'division_cd',
                'block_cd',
                'district_cd',
                'included_in_core_network',
                'created_at',
                'updated_at',
                'created_by',
                'updated_by',
                'remarks',
                'approved_by',
                'approved_at',
                'is_road_data_merged_to_all_state_file',
                'is_road_data_merged_to_division_file',
                'state_data_merged_on',
                'division_data_merged_on',
                'hist_created_by',
                'hist_created_at',
                'asset_plan_id'
            ], function ($query) use ($roadIds, $histCreatedBy, $histCreatedAt, $remarks) {
                $query->from('public.asset_road_details')
                    ->whereIn('rd_system_id', $roadIds)
                    ->select(
                        'rd_system_id',
                        'rd_category_cd',
                        'rd_number',
                        'rd_name',
                        'rd_type_cd',
                        'road_length',
                        'rd_owner_cd',
                        'road_created_at_office_type',
                        'road_created_at_office_cd',
                        'road_type',
                        'district_name',
                        'block_name',
                        'lng',
                        'lat',
                        'division_name',
                        'division_cd',
                        'block_cd',
                        'district_cd',
                        'included_in_core_network',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        DB::raw("'" . str_replace("'", "''", $remarks) . "' as remarks"),
                        'approved_by',
                        'approved_at',
                        'is_road_data_merged_to_all_state_file',
                        'is_road_data_merged_to_division_file',
                        'state_data_merged_on',
                        'division_data_merged_on',
                        DB::raw("'" . $histCreatedBy . "' as hist_created_by"),
                        DB::raw("'" . $histCreatedAt . "' as hist_created_at"),
                        'asset_plan_id'
                    );
            });

            if ($copiedRoadCount !== count($roadIds)) {
                throw new Exception('Failed to copy all upgraded roads to asset_road_details_hist.');
            }

            $deletedRoadCount = DB::table('public.asset_road_details')
                ->whereIn('rd_system_id', $roadIds)
                ->delete();

            if ($deletedRoadCount !== count($roadIds)) {
                throw new Exception('Failed to delete all upgraded roads from asset_road_details.');
            }

            DB::commit();
            return $deletedRoadCount;
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::error('Failed to move upgraded roads to history.', [
                'upgraded_roads' => $upgraded_roads,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function moveRoadSubAssetsToNewRoad(array $roadIds, string $newRoadId, $histCreatedBy, $histCreatedAt, array $roadSegments = []): void
    {
        if (empty($roadIds)) {
            return;
        }

        $subAssetTables = [
            [
                'label' => 'Culvert/CD Work',
                'table' => 'public.asset_road_cdwork_details',
                'history_table' => 'public.asset_road_cdwork_details_hist',
                'road_column' => 'rd_system_id',
                'chainage_column' => 'chainage',
            ],
            [
                'label' => 'Bridge',
                'table' => 'public.asset_road_bridge_details',
                'history_table' => 'public.asset_road_bridge_details_hist',
                'road_column' => 'rd_system_id',
                'chainage_column' => 'chainage',
            ],
            [
                'label' => 'Pavement',
                'table' => 'public.asset_road_pavement_details',
                'history_table' => 'public.asset_road_pavement_details_hist',
                'road_column' => 'rd_system_id',
                'start_chainage_column' => 'start_chainage',
                'end_chainage_column' => 'end_chainage',
            ],
            [
                'label' => 'Protection Wall',
                'table' => 'public.asset_protection_wall_details',
                'history_table' => 'public.asset_protection_wall_details_hist',
                'road_column' => 'rd_system_id',
                'chainage_column' => 'chainage',
            ],
        ];

        $remarks = 'Sub-asset parent road changed after upgradation project completion.';

        foreach ($subAssetTables as $subAssetTable) {
            $queryConstraint = function ($query) use ($subAssetTable, $roadIds, $roadSegments) {
                $query->whereIn($subAssetTable['road_column'], $roadIds);

                if (!empty($roadSegments)) {
                    $this->applySubAssetChainageFilter($query, $subAssetTable, $roadSegments);
                }
            };

            $referencedRoadIds = DB::table($subAssetTable['table'])
                ->where($queryConstraint)
                ->lockForUpdate()
                ->pluck($subAssetTable['road_column'])
                ->unique()
                ->values()
                ->all();

            if (empty($referencedRoadIds)) {
                continue;
            }

            $copiedRows = $this->copyRowsToHistoryTable(
                $subAssetTable['table'],
                $subAssetTable['history_table'],
                $subAssetTable['road_column'],
                $referencedRoadIds,
                $histCreatedBy,
                $histCreatedAt,
                $remarks,
                $queryConstraint
            );

            if ($copiedRows < 1) {
                throw new Exception("Failed to copy {$subAssetTable['label']} records to history.");
            }

            DB::table($subAssetTable['table'])
                ->where($queryConstraint)
                ->update([
                    $subAssetTable['road_column'] => $newRoadId,
                    'updated_at' => now(),
                ]);
        }
    }

    private function applySubAssetChainageFilter($query, array $subAssetTable, array $roadSegments): void
    {
        $query->where(function ($segmentQuery) use ($subAssetTable, $roadSegments) {
            foreach ($roadSegments as $roadId => $segments) {
                foreach ($segments as $segment) {
                    $segmentQuery->orWhere(function ($roadSegmentQuery) use ($subAssetTable, $roadId, $segment) {
                        $roadSegmentQuery->where($subAssetTable['road_column'], $roadId);

                        if (isset($subAssetTable['chainage_column'])) {
                            $roadSegmentQuery->whereBetween($subAssetTable['chainage_column'], [
                                $segment['start_chainage'],
                                $segment['end_chainage'],
                            ]);

                            return;
                        }

                        $roadSegmentQuery
                            ->where($subAssetTable['start_chainage_column'], '>=', $segment['start_chainage'])
                            ->where($subAssetTable['end_chainage_column'], '<=', $segment['end_chainage']);
                    });
                }
            }
        });
    }

    private function copyRowsToHistoryTable(
        string $sourceTable,
        string $historyTable,
        string $matchColumn,
        array $matchValues,
        $histCreatedBy,
        $histCreatedAt,
        string $remarks,
        ?callable $queryConstraint = null
    ): int {
        $sourceColumns = $this->getTableColumns($sourceTable);
        $historyColumns = $this->getTableColumns($historyTable);

        if (empty($sourceColumns)) {
            throw new Exception("Source table {$sourceTable} was not found.");
        }

        if (empty($historyColumns)) {
            throw new Exception("History table {$historyTable} was not found.");
        }

        $historyMetaColumns = [
            'hist_created_by',
            'hist_created_at',
            'hist_created_on',
            'hist_remarks',
            'remarks_hist',
        ];

        $copyColumns = array_values(array_filter(
            array_intersect($historyColumns, $sourceColumns),
            function ($column) use ($historyMetaColumns) {
                return $column !== 'id' && !in_array($column, $historyMetaColumns, true);
            }
        ));

        if (empty($copyColumns)) {
            throw new Exception("No matching columns found between {$sourceTable} and {$historyTable}.");
        }

        $targetColumns = $copyColumns;
        $selectColumns = $copyColumns;
        $escapedCreatedBy = $this->escapeSqlLiteral((string) $histCreatedBy);
        $escapedCreatedAt = $this->escapeSqlLiteral((string) $histCreatedAt);
        $escapedRemarks = $this->escapeSqlLiteral($remarks);

        if (in_array('hist_created_by', $historyColumns, true)) {
            $targetColumns[] = 'hist_created_by';
            $selectColumns[] = DB::raw("'{$escapedCreatedBy}' as hist_created_by");
        }

        if (in_array('hist_created_at', $historyColumns, true)) {
            $targetColumns[] = 'hist_created_at';
            $selectColumns[] = DB::raw("'{$escapedCreatedAt}' as hist_created_at");
        } elseif (in_array('hist_created_on', $historyColumns, true)) {
            $targetColumns[] = 'hist_created_on';
            $selectColumns[] = DB::raw("'{$escapedCreatedAt}' as hist_created_on");
        }

        if (in_array('hist_remarks', $historyColumns, true)) {
            $targetColumns[] = 'hist_remarks';
            $selectColumns[] = DB::raw("'{$escapedRemarks}' as hist_remarks");
        } elseif (in_array('remarks_hist', $historyColumns, true)) {
            $targetColumns[] = 'remarks_hist';
            $selectColumns[] = DB::raw("'{$escapedRemarks}' as remarks_hist");
        }

        return DB::table($historyTable)->insertUsing($targetColumns, function ($query) use ($sourceTable, $matchColumn, $matchValues, $selectColumns, $queryConstraint) {
            $query->from($sourceTable)
                ->whereIn($matchColumn, $matchValues)
                ->select($selectColumns);

            if ($queryConstraint !== null) {
                $query->where($queryConstraint);
            }
        });
    }

    private function getTableColumns(string $qualifiedTable): array
    {
        [$schema, $table] = $this->splitQualifiedTableName($qualifiedTable);

        return DB::table('information_schema.columns')
            ->where('table_schema', $schema)
            ->where('table_name', $table)
            ->orderBy('ordinal_position')
            ->pluck('column_name')
            ->all();
    }

    private function splitQualifiedTableName(string $qualifiedTable): array
    {
        $parts = explode('.', $qualifiedTable, 2);

        if (count($parts) === 1) {
            return ['public', $parts[0]];
        }

        return [$parts[0], $parts[1]];
    }

    private function escapeSqlLiteral(string $value): string
    {
        return str_replace("'", "''", $value);
    }
}
