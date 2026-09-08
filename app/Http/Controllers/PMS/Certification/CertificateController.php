<?php

namespace App\Http\Controllers\PMS\Certification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;

class CertificateController extends Controller
{
    public function index()
    {
        $userDeptCd = Auth::user()->department;

        $latestApprovedMovements = DB::table('prt_project_report_movements as prm')
            ->select('prm.id', 'prm.project_cd')
            ->where('prm.movement_status', 3)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('prt_project_report_movements as newer_prm')
                    ->whereColumn('newer_prm.report_id', 'prm.report_id')
                    ->whereColumn('newer_prm.id', '>', 'prm.id');
            });

        $pendingProjects = DB::table('prt_project_details as p')
            ->joinSub($latestApprovedMovements, 'approved_movement', function ($join) {
                $join->on('approved_movement.project_cd', '=', 'p.project_cd');
            })
            ->leftJoin('prt_contractor_details as contractor', 'contractor.regn_no', '=', 'p.project_awarded_to')
            ->where('p.owner_dept_cd', $userDeptCd)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('prt_project_completion_certificate_details as certificate')
                    ->whereColumn('certificate.project_cd', 'p.project_cd');
            })
            ->orderBy('p.project_cd')
            ->get([
                'p.project_cd as id',
                'p.project_name as name',
                'contractor.contractors_name as contractor',
                'p.project_start_date as start_date',
                'p.project_end_date as end_date',
                'p.work_order_amount as cost',
            ])
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'contractor' => $project->contractor ?? 'N/A',
                    'start_date' => $project->start_date,
                    'end_date' => $project->end_date,
                    'cost' => number_format((float) ($project->cost ?? 0), 2),
                    'status' => 'Approved',
                ];
            });

        $downloadCounts = DB::table('prt_project_completion_certificate_logs')
            ->select('certificate_id', DB::raw('COUNT(*) as download_count'))
            ->where('action', 'DOWNLOAD')
            ->groupBy('certificate_id');

        $generatedCertificates = DB::table('prt_project_completion_certificate_details as certificate')
            ->join('prt_project_details as p', 'p.project_cd', '=', 'certificate.project_cd')
            ->leftJoin('users as issuer', 'issuer.id', '=', 'certificate.issued_by')
            ->leftJoinSub($downloadCounts, 'downloads', function ($join) {
                $join->on('downloads.certificate_id', '=', 'certificate.certificate_id');
            })
            ->where('p.owner_dept_cd', $userDeptCd)
            ->orderByDesc('certificate.issued_on')
            ->get([
                'certificate.project_cd as id',
                'p.project_name as name',
                'certificate.certificate_number',
                'certificate.issued_on as generation_date',
                'issuer.name as generated_by',
                DB::raw('COALESCE(downloads.download_count, 0) as download_count'),
            ])
            ->map(function ($certificate) {
                return (array) $certificate;
            });

        return view('pms.certification.listOfCertifiedProjects', compact('pendingProjects', 'generatedCertificates'));
    }

    public function generate($projectId)
    {
        $user = Auth::user();
        $certificateNumber = 'CERT-' . now()->format('Y') . '-' . strtoupper(Str::random(10));
        $relativeFilePath = null;

        try {
            DB::transaction(function () use (
                $projectId,
                $user,
                $certificateNumber,
                &$relativeFilePath
            ) {
                $project = DB::table('prt_project_details as p')
                    ->where('p.project_cd', $projectId)
                    ->where('p.owner_dept_cd', $user->department)
                    ->lockForUpdate()
                    ->first([
                        'p.project_cd',
                        'p.project_name',
                        'p.project_start_date',
                        'p.project_end_date',
                        'p.work_order_amount',
                        'p.work_order_no',
                        'p.project_awarded_to',
                    ]);

                if (!$project) {
                    throw new \RuntimeException('Project not found.');
                }

                $approvalMovement = DB::table('prt_project_report_movements as prm')
                    ->where('prm.project_cd', $projectId)
                    ->where('prm.movement_status', 3)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('prt_project_report_movements as newer_prm')
                            ->whereColumn('newer_prm.report_id', 'prm.report_id')
                            ->whereColumn('newer_prm.id', '>', 'prm.id');
                    })
                    ->orderByDesc('prm.id')
                    ->first(['prm.id']);

                if (!$approvalMovement) {
                    throw new \RuntimeException('The project completion report is not approved.');
                }

                if (DB::table('prt_project_completion_certificate_details')->where('project_cd', $projectId)->exists()) {
                    throw new \RuntimeException('A completion certificate has already been issued for this project.');
                }

                $contractorName = DB::table('prt_contractor_details')
                    ->where('regn_no', $project->project_awarded_to)
                    ->value('contractors_name');

                $configCertPath = config('customconfigpath.CERTIFICATE_DOCS_PATH');
                $folderPath = rtrim($configCertPath, '/') . '/' . now()->year;
                $uniqueFileName = "{$projectId}_{$certificateNumber}.pdf";
                $relativeFilePath = $folderPath . '/' . $uniqueFileName;

                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                $issuedOn = now();
                $certificateData = [
                    'id' => $project->project_cd,
                    'name' => $project->project_name,
                    'contractor' => $contractorName ?? 'N/A',
                    'start_date' => $project->project_start_date,
                    'end_date' => $project->project_end_date,
                    'cost' => number_format((float) ($project->work_order_amount ?? 0), 2),
                    'work_order_no' => $project->work_order_no ?? '',
                    'certificate_number' => $certificateNumber,
                    'generation_date' => $issuedOn->format('Y-m-d H:i:s'),
                    'file_path' => $relativeFilePath,
                    'generated_by' => $user->name,
                    'download_count' => 0,
                ];

                $pdfContent = Pdf::loadView('pms.certification.certificateTemplate', compact('certificateData'))
                    ->setPaper('a4', 'landscape')
                    ->output();

                if (!Storage::disk('external')->put($relativeFilePath, $pdfContent)) {
                    throw new \RuntimeException('Unable to store the certificate PDF.');
                }

                $certificateId = DB::table('prt_project_completion_certificate_details')->insertGetId([
                    'project_cd' => $projectId,
                    'version_no' => '1.0',
                    'file_path' => $relativeFilePath,
                    'issued_on' => $issuedOn,
                    'issued_by' => $user->id,
                    'is_digitally_signed' => 'N',
                    'remarks' => null,
                    'certificate_number' => $certificateNumber,
                    'approval_movement_id' => $approvalMovement->id,
                    'created_at' => $issuedOn,
                    'updated_at' => $issuedOn,
                ], 'certificate_id');

                $this->logCertificateAction($certificateId, 'GENERATED', $user->id);
            });

            return redirect()
                ->route('pms.certificates.index')
                ->with('success', "Certificate {$certificateNumber} generated.");
        } catch (Throwable $exception) {
            if ($relativeFilePath && Storage::disk('external')->exists($relativeFilePath)) {
                Storage::disk('external')->delete($relativeFilePath);
            }

            Log::error('Completion certificate generation failed.', [
                'project_cd' => $projectId,
                'user_id' => $user->id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->back()->with('failed', $exception->getMessage());
        }
    }


    public function generateCopy($projectId)
    {
        $user = Auth::user();
        $certificateNumber = 'CERT-' . now()->format('Y') . '-' . strtoupper(Str::random(10));
        $relativeFilePath = null;

        try {
            DB::transaction(function () use (
                $projectId,
                $user,
                $certificateNumber,
                &$relativeFilePath
            ) {
                $project = DB::table('prt_project_details as p')
                    ->where('p.project_cd', $projectId)
                    ->where('p.owner_dept_cd', $user->department)
                    ->lockForUpdate()
                    ->first([
                        'p.project_cd',
                        'p.project_name',
                        'p.project_start_date',
                        'p.project_end_date',
                        'p.work_order_amount',
                        'p.work_order_no',
                        'p.project_awarded_to',
                    ]);

                if (!$project) {
                    throw new \RuntimeException('Project not found.');
                }

                $approvalMovement = DB::table('prt_project_report_movements as prm')
                    ->where('prm.project_cd', $projectId)
                    ->where('prm.movement_status', 3)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('prt_project_report_movements as newer_prm')
                            ->whereColumn('newer_prm.report_id', 'prm.report_id')
                            ->whereColumn('newer_prm.id', '>', 'prm.id');
                    })
                    ->orderByDesc('prm.id')
                    ->first(['prm.id']);

                if (!$approvalMovement) {
                    throw new \RuntimeException('The project completion report is not approved.');
                }

                if (DB::table('prt_project_completion_certificate_details')->where('project_cd', $projectId)->exists()) {
                    throw new \RuntimeException('A completion certificate has already been issued for this project.');
                }

                $contractorName = DB::table('prt_contractor_details')
                    ->where('regn_no', $project->project_awarded_to)
                    ->value('contractors_name');

                $configCertPath = config('customconfigpath.CERTIFICATE_DOCS_PATH');
                $folderPath = rtrim($configCertPath, '/') . '/' . now()->year;
                $uniqueFileName = "{$projectId}_{$certificateNumber}.pdf";
                $relativeFilePath = $folderPath . '/' . $uniqueFileName;

                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                $issuedOn = now();
                $certificateData = [
                    'id' => $project->project_cd,
                    'name' => $project->project_name,
                    'contractor' => $contractorName ?? 'N/A',
                    'start_date' => $project->project_start_date,
                    'end_date' => $project->project_end_date,
                    'cost' => number_format((float) ($project->work_order_amount ?? 0), 2),
                    'work_order_no' => $project->work_order_no ?? '',
                    'certificate_number' => $certificateNumber,
                    'generation_date' => $issuedOn->format('Y-m-d H:i:s'),
                    'file_path' => $relativeFilePath,
                    'generated_by' => $user->name,
                    'download_count' => 0,
                ];

                $pdfContent = Pdf::loadView('pms.certification.certificateTemplate', compact('certificateData'))
                    ->setPaper('a4', 'landscape')
                    ->output();

                if (!Storage::disk('external')->put($relativeFilePath, $pdfContent)) {
                    throw new \RuntimeException('Unable to store the certificate PDF.');
                }

                $certificateId = DB::table('prt_project_completion_certificate_details')->insertGetId([
                    'project_cd' => $projectId,
                    'version_no' => '1.0',
                    'file_path' => $relativeFilePath,
                    'issued_on' => $issuedOn,
                    'issued_by' => $user->id,
                    'is_digitally_signed' => 'N',
                    'remarks' => null,
                    'certificate_number' => $certificateNumber,
                    'approval_movement_id' => $approvalMovement->id,
                    'created_at' => $issuedOn,
                    'updated_at' => $issuedOn,
                ], 'certificate_id');

                $this->logCertificateAction($certificateId, 'GENERATED', $user->id);
            });

            return redirect()
                ->route('pms.certificates.index')
                ->with('success', "Certificate {$certificateNumber} generated.");
        } catch (Throwable $exception) {
            if ($relativeFilePath && Storage::disk('external')->exists($relativeFilePath)) {
                Storage::disk('external')->delete($relativeFilePath);
            }

            Log::error('Completion certificate generation failed.', [
                'project_cd' => $projectId,
                'user_id' => $user->id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->back()->with('failed', $exception->getMessage());
        }
    }
    public function viewCertificate($projectId)
    {
        $certificate = $this->findAccessibleCertificate($projectId);

        if (!$certificate || !Storage::disk('external')->exists($certificate->file_path)) {
            return redirect()->back()->with('failed', 'File not found on external disk storage.');
        }

        $this->logCertificateAction($certificate->certificate_id, 'VIEW', Auth::id());
        $file = Storage::disk('external')->get($certificate->file_path);
        $mimeType = Storage::disk('external')->mimeType($certificate->file_path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }

    public function downloadCertificate($projectId)
    {
        $certificate = $this->findAccessibleCertificate($projectId);

        if (!$certificate) {
            return redirect()->back()->with('failed', 'Certificate data not found.');
        }

        if (!Storage::disk('external')->exists($certificate->file_path)) {
            return redirect()->back()->with('failed', 'Certificate file not found on external storage.');
        }

        $this->logCertificateAction($certificate->certificate_id, 'DOWNLOAD', Auth::id());
        $customDownloadName = "{$certificate->certificate_number}.pdf";

        return Storage::disk('external')->download($certificate->file_path, $customDownloadName);
    }

    private function findAccessibleCertificate($projectId)
    {
        return DB::table('prt_project_completion_certificate_details as certificate')
            ->join('prt_project_details as p', 'p.project_cd', '=', 'certificate.project_cd')
            ->where('certificate.project_cd', $projectId)
            ->where('p.owner_dept_cd', Auth::user()->department)
            ->first([
                'certificate.certificate_id',
                'certificate.certificate_number',
                'certificate.file_path',
            ]);
    }

    private function logCertificateAction($certificateId, $action, $userId)
    {
        $performedOn = now();

        DB::table('prt_project_completion_certificate_logs')->insert([
            'request_id' => null,
            'certificate_id' => $certificateId,
            'action' => $action,
            'perfomed_by' => $userId,
            'performed_on' => $performedOn,
            'created_at' => $performedOn,
            'updated_at' => $performedOn,
        ]);
    }
}
