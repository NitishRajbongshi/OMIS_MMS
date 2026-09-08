<?php

namespace App\Http\Controllers\PMS\Progress;

use App\Http\Controllers\Controller;
use CreateAssetMasterPavementConditionsTable;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
class VerifyProgressController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $userDeptCd = $user->department;

            $userMapping = session('userMapping');
            $array = json_decode(json_encode($userMapping), true);
            $user_zone_cd = $array["zone_cd"];
            $user_circle_cd = $array["circle_cd"];
            $user_division_cd = $array["division_cd"];
            $user_sub_division_cd = $array["sub_division_cd"];
            $user_office_type_cd = $array["office_type_cd"];
            $user_office_cd = $array["office_cd"];

            $query = DB::table('projects.prt_project_progress_details_work_item_wise as pr')
                ->join('projects.prt_project_details as p', 'p.project_cd', '=', 'pr.project_cd')
                ->leftjoin('prm_project_types as pt', 'p.project_type_cd', '=', 'pt.proj_type_cd')
                ->leftjoin('public.asset_master_divisions as div', 'p.division_cd', '=', 'div.division_cd')
                ->leftjoin('public.asset_master_sub_divisions as subdiv', 'p.sub_division_cd', '=', 'subdiv.sub_div_cd')
                ->select(
                    'pr.project_cd',
                    'pr.progress_id',
                    'p.project_name',
                    'pr.progress_date',
                    'p.project_start_date',
                    'p.project_end_date',
                    'pt.proj_type_descr',
                    'div.division_name',
                    'subdiv.sub_div_name',
                    'pr.created_at'
                )
                ->where('pr.status', 'S')
                ->orWhereNull('pr.status')
                ->groupBy(
                    'pr.progress_id',
                    'pr.project_cd',
                    'p.project_name',
                    'p.project_start_date',
                    'p.project_end_date',
                    'pt.proj_type_descr',
                    'pr.created_at',
                    'pr.progress_date',
                    'div.division_name',
                    'subdiv.sub_div_name'
                );

            # Apply office hierarchy filter
            // if ($user_office_type_cd == "SDO") {
            //     $query->where('p.sub_division_cd', $user_sub_division_cd);
            // }

            // if ($user_office_type_cd == "DO") {
            //     $query->where('p.division_cd', $user_division_cd);
            // }

            // if ($user_office_type_cd == "CO") {
            //     $query->where('div.circle_cd', $user_circle_cd);
            // }

            // if ($user_office_type_cd == "ZO") {
            //     $query->where('div.zone_cd', $user_zone_cd);
            // }

            $project_list = $query->get();

            $financialQuery = DB::table('projects.prt_project_details as p')
                ->leftJoin('projects.prt_project_payment_details as pp', 'p.project_cd', '=', 'pp.project_cd')
                ->leftJoin("asset_master_divisions as div", "div.division_cd", "=", "p.division_cd")
                ->leftJoin("asset_master_sub_divisions as sdiv", "sdiv.sub_div_cd", "=", "p.sub_division_cd")
                ->select(
                    'p.*',
                    DB::raw('COALESCE(SUM(pp.payment_amount),0) as total_payment'),
                    DB::raw('(p.work_order_amount - COALESCE(SUM(pp.payment_amount),0)) as balance'),
                    DB::raw('MAX(pp.payment_date) as last_payment_date'),
                    "div.division_name",
                    "sdiv.sub_div_name"
                )
                ->where("p.owner_dept_cd", $userDeptCd);

            if ($user_office_type_cd == "SDO") {
                $financialQuery->where('p.sub_division_cd', $user_sub_division_cd);
            }

            if ($user_office_type_cd == "DO") {
                $financialQuery->where('p.division_cd', $user_division_cd);
            }

            if ($user_office_type_cd == "CO") {
                $financialQuery->where('div.circle_cd', $user_circle_cd);
            }

            if ($user_office_type_cd == "ZO") {
                $financialQuery->where('div.zone_cd', $user_zone_cd);
            }

            $financial_project_list = $financialQuery
                ->groupBy(
                    'p.project_cd',
                    'p.work_order_amount',
                    'p.division_cd',
                    'p.sub_division_cd',
                    'div.division_name',
                    'sdiv.sub_div_name'
                )
                ->havingRaw('COALESCE(SUM(pp.payment_amount),0) < p.work_order_amount')
                ->get();

            return view("pms.progress.listProjectProgressVerify", compact('project_list', 'financial_project_list'));
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
    }

    public function getProgressSubmitted($project_cd, $progress_cd)
    {
        try {

            $approvedSub = DB::table('prt_project_progress_details_work_item_wise')
                ->select(
                    'item_id',
                    DB::raw('SUM(quantity_done) as approved_qty'),
                    DB::raw('SUM(boq_quantity_done) as approved_boq_qty')
                )
                ->where('project_cd', $project_cd)
                ->where('status', 'A')
                ->groupBy('item_id');

            $data = DB::table('prt_project_work_items_details as w')
                ->leftJoin('prm_item_of_work as i', 'i.item_cd', '=', 'w.item_cd')
                ->leftJoin('prt_project_iow_boq_mapping as b', 'b.iow_id', '=', 'w.id')
                ->leftJoin('prm_boq_items as q', 'q.boq_item_id', '=', 'b.boq_item_id')
                ->leftJoin('prt_project_progress_details_work_item_wise as pr', 'pr.item_id', '=', 'w.id')
                ->leftJoin('prm_item_units as u1', 'u1.unit_cd', '=', 'i.unit_cd')
                ->leftJoin('prm_item_units as u2', 'u2.unit_cd', '=', 'q.unit_cd')
                ->leftJoinSub($approvedSub, 'ap', function ($join) {
                    $join->on('ap.item_id', '=', 'w.id');
                })
                ->select(
                    'i.item_name',
                    'q.boq_item_name',
                    'w.quantity',
                    'u1.unit_cd as iow_unit',
                    'u2.unit_cd as boq_unit',
                    'pr.quantity_done',
                    DB::raw('COALESCE(ap.approved_qty, 0) as total_approved_qty')
                )
                ->where('w.project_cd', $project_cd)
                ->where('pr.progress_id', $progress_cd)
                ->get();

            $imageData = [];
            $images = [];
            if ($data)
                $imageData = DB::table('prt_progress_images as img')
                    ->select('img.file_path')
                    ->where('img.project_cd', $project_cd)
                    ->where('img.progress_id', $progress_cd)
                    ->get();
            if ($imageData) {
                $images = $imageData->map(function ($item) {
                    $path = str_replace('\\', '/', $item->file_path);
                    $parts = explode('/', $path);
                    $lastParts = array_slice($parts, -2);
                    $relativePath = implode('/', $lastParts);
                    return urlencode(Crypt::encryptString($relativePath));
                });
            }
            Log::info($images);
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
        // return response()->json($data);
        return response()->json([
            'data' => $data,
            'images' => $images ?? []
        ]);

    }

    public function viewExternalFile($token)
    {
        try {
            // Decode URL (important)
            $path = Crypt::decryptString(urldecode($token));
            // External folder base path
            $basePath = config('customconfigpath.PMS_PROGRESS_IMAGES_PATH');
            $rootPath = config('filesystems.disks.external.root');

            $rootPath = rtrim($rootPath, '\\/');
            $basePath = trim($basePath, '\\/');
            $path = ltrim($path, '\\/');

            $fullPath = $rootPath . DIRECTORY_SEPARATOR . $basePath . DIRECTORY_SEPARATOR . $path;

            if (!file_exists($fullPath)) {
                abort(404, 'File not found');
            }
            // Detect MIME type
            $mimeType = mime_content_type($fullPath);
            return response()->file($fullPath, [
                'Content-Type' => $mimeType
            ]);

        } catch (\Exception $e) {
            abort(403, 'Invalid file access');
        }
    }

    public function getPreviousSubmittedProgress($project_cd)
    {
        try {
            DB::enableQueryLog();
            Log::info("getPreviousSubmittedProgress");
            $data = DB::table('prt_project_progress_details_work_item_wise as pr')
                ->join('prt_project_work_items_details as w', 'w.id', '=', 'pr.item_id')
                ->leftJoin('prm_item_of_work as i', 'i.item_cd', '=', 'w.item_cd')
                // ->leftJoin('prt_project_iow_boq_mapping as b', 'b.iow_id', '=', 'w.id')
                // ->leftJoin('prm_boq_items as q', 'q.boq_item_id', '=', 'b.boq_item_id')
                ->leftJoin('prm_item_units as u1', 'u1.unit_cd', '=', 'i.unit_cd')
                // ->leftJoin('prm_item_units as u2', 'u2.unit_cd', '=', 'q.unit_cd')
                ->select(
                    'pr.project_cd',
                    'pr.progress_id',
                    'pr.progress_date',
                    'pr.status',
                    'i.item_name',
                    // 'q.boq_item_name',
                    'pr.quantity_done',
                    // 'pr.boq_quantity_done',
                    'u1.unit_cd as iow_unit',
                    // 'u2.unit_cd as boq_unit'
                )
                ->where('pr.project_cd', $project_cd)
                ->whereIn('pr.status', ['A', 'R'])
                ->orderBy('pr.progress_date', 'desc')
                ->orderBy('pr.progress_id', 'desc')
                ->get();

            $grouped = $data->groupBy(function ($item) {
                return $item->progress_date . '_' . $item->progress_id;
            });
            Log::info(DB::getQueryLog());
            return response()->json([
                'status' => 'success',
                'data' => $grouped
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function approveProgress(Request $request)
    {
        try {
            $isCompleted = false;
            $response = DB::transaction(
                function () use ($request, $isCompleted) {
                    $proj_cd = $request->txt_project_cd;
                    $prog_cd = $request->txt_progress_cd;
                    $action_status = $request->action_type;
                    $remarks = $request->reject_reason;
                    Log::info("proj_cd: " . $proj_cd);
                    Log::info("prog_cd: " . $prog_cd);
                    Log::info("action_status: " . $action_status);
                    Log::info("remarks: " . $remarks);

                    $updated = DB::table('prt_project_progress_details_work_item_wise')
                        ->where('project_cd', $proj_cd)
                        ->where('progress_id', $prog_cd)
                        ->update([
                            'status' => $action_status,
                            'remarks' => $remarks,
                            'verified_by' => Auth::user()->id,
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        ]);

                    if ($updated === 0) {
                        throw new \Exception('Progress update failed');
                    }

                    ///Check if Project Overall Progress is 100% -- Start
    
                    if ($action_status === 'A')
                        Log::info("Progress Approved. Checking if project is completed...");
                    $isCompleted = !DB::table('prt_project_work_items_details as w')
                        ->leftJoin(
                            'prt_project_progress_details_work_item_wise as pr',
                            function ($join) {
                                $join->on('pr.item_id', '=', 'w.id')
                                    ->where('pr.status', '=', 'A');
                            }
                        )
                        ->where('w.project_cd', $proj_cd)
                        ->groupBy('w.id', 'w.quantity')
                        ->havingRaw('COALESCE(SUM(pr.quantity_done),0) < w.quantity')
                        ->select(DB::raw('1'))
                        ->exists();
                    // if ($isCompleted) {
                    //      $this->createAssetPoolAfterProjectCompletion($proj_cd);
                    // }
    
                    //Not required to Create Asset Pool Just After verifications
                    //Another Module will be desigened
                    ///Check if Project Overall Progress is 100% -- End
    
                    return [
                        'status' => 200,
                        'action_status' => $action_status
                    ];
                }
            );
            if ($response['action_status'] == "A") {
                if ($isCompleted) {
                    return redirect()->route('progress.verify.index')
                        ->with('success', 'Progress Data Approved Successfully!!!Project completion confirmed: Progress has reached 100%. All associated assets have been successfully recorded and stored in the Asset Plan repository.');
                }
                return redirect()->route('progress.verify.index')
                    ->with('success', 'Progress Data Approved Successfully!');
            }
            return redirect()->route('progress.verify.index')
                ->with('warning', 'Progress Data Rejected Successfully!');
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
    }
}
