<?php

namespace App\Http\Controllers\PMS\Progress;

use App\Http\Controllers\Controller;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class ProjectFinancialProgressController extends Controller
{
    //
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
            // dd($array);
            $query = DB::table('projects.prt_project_details as p')
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

            # Apply office hierarchy filter
            if ($user_office_type_cd == "SDO") {
                $query->where('p.sub_division_cd', $user_sub_division_cd);
            }

            if ($user_office_type_cd == "DO") {
                $query->where('p.division_cd', $user_division_cd);
            }

            if ($user_office_type_cd == "CO") {
                $query->where('div.circle_cd', $user_circle_cd);
            }

            if ($user_office_type_cd == "ZO") {
                $query->where('div.zone_cd', $user_zone_cd);
            }

            $project_list = $query
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

            return view("pms.progress.listProjects", compact('project_list'));
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

    public function create($project_cd)
    {
        try {
            $project = DB::table('projects.prt_project_details as p')
                ->select(
                    "p.*",
                    "div.division_name",
                    "sdiv.sub_div_name",
                    DB::raw("(SELECT payment_date 
                  FROM prt_project_payment_details 
                  WHERE project_cd = p.project_cd 
                  ORDER BY payment_date DESC, id DESC
                  LIMIT 1) as last_payment_date"),

                    DB::raw("(SELECT payment_amount 
                  FROM prt_project_payment_details 
                  WHERE project_cd = p.project_cd 
                  ORDER BY payment_date DESC, id DESC
                  LIMIT 1) as last_payment_amount"),

                    DB::raw("(p.work_order_amount - COALESCE(
                    (SELECT SUM(payment_amount) 
                     FROM prt_project_payment_details 
                     WHERE project_cd = p.project_cd),0)
                 ) as balance")
                )
                ->leftJoin("asset_master_divisions as div", "div.division_cd", "=", "p.division_cd")
                ->leftJoin("asset_master_sub_divisions as sdiv", "sdiv.sub_div_cd", "=", "p.sub_division_cd")
                ->where('project_cd', $project_cd)
                ->first();
            $totalBilledAmount = DB::table('prt_project_payment_details')
                ->where('project_cd', $project_cd)
                ->where('bill_type', 'R')
                ->sum('bill_amount');
            $project->remaining_bill_amount = max(
                (float) $project->work_order_amount - (float) $totalBilledAmount,
                0
            );
            $releasedAmounts = DB::table('prt_project_payment_details')
                ->select('project_cd', 'against_bill_no', DB::raw('SUM(payment_amount) as released_amount'))
                ->where('bill_type', 'W')
                ->groupBy('project_cd', 'against_bill_no');

            $runningBills = DB::table('prt_project_payment_details as rb')
                ->leftJoinSub($releasedAmounts, 'wr', function ($join) {
                    $join->on('wr.project_cd', '=', 'rb.project_cd')
                        ->on('wr.against_bill_no', '=', 'rb.bill_no');
                })
                ->select(
                    'rb.bill_no',
                    'rb.bill_amount',
                    'rb.withheld_amount',
                    DB::raw('COALESCE(wr.released_amount, 0) as released_amount'),
                    DB::raw('(rb.withheld_amount - COALESCE(wr.released_amount, 0)) as remaining_withheld_amount')
                )
                ->where('rb.project_cd', $project_cd)
                ->where('rb.bill_type', 'R')
                ->where('rb.withheld_amount', '>', 0)
                ->whereRaw('(rb.withheld_amount - COALESCE(wr.released_amount, 0)) > 0')
                ->orderBy('rb.bill_no')
                ->get();
            $lastBillNo = DB::table('prt_project_payment_details')
                ->where('project_cd', $project_cd)
                ->where('bill_type', 'R')
                ->max('bill_no');
            $nextBillNo = $lastBillNo ? $lastBillNo + 1 : 1;

            return view('pms.progress.enter_payment', compact('project', 'runningBills', 'nextBillNo'));
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

    public function store(Request $request)
    {
        $request->validate([
            'project_cd' => ['required', 'string'],
            'sel_bill_type' => ['required', 'in:R,W'],
            'against_bill_no' => ['required_if:sel_bill_type,W', 'nullable', 'array', 'min:1'],
            'against_bill_no.*' => ['integer', 'distinct'],
            'release_against_bill_no' => ['nullable', 'array'],
            'release_against_bill_no.*' => ['integer', 'distinct'],
            'release_withheld_amount' => ['nullable', 'numeric', 'gt:0'],
            'bill_amount' => ['required', 'numeric', 'gt:0'],
            'payment_amount' => ['required', 'numeric', 'gt:0'],
            'payment_date' => ['required', 'date'],
            'payment_reference' => ['required', 'string'],
            'remarks' => ['nullable', 'string'],
            'paymentDoc' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        $user = Auth::user();
        $userDeptCd = $user->department;
        $deptFolderName = "ROADS";
        switch ($userDeptCd) {
            case '3':
                $deptFolderName = "NATIONAL_HIGHWAY";
                break;
            case '6':
                $deptFolderName = "HOUSING";
                break;
            case '14':
                $deptFolderName = "ROADS";
                break;
            case '15':
                $deptFolderName = "MECHANICALS";
                break;
            default:
                $deptFolderName = "ROADS";
        }
        $response = DB::transaction(function () use ($request, $deptFolderName) {
            $bill_no = null;

            if ($request->sel_bill_type == 'R') {
                $bill = DB::table('prt_project_payment_details')
                    ->where('project_cd', $request->project_cd)
                    ->where('bill_type', 'R')
                    ->orderByDesc('bill_no')
                    ->lockForUpdate()
                    ->first();

                $bill_no = ($bill && $bill->bill_no) ? $bill->bill_no + 1 : 1;
            }

            $selectedBillNos = $request->sel_bill_type == 'W'
                ? $request->input('against_bill_no', [])
                : $request->input('release_against_bill_no', []);
            $withheldAllocations = [];

            foreach ($selectedBillNos as $selectedBillNo) {
                $selectedBill = DB::table('prt_project_payment_details')
                    ->where('project_cd', $request->project_cd)
                    ->where('bill_no', $selectedBillNo)
                    ->where('bill_type', 'R')
                    ->lockForUpdate()
                    ->first();

                if (!$selectedBill) {
                    return ['status' => 422, 'message' => 'One or more selected Running Bills are invalid.'];
                }

                $alreadyReleased = DB::table('prt_project_payment_details')
                    ->where('project_cd', $request->project_cd)
                    ->where('bill_type', 'W')
                    ->where('against_bill_no', $selectedBillNo)
                    ->sum('payment_amount');
                $available = max((float) $selectedBill->withheld_amount - (float) $alreadyReleased, 0);

                if ($available <= 0) {
                    return ['status' => 422, 'message' => 'A selected Running Bill has no withheld balance remaining.'];
                }

                $withheldAllocations[] = ['bill' => $selectedBill, 'available' => $available];
            }

            $totalSelectedWithheld = array_sum(array_column($withheldAllocations, 'available'));
            if ($request->sel_bill_type == 'W' && (float) $request->payment_amount > $totalSelectedWithheld) {
                return [
                    'status' => 422,
                    'message' => 'Payment Amount cannot exceed the selected withheld balance of ' . number_format($totalSelectedWithheld, 2) . '.'
                ];
            }

            $currentBillPayment = (float) $request->payment_amount;
            if ($request->sel_bill_type == 'R') {
                $project = DB::table('projects.prt_project_details')
                    ->where('project_cd', $request->project_cd)
                    ->lockForUpdate()
                    ->first();
                $totalBilledAmount = DB::table('prt_project_payment_details')
                    ->where('project_cd', $request->project_cd)
                    ->where('bill_type', 'R')
                    ->sum('bill_amount');
                $remainingBillAmount = max(
                    (float) $project->work_order_amount - (float) $totalBilledAmount,
                    0
                );

                if ((float) $request->bill_amount > $remainingBillAmount) {
                    return [
                        'status' => 422,
                        'message' => 'Bill Amount cannot exceed the remaining project bill amount of ' . number_format($remainingBillAmount, 2) . '.'
                    ];
                }

                if ($currentBillPayment <= 0 || $currentBillPayment > (float) $request->bill_amount) {
                    return [
                        'status' => 422,
                        'message' => 'Payment Amount must be greater than zero and cannot exceed the Bill Amount.'
                    ];
                }
            }

            $status = true;
            if ($request->sel_bill_type == 'R') {
                $status = DB::table('prt_project_payment_details')->insert([
                'project_cd' => $request->project_cd,
                'payment_amount' => $currentBillPayment,
                'payment_date' => $request->payment_date,
                'payment_reference' => $request->payment_reference,
                'remarks' => $request->remarks,
                'bill_no' => $bill_no,
                'bill_type' => 'R',
                'bill_amount' => $request->bill_amount,
                'against_bill_no' => null,
                'withheld_amount' => (float) $request->bill_amount - $currentBillPayment,
                'created_at' => now()
                ]);
            }

            $remainingPayment = $request->sel_bill_type == 'W'
                ? (float) $request->payment_amount
                : $totalSelectedWithheld;
            foreach ($withheldAllocations as $allocation) {
                $releaseAmount = min($allocation['available'], $remainingPayment);
                if ($releaseAmount <= 0) {
                    break;
                }
                $status = DB::table('prt_project_payment_details')->insert([
                    'project_cd' => $request->project_cd,
                    'payment_amount' => $releaseAmount,
                    'payment_date' => $request->payment_date,
                    'payment_reference' => $request->payment_reference,
                    'remarks' => $request->remarks,
                    'bill_no' => null,
                    'bill_type' => 'W',
                    'bill_amount' => $allocation['bill']->bill_amount,
                    'against_bill_no' => $allocation['bill']->bill_no,
                    'withheld_amount' => 0,
                    'created_at' => now()
                ]);
                $remainingPayment -= $releaseAmount;
            }
            if ($status) {
                $rootPath = config('filesystems.disks.external.root');
                if ($request->hasFile('paymentDoc')) {
                    $file = $request->file('paymentDoc');
                    $uniqueFileName = $request->project_cd . "_" . $request->payment_date . '_' . Str::uuid() . '.pdf';
                    $year = date('Y');
                    $folderPath = config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'PROJECTS/' . $deptFolderName . '/payment_docs/' . $year . '/';

                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0755, true, true);
                    }

                    Log::info($folderPath);
                    if (!Storage::exists($folderPath)) {
                        Storage::makeDirectory('public/' . $folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . "/" . $filePath;
                    DB::table('projects.prt_project_payment_document_details')->insert([
                        'project_cd' => $request->project_cd,
                        'file_path' => $completeFilePath,
                        'file_type' => "pdf",
                        'created_at' => now()
                    ]);
                }
                return [
                    'status' => 200,
                    'message' => 'Payment Record Saved successfully!'
                ];
            } else {
                return [
                    'status' => 500,
                    'message' => 'Payment Record Could Not be Saved!'
                ];
            }
        });


        return redirect()->back()->with(
            $response['status'] == 200 ? 'success' : 'failed',
            $response['message']
        );
    }

    public function getFinancialProgressDetailsProjectWise($project_cd)
    {
        try {
            $totalPaymentSubQuery = DB::table('prt_project_payment_details as p')
                ->selectRaw('p.project_cd, SUM(p.payment_amount) as total_payment')
                ->where('p.project_cd', $project_cd)
                ->groupBy('p.project_cd');

            $data = DB::table('prt_project_payment_details as pp')
                ->leftJoin('prt_project_details as p', 'p.project_cd', '=', 'pp.project_cd')
                ->leftJoinSub($totalPaymentSubQuery, 'tp', function ($join) {
                    $join->on('tp.project_cd', '=', 'pp.project_cd');
                })
                ->select(
                    'p.project_cd',
                    'p.work_order_amount',
                    'pp.id',
                    'pp.payment_date',
                    'pp.payment_amount',
                    'pp.payment_reference',
                    'pp.remarks',
                    'pp.bill_type',
                    'pp.bill_no',
                    'pp.against_bill_no',
                    'pp.bill_amount',
                    'pp.withheld_amount',
                    'pp.created_at',
                    'pp.updated_at',
                    DB::raw('COALESCE(tp.total_payment,0) as total_payment'),
                    DB::raw('COALESCE(p.work_order_amount - COALESCE(tp.total_payment,0), 0) as remaining_amount')
                )
                ->where('p.project_cd', $project_cd)
                ->orderBy('pp.payment_date')
                ->orderBy('pp.id')
                ->get();

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
        return response()->json($data);

    }
}
