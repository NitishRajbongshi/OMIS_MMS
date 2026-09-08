<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Models\AssetMasterDocumentCategory;
use App\Models\PMS\PrtContractorDetail;
use App\Models\PMS\PrtContractorDocumentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContractorController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'regn_no' => [
                    'required',
                    'max:30',
                    Rule::unique('pgsql_pms.prt_contractor_details', 'regn_no')
                ],
                'contractors_name' => [
                    'required',
                    'max:100',
                    Rule::unique('pgsql_pms.prt_contractor_details', 'contractors_name')
                ]
            ], [
                'regn_no.unique'          => 'Registration Number already exists.',
                'contractors_name.unique' => 'Contractor Name already exists.',
            ]);

           PrtContractorDetail::create([
                'regn_no'          => $request->regn_no,
                'contractors_name' => $request->contractors_name,
                'category_cd'      => $request->category_cd,
                'address_line1'    => $request->address_line_1,
                'address_line2'    => $request->address_line_2,
                'district_cd'      => $request->district_cd,
                'state_cd'         => $request->state_cd,
                'phone_no'         => $request->phone_no,
                'is_published'     => 'N',
                'created_at'       => now(),
                'updated_at'       => now()
            ]);

            $userid        = Auth::id();
            $randomNumber  = mt_rand(100, 999);
            $currentTime   = time();
            $randomCode    = $userid . $currentTime . $randomNumber;
            $reg_no        = $request->regn_no;

            $this->handleDocument($request, $randomCode, $reg_no);

            DB::commit();

            return response()->json([
                'status'           => 'success',
                'message'          => 'Contractor created successfully',
                'regn_no'          => $request->regn_no,
                'contractors_name' => $request->contractors_name
            ]);

        } catch (ValidationException $e) {

            DB::rollBack();
            Log::error($e);

            return response()->json([
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    private function handleDocument(Request $request, $randomCode, $reg_no)
    {
        $configPath      = config('customconfigpath.PMS_DOCS_PATH');
        $configImagePath = config('customconfigpath.PMS_ASSET_IMAGES_PATH');
        $rootPath        = config('filesystems.disks.external.root');

        // Passport Photo (base64 from cropper)
        if ($request->filled('passportPhoto')) {
            $base64String = $request->input('passportPhoto');

            $imageData = base64_decode(
                preg_replace('#^data:image/\w+;base64,#i', '', $base64String)
            );

            $randomNumber   = mt_rand(100, 999);
            $extension      = 'jpg';
            $uniqueFileName = 'pms_' . $randomCode . '_' . $randomNumber . '.' . $extension;
            $folderPath     = $configImagePath . now()->year;

            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            Storage::disk('external')->put($folderPath . '/' . $uniqueFileName, $imageData);
            $completeFilePath = $rootPath . '/' . $folderPath . '/' . $uniqueFileName;

            PrtContractorDocumentDetails::create([
                'regn_no'    => $reg_no,
                'file_path'  => $completeFilePath,
                'file_type'  => $extension,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
        }

        // PAN Card (PDF)
        if ($request->hasFile('panCardDoc')) {
            $file           = $request->file('panCardDoc');
            $extension      = $file->getClientOriginalExtension();
            $uniqueFileName = $randomCode . '_1.pdf';
            $folderPath     = $configPath . now()->year;

            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            $filePath         = $file->storeAs($folderPath, $uniqueFileName, 'external');
            $completeFilePath = $rootPath . '/' . $filePath;

            PrtContractorDocumentDetails::create([
                'regn_no'    => $reg_no,
                'file_path'  => $completeFilePath,
                'file_type'  => $extension,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
        }

        // Passbook / Cancelled Cheque (PDF)
        if ($request->hasFile('passbookDoc')) {
            $file           = $request->file('passbookDoc');
            $extension      = $file->getClientOriginalExtension();
            $uniqueFileName = $randomCode . '_2.pdf';
            $folderPath     = $configPath . now()->year;

            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            $filePath         = $file->storeAs($folderPath, $uniqueFileName, 'external');
            $completeFilePath = $rootPath . '/' . $filePath;

            PrtContractorDocumentDetails::create([
                'regn_no'    => $reg_no,
                'file_path'  => $completeFilePath,
                'file_type'  => $extension,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
        }
    }
}