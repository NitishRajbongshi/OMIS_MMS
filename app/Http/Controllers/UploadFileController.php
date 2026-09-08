<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\UserMenuDetail;
use App\Models\DepartmentDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\TblTenderDocumentsDtls;
use App\Models\TblTenderDtls;
use App\Models\TblNotificationDtls;
use App\Models\TblNotificationDocumentsDtls;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UploadFileController extends Controller
{
    public function __construct()
    {

        $this->middleware("auth");
    }
    public function uploadTenderViewPage()
    {
        $deptData = DepartmentDetail::find(session('user_dept_cd'));
        $deptName = $deptData->department_name;
        $deptCd = $deptData->id;
        return view("uploads.uploadTender", compact('deptCd', 'deptName'));
    }

    public function uploadNotificationsViewPage()
    {
        $deptData = DepartmentDetail::find(session('user_dept_cd'));
        $deptName = $deptData->department_name;
        $deptCd = $deptData->id;
        return view("uploads.uploadNotification", compact('deptCd', 'deptName'));
    }

    public function saveTenderDetails(Request $request)
    {
        try {
            $request->validate([
                'firstFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'secondFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'thirdFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fourthFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            $user_id = Auth::user()->id;
            $prefix = rand(10, 99);
            $postfix = rand(100, 999);
            $date = Carbon::now();
            $formatedDate = $date->format('YmdHis');
            $tender_code = "Tender_" . $prefix . $user_id . $formatedDate . $postfix;

            $data = [
                'tender_cd' => $tender_code,
                'tender_title' => $request->txt_tender_title,
                'tender_desc' => $request->txt_tender_descr,
                'date_expiry' => $request->tender_expiry_date,
                'is_expired' => 'N',
                'dept_cd' => $request->txtDeptCD,
                'created_by' => $user_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            $status = TblTenderDtls::create($data);

            if ($status) {
                $configPath = config('customconfigpath.TENDER_DOCS_PATH');
                $rootPath = config('filesystems.disks.external.root');


                if ($request->hasFile('firstFile')) {
                    Log::info("Has File to upload");
                    $file = $request->file('firstFile');

                    $extension = $file->extension();
                    $uniqueFileName = Str::uuid() . '_1.' . $extension;


                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $filePath;

                    TblTenderDocumentsDtls::create([
                        'tender_cd' => $tender_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $extension,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }


                if ($request->hasFile('secondFile')) {
                    $file = $request->file('secondFile');

                    $extension = $file->extension();
                    $uniqueFileName = Str::uuid() . '_2.' . $extension;

                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $filePath;

                    TblTenderDocumentsDtls::create([
                        'tender_cd' => $tender_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $extension,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }

                if ($request->hasFile('thirdFile')) {
                    $file = $request->file('thirdFile');

                    $extension = $file->extension();
                    $uniqueFileName = Str::uuid() . '_3.' . $extension;

                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $filePath;

                    TblTenderDocumentsDtls::create([
                        'tender_cd' => $tender_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $extension,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }

                if ($request->hasFile('fourthFile')) {
                    $file = $request->file('fourthFile');

                    $extension = $file->extension();
                    $uniqueFileName = Str::uuid() . '_4.' . $extension;


                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $filePath;

                    TblTenderDocumentsDtls::create([
                        'tender_cd' => $tender_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $extension,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }

                return redirect()->back()
                    ->with('success', 'Tender Uploaded successfully with Tender code ' . $tender_code);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to Upload Tender ');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function saveNotificationDetails(Request $request)
    {
        // TblNotificationDtls;
        // TblNotificationDocumentsDtls;
        try {
            $user_id = Auth::user()->id;
            $prefix = rand(10, 99);
            $postfix = rand(100, 999);
            $date = Carbon::now();
            $formatedDate = $date->format('YmdHis');
            $notice_code = "Notice_" . $prefix . $user_id . "_" . $request->txtDeptCD . "_" . $formatedDate . $postfix;

            $data = [
                'notice_cd' => $notice_code,
                'notice_title' => $request->txt_notification_title,
                'notice_desc' => $request->txt_notice_descr,
                'date_expiry' => $request->notification_expiry_date,
                'is_expired' => 'N',
                'dept_cd' => $request->txtDeptCD,
                'created_by' => $user_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ];

            $status = TblNotificationDtls::create($data);

            if ($status) {
                $configPath = config('customconfigpath.NOTIFICATION_DOCS_PATH');
                $rootPath = config('filesystems.disks.external.root');
                if ($request->hasFile('firstFile')) {
                    Log::info("Has File to upload");
                    $file = $request->file('firstFile');

                    $filename = $_FILES["firstFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["firstFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    Log::info($arrfileType[1]);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_1_' . $formatedDate . "." . $fileTypeWithoutSeperator;
                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    TblNotificationDocumentsDtls::create([
                        'notice_cd' => $notice_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }


                if ($request->hasFile('secondFile')) {
                    $file = $request->file('secondFile');

                    // $uniqueFileName = "secondFile" . '_' . $notice_code . $formatedDate . '.pdf';
                    $filename = $_FILES["secondFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["secondFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_2_' . $formatedDate . "." . $fileTypeWithoutSeperator;
                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    TblNotificationDocumentsDtls::create([
                        'notice_cd' => $notice_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }

                if ($request->hasFile('thirdFile')) {
                    $file = $request->file('thirdFile');

                    // $uniqueFileName = "thirdFile" . '_' . $notice_code . $formatedDate . '.pdf';
                    $filename = $_FILES["thirdFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["thirdFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_3_' . $formatedDate . "." . $fileTypeWithoutSeperator;
                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    TblNotificationDocumentsDtls::create([
                        'notice_cd' => $notice_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }

                if ($request->hasFile('fourthFile')) {
                    $file = $request->file('fourthFile');

                    // $uniqueFileName = "fourthFile" . '_' . $notice_code . $formatedDate . '.pdf';
                    $filename = $_FILES["fourthFile"]["name"];
                    $filename_without_ext = substr($filename, 0, strrpos($filename, "."));
                    $fileTypeWithSeperator = $_FILES["fourthFile"]["type"];
                    $arrfileType = explode("/", $fileTypeWithSeperator);
                    Log::info($arrfileType);
                    $fileTypeWithoutSeperator = $arrfileType[1];
                    $uniqueFileName = $filename_without_ext . '_4_' . $formatedDate . "." . $fileTypeWithoutSeperator;
                    $folderPath = $configPath . now()->year;

                    // Use the 'external' disk to store the file
                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                        // Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                    }

                    // Store the file using the 'external' disk
                    $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
                    // Combine the root path and folder path to get the complete file path
                    $completeFilePath = $rootPath . '/' . $filePath;

                    TblNotificationDocumentsDtls::create([
                        'notice_cd' => $notice_code,
                        'file_path' => $completeFilePath,
                        'file_name' => $uniqueFileName,
                        'file_type' => $fileTypeWithoutSeperator,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => $user_id
                    ]);
                }

                return redirect()->back()
                    ->with('success', 'Notification Uploaded successfully with Notice code ' . $notice_code);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to Upload Notification ');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function loadShortMsgPage()
    {
        return view("uploads.broadCastMsgByAdmin");
    }


    public function submitShortMsg(Request $request)
    {
        $duration = $request->txtMinute;
        Cache::put('global_message', $request->txtBroadcastMsg, now()->addMinutes($duration));
        return view("uploads.broadCastMsgByAdmin");
    }

}