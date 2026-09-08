<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\OfficeDetail;
use App\Models\User;
use App\Models\UserMenuDetail;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        try {
            $secreteCode = Auth::user()->secret_code;
            return view('home', compact(
                'secreteCode',
            ));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function portalLanding()
    {
        try {
            $portals = [
                [
                    'title' => 'AMIS Portal',
                    'subtitle' => 'Asset registry and operational command center',
                    'route' => route('home'),
                    'icon' => 'fas fa-network-wired',
                    'accent' => 'teal',
                ],
                [
                    'title' => 'PMIS Portal',
                    'subtitle' => 'Project creation, verification and progress tracking',
                    'route' => route('pms.landing'),
                    'icon' => 'fas fa-diagram-project',
                    'accent' => 'blue',
                ],
                [
                    'title' => 'MMIS Portal',
                    'subtitle' => 'Maintenance assets, estimates and project entry',
                    'route' => route('maintenance.landing'),
                    'icon' => 'fas fa-screwdriver-wrench',
                    'accent' => 'amber',
                ],
                [
                    'title' => 'GIS Portal',
                    'subtitle' => 'Map-based infrastructure intelligence',
                    'route' => route('dashboard'),
                    'icon' => 'fas fa-map-location-dot',
                    'accent' => 'rose',
                ],
            ];

            return view('portal.landing', compact('portals'));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function maintenanceLanding()
    {
        try {
            $maintenanceLinks = [
                [
                    'title' => 'Create Maintenance Project',
                    'subtitle' => 'Start maintenance project entry for roads, buildings, vehicles or equipment',
                    'route' => url()->current(), #route('#'),
                    'icon' => 'fas fa-file-circle-plus',
                ],
                [
                    'title' => 'Project List',
                    'subtitle' => 'Open project monitoring records and progress details',
                    'route' => url()->current(), #route('pms.project.list'),
                    'icon' => 'fas fa-list-check',
                ],
                [
                    'title' => 'Completion Report',
                    'subtitle' => 'Review completion reports and movement status',
                    'route' => url()->current(), #route('project.pms-completion-report'),
                    'icon' => 'fas fa-clipboard-check',
                ],
            ];

            return view('maintenance.landing', compact('maintenanceLinks'));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function pmsLanding()
    {
        try {
            $itemProgress = DB::table('projects.prt_project_work_items_details as wi')
                ->leftJoin('projects.prt_project_progress_details_work_item_wise as pp', function ($join) {
                    $join->on('pp.item_id', '=', 'wi.id')->where('pp.status', 'A');
                })
                ->select(
                    'wi.project_cd',
                    DB::raw('CASE WHEN wi.quantity > 0 THEN LEAST((COALESCE(SUM(pp.quantity_done), 0) / wi.quantity) * 100, 100) ELSE 0 END as item_progress')
                )
                ->groupBy('wi.project_cd', 'wi.id', 'wi.quantity');

            $projectProgress = DB::query()
                ->fromSub($itemProgress, 'item_progress')
                ->select('project_cd', DB::raw('ROUND(AVG(item_progress), 2) as physical_progress'))
                ->groupBy('project_cd');

            $payments = DB::table('projects.prt_project_payment_details')
                ->select('project_cd', DB::raw('COALESCE(SUM(payment_amount), 0) as paid_amount'))
                ->groupBy('project_cd');

            $projectsQuery = DB::table('projects.prt_project_details as p')
                ->leftJoinSub($projectProgress, 'progress', 'progress.project_cd', '=', 'p.project_cd')
                ->leftJoinSub($payments, 'payments', 'payments.project_cd', '=', 'p.project_cd')
                ->leftJoin('projects.prt_contractor_details as contractor', 'contractor.regn_no', '=', 'p.project_awarded_to')
                ->select(
                    'p.project_cd',
                    'p.project_end_date',
                    'p.work_order_amount',
                    'p.project_awarded_to',
                    'contractor.contractors_name as contractor_name',
                    DB::raw('COALESCE(progress.physical_progress, 0) as physical_progress'),
                    DB::raw('COALESCE(payments.paid_amount, 0) as paid_amount')
                )
                ->where('p.is_published', 'Y');

            $user = Auth::user();
            if ($user && $user->department) {
                $projectsQuery->where('p.owner_dept_cd', $user->department);
            }

            $mapping = (array) session('userMapping', []);
            $officeType = $mapping['office_type_cd'] ?? null;
            if ($officeType === 'SDO' && !empty($mapping['sub_division_cd'])) {
                $projectsQuery->where('p.sub_division_cd', $mapping['sub_division_cd']);
            } elseif ($officeType === 'DO' && !empty($mapping['division_cd'])) {
                $projectsQuery->where('p.division_cd', $mapping['division_cd']);
            }

            $projects = $projectsQuery->get();
            $today = now()->startOfDay();
            $statusCounts = ['Completed' => 0, 'Ongoing' => 0, 'Delayed' => 0, 'Not Started' => 0];

            foreach ($projects as $project) {
                $physical = (float) $project->physical_progress;
                if ($physical >= 100) {
                    $statusCounts['Completed']++;
                } elseif ($project->project_end_date && \Carbon\Carbon::parse($project->project_end_date)->startOfDay()->lt($today)) {
                    $statusCounts['Delayed']++;
                } elseif ($physical <= 0) {
                    $statusCounts['Not Started']++;
                } else {
                    $statusCounts['Ongoing']++;
                }
            }

            $totalProjects = $projects->count();
            $totalBudget = (float) $projects->sum('work_order_amount');
            $totalPaid = (float) $projects->sum('paid_amount');
            $dashboard = [
                'total_projects' => $totalProjects,
                'completed_projects' => $statusCounts['Completed'],
                'delayed_projects' => $statusCounts['Delayed'],
                'total_budget' => $totalBudget,
                'total_paid' => $totalPaid,
                'physical_progress' => $totalProjects ? round((float) $projects->avg('physical_progress'), 1) : 0,
                'financial_progress' => $totalBudget > 0 ? round(min(($totalPaid / $totalBudget) * 100, 100), 1) : 0,
                'status_counts' => $statusCounts,
            ];

            $contractorProgress = $projects
                ->filter(function ($project) {
                    return !empty($project->project_awarded_to);
                })
                ->groupBy('project_awarded_to')
                ->map(function ($contractorProjects) {
                    $firstProject = $contractorProjects->first();
                    return [
                        'name' => $firstProject->contractor_name ?: $firstProject->project_awarded_to,
                        'progress' => round((float) $contractorProjects->avg('physical_progress'), 1),
                        'projects' => $contractorProjects->count(),
                    ];
                })
                ->sortByDesc('progress')
                ->values();

            $monthStart = now()->startOfMonth()->subMonths(11);
            $completionQuery = DB::table('projects.prt_project_report_movements as movement')
                ->join('projects.prt_project_details as p', 'p.project_cd', '=', 'movement.project_cd')
                ->whereNotNull('movement.prj_completion_date')
                ->whereDate('movement.prj_completion_date', '>=', $monthStart);

            if ($user && $user->department) {
                $completionQuery->where('p.owner_dept_cd', $user->department);
            }
            if ($officeType === 'SDO' && !empty($mapping['sub_division_cd'])) {
                $completionQuery->where('p.sub_division_cd', $mapping['sub_division_cd']);
            } elseif ($officeType === 'DO' && !empty($mapping['division_cd'])) {
                $completionQuery->where('p.division_cd', $mapping['division_cd']);
            }

            $completedProjects = $completionQuery
                ->select('p.project_cd', DB::raw('MIN(movement.prj_completion_date) as completion_date'))
                ->groupBy('p.project_cd')
                ->get();

            $monthlyCompletions = collect(range(0, 11))->map(function ($offset) use ($monthStart, $completedProjects) {
                $month = $monthStart->copy()->addMonths($offset);
                return [
                    'label' => $month->format('M Y'),
                    'count' => $completedProjects->filter(function ($project) use ($month) {
                        return \Carbon\Carbon::parse($project->completion_date)->format('Y-m') === $month->format('Y-m');
                    })->count(),
                ];
            })->values();

            return view('pms.pmsHome', compact('dashboard', 'monthlyCompletions', 'contractorProgress'));
        } catch (Exception $e) {
            Log::error('PMS dashboard error: '.$e->getMessage(), ['exception' => $e]);
            return view('error');
        }
    }

    public function downloadAPK(Request $request)
    {
        // if (! $request->hasValidSignature()) {
        //     abort(401);
        // }
        $apiEndpoint = config('customconfigpath.APK');
        $client = new Client();
        $response = $client->get($apiEndpoint);
        // Get the response body as a string
        $apkLink = $response->getBody()->getContents();

        return view('apk.index', ['apkLink' => $apkLink]);
    }

    public function createSecretCode(Request $request)
    {
        try {
            return view('user.secretCode');
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function storeSecretCode(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'secretCode' => 'required|string|min:5',
        ]);

        try {
            $secretCode = $request->input('secretCode');
            $hashedSecretCode = Hash::make($secretCode);
            $findUser = User::find($user->id);
            if ($findUser) {
                $query = DB::table('users')
                    ->select('secret_code')
                    ->where('id', $user->id)
                    ->get()->first();
                if ($query->secret_code == '0') {
                    $status = DB::table('users')
                        ->where('id', $user->id)
                        ->update(['secret_code' => $hashedSecretCode]);
                    if ($status) {
                        return redirect()->back()
                            ->with('success', 'Secret code set successfully. Please note your secret code as : ' . $secretCode);
                    } else {
                        return redirect()->back()
                            ->with('failed', 'Failed to set the secret code!');
                    }
                } else {
                    return redirect()->back()
                        ->with('failed', 'You have already set your secret code.');
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function switchOffice(Request $request)
    {
        try {
            $user = Auth::user();
            $office_cd = $request->office;
            $current_office = session('active_office');
            // if try to switch between active office
            if ($office_cd != $current_office) {
                // check for permanent office 
                if ($user->office == $office_cd) {
                    $userOffice = OfficeDetail::select('office_name')->where('id', $user->office)->get()->first();
                    $menus = UserMenuDetail::select('menuid')->where('userid', $user->id)->get();
                    $menu = $menus->pluck('menuid')->toArray();
                    $dataEntry = 0;
                    foreach ($menu as $item) {
                        if ($item === 13)
                            $dataEntry = 1;
                    }
                    session()->put('office', $userOffice->office_name);
                    session()->put('office_cd', $user->office);
                    session()->put('department', $user->department_name);
                    session()->put('officeType', $user->office_type_cd);
                    session()->put('users_office_type_cd', $user->office_type_cd);
                    session()->put('dataEntry', $dataEntry);
                    session()->put('active_office', $user->office);
                    session()->put('office_charge_type', 0);
                } else {
                    foreach (session('additional_office_details') as $index => $office) {
                        // check for activity status
                        if ($office['office'] == $office_cd) {
                            session()->put('office', $office['office_name']);
                            session()->put('office_cd', $office['office']);
                            session()->put('department', $office['department_name']);
                            session()->put('officeType', $office['office_type_cd']);
                            session()->put('users_office_type_cd', $office['office_type_cd']);
                            session()->put('dataEntry', $office['data_entry']);
                            session()->put('active_office', $office['office']);
                        }
                    }
                    session()->put('office_charge_type', 1);
                }
                // redirect to home page
                $secreteCode = Auth::user()->secret_code;
                return redirect()->back()
                    ->with('success', 'Office switch successfully!')
                    ->with('secreteCode', $secreteCode);
            } else {
                // redirect to home page
                $secreteCode = Auth::user()->secret_code;
                return redirect()->back()
                    ->with('failed', 'You are already in the same office!')
                    ->with('secreteCode', $secreteCode);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }
}
