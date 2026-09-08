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
    public function index(Request $request)
    {
        try {

            $user = Auth::user();

            $data = $this->apiGet(config('customconfigpath.GET_PROJECTS'), [
                'uid' => $user->id,
                'div_cd' => $user->assetUserMapping->division_cd ?? 0,
                'sub_div_cd' => $user->assetUserMapping->sub_division_cd ?? 0,
                'dept_cd' => $user->department ?? '14',
            ]);

            Log::info('ProjectListController: project list fetched.', [
                'count' => count($data['proj_list'] ?? []),
            ]);

            $project_list = collect($data['proj_list'] ?? [])
                ->map(fn($item) => (object) $item);

            return view('pms.progress.projectList', compact('project_list'));

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
