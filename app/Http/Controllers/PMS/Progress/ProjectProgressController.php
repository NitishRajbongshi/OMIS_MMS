<?php

namespace App\Http\Controllers\PMS\Progress;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Exception;


class ProjectProgressController extends Controller
{
    /**
     * Generate a fresh JWT token from the auth server.
     */
    private function generateJwtToken(): string
    {
        $username = config('customconfigpath.API_USERNAME');
        $password = config('customconfigpath.API_PASSWORD');

        $client = new Client(['verify' => false]);

        $response = $client->get(config('customconfigpath.CREATE_TOKEN'), [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("{$username}:{$password}"),
                'Accept' => 'application/json',
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        $token = $body['token'] ?? '';

        if (!$token) {
            throw new Exception('Token not found in auth response.');
        }

        Log::info('JWT token generated successfully.');

        return $token;
    }

    /**
     * Make a GET request, automatically retrying once with a fresh token on 401.
     */
    private function apiGet(string $endpoint, array $query = []): array
    {
        $client = new Client(['verify' => false]);
        $token = $this->generateJwtToken();

        $options = [
            'query' => $query,
            'headers' => [
                'x-access-token' => $token,
                'Accept' => 'application/json',
            ],
        ];

        try {

            $response = $client->get($endpoint, $options);

        } catch (ClientException $e) {

            // Guzzle throws ClientException for 4xx — catch 401 and retry once
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {

                Log::warning('Token expired. Retrying with a fresh token.', ['endpoint' => $endpoint]);

                $options['headers']['x-access-token'] = $this->generateJwtToken();

                $response = $client->get($endpoint, $options);

            } else {
                throw $e;
            }
        }

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    /**
     * Make a POST request with a JSON body.
     */
    private function apiPost(string $endpoint, array $payload = []): array
    {
        $client = new Client(['verify' => false]);
        $token = $this->generateJwtToken();

        $options = [
            'headers' => [
                'x-access-token' => $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ];

        try {

            $response = $client->post($endpoint, $options);

        } catch (ClientException $e) {

            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {

                Log::warning('Token expired on POST. Retrying.', ['endpoint' => $endpoint]);

                $options['headers']['x-access-token'] = $this->generateJwtToken();

                $response = $client->post($endpoint, $options);

            } else {
                throw $e;
            }
        }

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Display the project list.
     */
    // public function index(Request $request)
    // {
    //     try {

    //         $user = Auth::user();

    //         $data = $this->apiGet(config('customconfigpath.GET_PROJECTS'), [
    //             'uid' => $user->id,
    //             'div_cd' => $user->assetUserMapping->division_cd ?? 0,
    //             'sub_div_cd' => $user->assetUserMapping->sub_division_cd ?? 0,
    //             'dept_cd' => $user->department ?? '14',
    //         ]);

    //         Log::info('ProjectListController: project list fetched.', [
    //             'count' => count($data['proj_list'] ?? []),
    //         ]);

    //         $project_list = collect($data['proj_list'] ?? [])
    //             ->map(fn($item) => (object) $item);

    //         return view('pms.progress.projectList', compact('project_list'));

    //     } catch (Exception $e) {

    //         Log::error('ProjectListController@index: ' . $e->getMessage(), [
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //         ]);

    //         return view('error');
    //     }
    // }


    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $divisionCd = $user->assetUserMapping->division_cd ?? 0;
            $subDivisionCd = $user->assetUserMapping->sub_division_cd ?? 0;
            $deptCd = $user->department ?? 14;

            /*
             * Step 1: Approved progress per work item.
             * Equivalent to Python progress_subq.
             */
            $progressSubquery = DB::table('projects.prt_project_progress_details_work_item_wise')
                ->select(
                    'item_id',
                    DB::raw('SUM(quantity_done) AS total_done')
                )
                ->where('status', 'A')
                ->groupBy('item_id');

            /*
             * Step 2: Identify completed projects.
             * A project is completed when:
             * SUM(work item quantity) = SUM(approved progress)
             */
            $completedProjects = DB::table('projects.prt_project_work_items_details as pwit')
                ->leftJoinSub($progressSubquery, 'progress_subq', function ($join) {
                    $join->on('progress_subq.item_id', '=', 'pwit.id');
                })
                ->select('pwit.project_cd')
                ->groupBy('pwit.project_cd')
                ->havingRaw(
                    'SUM(pwit.quantity) = SUM(COALESCE(progress_subq.total_done, 0))'
                )
                ->whereColumn('pwit.project_cd', 'ppd.project_cd');

            /*
             * Step 3: Main project query.
             */
            $query = DB::table('projects.prt_project_details as ppd')

                // Project type and status
                ->leftJoin(
                    'projects.prm_project_types as prj_tp',
                    'ppd.project_type_cd',
                    '=',
                    'prj_tp.proj_type_cd'
                )

                ->leftJoin(
                    'projects.prm_project_status as p_sts',
                    'ppd.project_status_cd',
                    '=',
                    'p_sts.project_status_cd'
                )

                // Department
                ->leftJoin(
                    'public.department_details as dpt',
                    'ppd.owner_dept_cd',
                    '=',
                    'dpt.id'
                )

                // Contractor
                ->leftJoin(
                    'projects.prt_contractor_details as cntr_d',
                    'ppd.project_awarded_to',
                    '=',
                    'cntr_d.regn_no'
                )

                ->leftJoin(
                    'projects.prm_contractor_categories as c_catg',
                    'cntr_d.category_cd',
                    '=',
                    'c_catg.category_cd'
                )

                ->leftJoin(
                    'public.asset_master_lgd_district as cntr_dst_m',
                    'cntr_d.district_cd',
                    '=',
                    'cntr_dst_m.dist_code'
                )

                ->leftJoin(
                    'public.asset_master_lgd_state as cntr_st_m',
                    'cntr_d.state_cd',
                    '=',
                    'cntr_st_m.state_code'
                )

                // Site engineer
                ->leftJoin(
                    'public.users as usr',
                    'ppd.site_eng_id',
                    '=',
                    'usr.id'
                )

                ->leftJoin(
                    'public.department_details as site_eng_dpt',
                    'usr.department',
                    '=',
                    'site_eng_dpt.id'
                )

                ->leftJoin(
                    'public.office_details as site_eng_ofs',
                    'usr.office',
                    '=',
                    'site_eng_ofs.id'
                )

                ->leftJoin(
                    'public.desg_details as site_eng_desg',
                    'usr.designation',
                    '=',
                    'site_eng_desg.id'
                )

                // Site in-charge office
                ->leftJoin(
                    'projects.prm_site_incharge_office_details as siod',
                    'ppd.site_incharge_office_cd',
                    '=',
                    'siod.office_cd'
                )

                ->leftJoin(
                    'public.asset_master_lgd_district as siod_dst_m',
                    'siod.district_cd',
                    '=',
                    'siod_dst_m.dist_code'
                )

                ->leftJoin(
                    'public.asset_master_lgd_state as siod_st_m',
                    'siod.state_cd',
                    '=',
                    'siod_st_m.state_code'
                )

                // Division and sub-division
                ->leftJoin(
                    'public.asset_master_divisions as div_m',
                    'ppd.division_cd',
                    '=',
                    'div_m.division_cd'
                )

                ->leftJoin(
                    'public.asset_master_sub_divisions as sdiv_m',
                    'ppd.sub_division_cd',
                    '=',
                    'sdiv_m.sub_div_cd'
                )

                /*
                 * Main selected fields
                 */
                ->select(
                    'ppd.project_cd',
                    'ppd.project_name',
                    'ppd.project_type_cd',
                    'prj_tp.proj_type_descr',

                    'ppd.owner_dept_cd',
                    'dpt.department_name as owner_dept_name',

                    'ppd.parent_asset_cd',
                    'ppd.project_start_date',
                    'ppd.project_end_date',
                    'ppd.est_proj_cost',
                    'ppd.defect_liability_period',

                    'ppd.project_status_cd',
                    'p_sts.project_status_descr',

                    'ppd.project_awarded_to',

                    // Contractor
                    'cntr_d.contractors_name',
                    'c_catg.category_descr',
                    'cntr_d.category_cd',
                    'cntr_d.address_line1 as cntr_add1',
                    'cntr_d.address_line2 as cntr_add2',
                    'cntr_dst_m.dist_name as cntr_dist_name',
                    'cntr_st_m.state_name as cntr_state_name',
                    'cntr_d.phone_no',
                    'cntr_d.email',

                    // Site engineer
                    'ppd.site_eng_id',
                    'usr.name as site_eng_name',
                    'usr.department',
                    'site_eng_dpt.department_name as site_eng_dept_name',
                    'usr.office as site_eng_ofis_cd',
                    'site_eng_ofs.office_name as site_eng_ofis_name',
                    'usr.designation as site_eng_desg_cd',
                    'site_eng_desg.desg_name as site_eng_desg_name',

                    // Site in-charge
                    'ppd.site_incharge_name',
                    'ppd.site_incharge_office_cd as siod_ofis_cd',
                    'siod.office_name as siod_ofis_name',
                    'siod.contact_person_name',
                    'siod.ph_no',
                    'siod.address_line1 as siod_add1',
                    'siod.address_line2 as siod_add2',
                    'siod_dst_m.dist_name as siod_dist_name',
                    'siod_st_m.state_name as siod_state_name',
                    'ppd.site_incharge_ph_no',

                    'ppd.others',
                    'ppd.latitude',
                    'ppd.longitude',

                    'ppd.division_cd',
                    'div_m.division_name',

                    'ppd.sub_division_cd',
                    'sdiv_m.sub_div_name'
                )

                /*
                 * Same filters as Python getProjects()
                 */
                ->where('ppd.project_status_cd', 1)
                ->where('ppd.division_cd', $divisionCd)
                ->where('ppd.sub_division_cd', $subDivisionCd)
                ->where('ppd.owner_dept_cd', $deptCd)
                ->where('ppd.is_published', 'Y')

                // Exclude 100% completed projects
                // ->whereNotExists($completedProjects)
                ->whereNotExists(function ($query) use ($progressSubquery) {

                    $query->select(DB::raw(1))
                        ->from('projects.prt_project_work_items_details as pwit')
                        ->leftJoinSub($progressSubquery, 'progress_subq', function ($join) {
                            $join->on('progress_subq.item_id', '=', 'pwit.id');
                        })
                        ->whereColumn('pwit.project_cd', 'ppd.project_cd')
                        ->groupBy('pwit.project_cd')
                        ->havingRaw(
                            'SUM(pwit.quantity) = SUM(COALESCE(progress_subq.total_done, 0))'
                        );

                })

                ->orderByDesc('ppd.created_at');

            /*
             * Step 4: Pagination
             */
            $perPage = 100;

            $paginator = $query->paginate($perPage);

            /*
             * Step 5: Construct the same nested structure
             * returned by Python getProjects().
             */
            $project_list = $paginator->getCollection()->map(function ($row) {

                $contractorDetails = null;

                if ($row->project_awarded_to !== null) {
                    $contractorDetails = [
                        'project_awarded_to' => $row->project_awarded_to,
                        'contractors_name' => $row->contractors_name,
                        'category_descr' => $row->category_descr,
                        'category_cd' => $row->category_cd,
                        'cntr_add1' => $row->cntr_add1,
                        'cntr_add2' => $row->cntr_add2,
                        'cntr_dist_name' => $row->cntr_dist_name,
                        'cntr_state_name' => $row->cntr_state_name,
                        'phone_no' => $row->phone_no,
                        'email' => $row->email,
                    ];
                }

                $siteEngineerDetails = null;

                if ($row->site_eng_id !== null) {
                    $siteEngineerDetails = [
                        'site_eng_id' => $row->site_eng_id,
                        'site_eng_name' => $row->site_eng_name,
                        'department_cd' => $row->department,
                        'site_eng_dept_name' => $row->site_eng_dept_name,
                        'site_eng_ofis_cd' => $row->site_eng_ofis_cd,
                        'site_eng_ofis_name' => $row->site_eng_ofis_name,
                        'site_eng_desg_cd' => $row->site_eng_desg_cd,
                        'site_eng_desg_name' => $row->site_eng_desg_name,
                    ];
                }

                $siteInchargeOfficeDetails = null;

                if ($row->siod_ofis_cd !== null) {
                    $siteInchargeOfficeDetails = [
                        'site_incharge_office_cd' => $row->siod_ofis_cd,
                        'siod_office_cd' => $row->siod_ofis_cd,
                        'siod_ofis_name' => $row->siod_ofis_name,
                        'siod_add1' => $row->siod_add1,
                        'siod_add2' => $row->siod_add2,
                        'siod_dist_name' => $row->siod_dist_name,
                        'siod_state_name' => $row->siod_state_name,
                    ];
                }

                return (object) [
                    'project_cd' => $row->project_cd,
                    'project_name' => $row->project_name,
                    'project_type_cd' => $row->project_type_cd,
                    'proj_type_descr' => $row->proj_type_descr,

                    'owner_dept_cd' => $row->owner_dept_cd,
                    'owner_dept_name' => $row->owner_dept_name,

                    'parent_asset_cd' => $row->parent_asset_cd,
                    'project_start_date' => $row->project_start_date,
                    'project_end_date' => $row->project_end_date,
                    'est_proj_cost' => $row->est_proj_cost,
                    'defect_liability_period' => $row->defect_liability_period,

                    'project_status_cd' => $row->project_status_cd,
                    'project_status_descr' => $row->project_status_descr,

                    'project_awarded_to' => $row->project_awarded_to,
                    'project_awarded_contractors_details' => $contractorDetails,

                    'site_eng_id' => $row->site_eng_id,
                    'site_eng_details' => $siteEngineerDetails,

                    'site_incharge_name' => $row->site_incharge_name,
                    'site_incharge_office_cd' => $row->siod_ofis_cd,
                    'site_incharge_office_details' => $siteInchargeOfficeDetails,

                    'site_incharge_ph_no' => $row->site_incharge_ph_no,
                    'others' => $row->others,

                    'lat' => $row->latitude,
                    'lon' => $row->longitude,

                    'division_cd' => $row->division_cd,
                    'division_name' => $row->division_name,

                    'sub_division_cd' => $row->sub_division_cd,
                    'sub_division_name' => $row->sub_div_name,
                ];
            });

            Log::info('ProjectListController: project list fetched directly from DB.', [
                'count' => $project_list->count(),
                'division_cd' => $divisionCd,
                'sub_division_cd' => $subDivisionCd,
                'department_cd' => $deptCd,
            ]);

            return view('pms.progress.projectList', compact(
                'project_list',
                'paginator'
            ));

        } catch (Exception $e) {

            Log::error('ProjectListController@index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return view('error');
        }
    }


    /**
     * Show project progress detail page.
     */
    public function show(string $projectCode)
    {
        try {

            $user = Auth::user();

            // ── DB query for project meta ─────────────────────────────────
            $projectDetails = DB::table('projects.prt_project_details as ppd')
                ->leftJoin('public.asset_master_divisions as pdm', 'ppd.division_cd', '=', 'pdm.division_cd')
                ->leftJoin('public.asset_master_sub_divisions as psd', 'ppd.sub_division_cd', '=', 'psd.sub_div_cd')
                ->leftJoin('public.department_details as md', 'ppd.owner_dept_cd', '=', 'md.id')
                ->select(
                    'ppd.project_name',
                    'pdm.division_name',
                    'psd.sub_div_name',
                    'md.department_name',
                    'ppd.project_start_date',
                    'ppd.project_end_date'
                )
                ->where('ppd.project_cd', $projectCode)
                ->first();

            // ── External API for work items ───────────────────────────────
            $data = $this->apiGet(config('customconfigpath.GET_PROJECT_DETAILS'), [
                'uid' => $user->id,
                'proj_cd' => $projectCode,
            ]);

            Log::info('Project Progress: details fetched.', ['project_code' => $projectCode]);

            $project = (object) [
                'project_cd' => $projectCode,
                'project_name' => $projectDetails->project_name ?? '',
                'division_name' => $projectDetails->division_name ?? '',
                'sub_div_name' => $projectDetails->sub_div_name ?? '',
                'department_name' => $projectDetails->department_name ?? '',
                'start_date' => $projectDetails->project_start_date ?? '',
                'end_date' => $projectDetails->project_end_date ?? '',
                'work_item_details' => $data['work_item_details'] ?? [],
            ];

            return view('pms.progress.projectProgress', compact('project'));

        } catch (Exception $e) {

            Log::error('ProjectListController@show: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Unable to fetch project details.');
        }
    }

    /**
     * Submit progress payload to external API.
     */
    public function submit(Request $request)
    {
        try {

            $payload = $request->all();

            $data = $this->apiPost(
                config('customconfigpath.SUBMIT_PROJECT_PROGRESS'),
                $payload
            );

            return response()->json($data);

        } catch (Exception $e) {

            Log::error('ProjectListController@submit: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Fetch progress history for a specific work item.
     * GET /pms/progress/history?proj_cd=XXX&item_cd=YYY
     */
    public function history(Request $request)
    {
        try {

            $projCd = $request->query('proj_cd');
            $itemCd = $request->query('item_cd');

            if (!$projCd || !$itemCd) {
                return response()->json([
                    'status' => false,
                    'message' => 'proj_cd and item_cd are required.',
                ], 422);
            }

            $data = $this->apiGet(config('customconfigpath.GET_PROGRESS_HISTORY'), [
                'uid' => Auth::id(),
                'proj_cd' => $projCd,
                'item_id' => $itemCd,
            ]);

            return response()->json($data);

        } catch (Exception $e) {

            Log::error('ProjectListController@history: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch history.',
            ], 500);
        }
    }
}
