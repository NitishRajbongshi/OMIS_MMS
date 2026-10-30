<?php

namespace App\Http\Controllers\Home\PMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PmsHomeController extends Controller
{

    public function projectList(string $type)
    {
        try {
            if (!in_array($type, ['total', 'delayed', 'completed', 'ongoing'], true)) {
                abort(404);
            }

            // Calculate physical progress for each project.
            $itemProgress = DB::table('projects.prt_project_work_items_details as wi')
                ->leftJoin('projects.prt_project_progress_details_work_item_wise as pp', function ($join) {
                    $join->on('pp.item_id', '=', 'wi.id')
                        ->where('pp.status', 'A');
                })
                ->select(
                    'wi.project_cd',
                    DB::raw('CASE
                    WHEN wi.quantity > 0
                    THEN LEAST(
                        (COALESCE(SUM(pp.quantity_done), 0) / wi.quantity) * 100,
                        100
                    )
                    ELSE 0
                END as item_progress')
                )
                ->groupBy('wi.project_cd', 'wi.id', 'wi.quantity');

            $projectProgress = DB::query()
                ->fromSub($itemProgress, 'item_progress')
                ->select(
                    'project_cd',
                    DB::raw('ROUND(AVG(item_progress), 2) as physical_progress')
                )
                ->groupBy('project_cd');

            // Main project query.
            $projectsQuery = DB::table('projects.prt_project_details as p')
                ->leftJoinSub(
                    $projectProgress,
                    'progress',
                    'progress.project_cd',
                    '=',
                    'p.project_cd'
                )
                ->leftJoin(
                    'projects.prt_contractor_details as contractor',
                    'contractor.regn_no',
                    '=',
                    'p.project_awarded_to'
                )
                ->leftJoin(
                    'public.department_details as d',
                    'd.id',
                    '=',
                    'p.owner_dept_cd'
                )
                ->select(
                    'p.project_cd',
                    'p.project_end_date',
                    'p.project_awarded_to',
                    'd.department_name',
                    'contractor.contractors_name as contractor_name',
                    DB::raw('COALESCE(progress.physical_progress, 0) as physical_progress')
                )
                ->where('p.is_published', 'Y');

            // Apply the same department restriction as the dashboard.
            $user = Auth::user();

            if ($user && $user->department) {
                $projectsQuery->where('p.owner_dept_cd', $user->department);
            }

            // Apply the same office mapping.
            $mapping = (array) session('userMapping', []);

            $officeType = $mapping['office_type_cd'] ?? null;

            if ($officeType === 'SDO' && !empty($mapping['sub_division_cd'])) {
                $projectsQuery->where(
                    'p.sub_division_cd',
                    $mapping['sub_division_cd']
                );
            } elseif ($officeType === 'DO' && !empty($mapping['division_cd'])) {
                $projectsQuery->where(
                    'p.division_cd',
                    $mapping['division_cd']
                );
            }

            // Apply the selected listing type.
            if ($type === 'completed') {

                // Physical progress is 100% or above.
                $projectsQuery->whereRaw(
                    'COALESCE(progress.physical_progress, 0) >= 100'
                );
            } elseif ($type === 'delayed') {

                // Scheduled end date has passed and project is incomplete.
                $projectsQuery
                    ->whereNotNull('p.project_end_date')
                    ->whereDate('p.project_end_date', '<', now()->toDateString())
                    ->whereRaw(
                        'COALESCE(progress.physical_progress, 0) < 100'
                    );
            } elseif ($type === 'ongoing') {

                // Project has started, is incomplete, and is not delayed.
                $projectsQuery
                    ->whereRaw('COALESCE(progress.physical_progress, 0) > 0')
                    ->whereRaw('COALESCE(progress.physical_progress, 0) < 100')
                    ->where(function ($query) {
                        $query->whereNull('p.project_end_date')
                            ->orWhereDate(
                                'p.project_end_date',
                                '>=',
                                now()->toDateString()
                            );
                    });
            }

            $projects = $projectsQuery
                ->orderBy('p.project_end_date', 'desc')
                ->paginate(20);

            $pageTitle = match ($type) {
                'total' => 'All Projects',
                'completed' => 'Completed Projects',
                'ongoing' => 'Ongoing Projects',
                'delayed' => 'Delayed Projects',
            };

            return view('pms.dashboard.project-list', compact(
                'projects',
                'pageTitle',
                'type'
            ));
        } catch (\Exception $e) {
            Log::error('Project listing error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return view('error');
        }
    }

    public function monthlyProjectCompletion(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m-d'],
            'department_id' => ['nullable', 'integer'],
            'contractor_id' => ['nullable', 'string', 'max:100'],
        ]);

        $monthStart = Carbon::createFromFormat('Y-m-d', $validated['month'])
            ->startOfMonth();

        $monthEnd = $monthStart->copy()->addMonth();

        $selectedDepartment = $validated['department_id'] ?? null;
        $selectedContractor = $validated['contractor_id'] ?? null;

        // Find one completion date per project.
        $completionQuery = DB::table(
            'projects.prt_project_report_movements as movement'
        )
            ->join(
                'projects.prt_project_details as p',
                'p.project_cd',
                '=',
                'movement.project_cd'
            )
            ->whereNotNull('movement.prj_completion_date')
            ->select(
                'p.project_cd',
                'p.owner_dept_cd',
                'p.project_awarded_to'
            )
            ->selectRaw(
                'MIN(movement.prj_completion_date) AS completion_date'
            )
            ->groupBy(
                'p.project_cd',
                'p.owner_dept_cd',
                'p.project_awarded_to'
            );

        if ($selectedDepartment) {
            $completionQuery->where(
                'p.owner_dept_cd',
                $selectedDepartment
            );
        }

        if ($selectedContractor) {
            $completionQuery->where(
                'p.project_awarded_to',
                $selectedContractor
            );
        }

        // Filter the grouped completion date to the selected month.
        $completionQuery->havingRaw(
            'MIN(movement.prj_completion_date) >= ?',
            [$monthStart]
        )->havingRaw(
            'MIN(movement.prj_completion_date) < ?',
            [$monthEnd]
        );

        $completedProjects = DB::query()
            ->fromSub($completionQuery, 'completed')
            ->leftJoin(
                'public.department_details as d',
                'd.id',
                '=',
                'completed.owner_dept_cd'
            )
            ->leftJoin(
                'projects.prt_contractor_details as c',
                'c.regn_no',
                '=',
                'completed.project_awarded_to'
            )
            ->select(
                'completed.*',
                'd.department_name',
                'c.contractors_name'
            )
            ->orderByDesc('completed.completion_date')
            ->paginate(20)
            ->withQueryString();

        return view('pms.completed-projects', compact(
            'completedProjects',
            'monthStart',
            'selectedDepartment',
            'selectedContractor'
        ));
    }
}
