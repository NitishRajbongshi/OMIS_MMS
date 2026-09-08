<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ViewTenderAndNoticesController extends Controller
{
    public function viewTenderAndNoticesByCitizen()
    {
        DB::enableQueryLog();
        $tender_dtls = DB::table('tbl_tender_dtls AS tend')
            ->select(
                "tend.tender_cd",
                "tend.tender_title",
                "tend.tender_desc",
                "tend.dept_cd",
                "dpt.department_name",
                "tend.date_expiry"
            )
            ->join('department_details AS dpt', 'dpt.id', '=', 'tend.dept_cd')
            ->where('tend.is_expired', '=', 'N')
            ->orderBy('tend.created_at', 'desc')
            ->get();

        $query = DB::getQueryLog();
        Log::info(end($query));

        $tender_doc_dtls = DB::table('tbl_tender_documents_dtls AS tend_doc')
            ->select(
                "tend_doc.tender_cd",
                "tend_doc.file_path",
                "tend_doc.file_name"
            )
            ->join('tbl_tender_dtls AS tend', 'tend.tender_cd', '=', 'tend_doc.tender_cd')
            ->where('tend.is_expired', '=', 'N')
            ->orderBy('tend_doc.created_at', 'desc')
            // ->orderBy('tend_doc.tender_cd', 'desc')
            ->get();


        $notice_dtls = DB::table('tbl_notification_dtls AS notice')
            ->select(
                "notice.notice_cd",
                "notice.notice_title",
                "notice.notice_desc",
                "notice.dept_cd",
                "dpt.department_name",
                "notice.date_expiry"
            )
            ->join('department_details AS dpt', 'dpt.id', '=', 'notice.dept_cd')
            ->where('notice.is_expired', '=', 'N')
            ->orderBy('notice.created_at', 'desc')
            ->get();

        return view(
            'viewTenderAndNotices',
            compact(
                'tender_dtls',
                'tender_doc_dtls',
                'notice_dtls'

            )
        );
    }

    public function download_file(Request $request)
    {
        if (!Auth::check()) {
            abort(403);
        }
        $tender_doc_dtls = DB::table('tbl_tender_documents_dtls AS tend_doc')
            ->select(
                "tend_doc.tender_cd",
                "tend_doc.file_path",
                "tend_doc.file_name"
            )
            ->where('tend_doc.tender_cd', '=', $request->id)
            ->first();
        if (!$tender_doc_dtls) {
            abort(404);
        }
        $path = $tender_doc_dtls->file_path;
        $realFile = realpath($path);

        if (!$realFile) {
            abort(404);
        }
        $allowedRoot = realpath(config('filesystems.disks.external.root'));
        if (strpos($realFile, $allowedRoot) !== 0) {
            abort(403);
        }
        return response()->download($path, $tender_doc_dtls->file_name);
    }
    public function download_notification_file(Request $request)
    {
        if (!Auth::check()) {
            abort(403);
        }
        $tender_doc_dtls = DB::table('tbl_notification_documents_dtls AS notice_doc')
            ->select(
                "notice_doc.notice_cd",
                "notice_doc.file_path",
                "notice_doc.file_name"
            )
            ->where('notice_doc.notice_cd', '=', $request->id)
            ->first();
        if (!$tender_doc_dtls) {
            abort(404);
        }

        $path = $tender_doc_dtls->file_path;
        $realFile = realpath($path);
        if (!$realFile) {
            abort(404);
        }

        $allowedRoot = realpath(config('filesystems.disks.external.root'));
        if (strpos($realFile, $allowedRoot) !== 0) {
            abort(403);
        }
        return response()->download($path, $tender_doc_dtls->file_name);
    }
}
