<?php

namespace App\Http\Controllers\Contractor;

use App\Http\Controllers\Controller;
use App\Models\AssetMasterDocumentCategory;
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
         //newly modified by pulak 13-05-26
        // DB::beginTransaction();

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
                'regn_no.unique' => 'Registration Number already exists.',
                'contractors_name.unique' => 'Contractor Name already exists.',
            ]);

            DB::table('projects.prt_contractor_details')->insert([
                'regn_no' => $request->regn_no,
                'contractors_name' => $request->contractors_name,
                'category_cd' => $request->category_cd,
                'address_line1' => $request->address_line_1,
                'address_line2' => $request->address_line_2,
                'district_cd' => $request->district_cd,
                'state_cd' => $request->state_cd,
                'phone_no' => $request->phone_no,
                'is_published' => 'N',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            //DB::commit();
								  
            // $this->handleDocument($request, $request->regn_no);

            return response()->json([
                'status' => 'success',
                'message'=> "Contractor created successfully",
                'regn_no'=> $request->regn_no,
                'contractors_name'=> $request->contractors_name
            ]);

        } catch (ValidationException $e) {

            // DB::rollBack();
            Log::error($e);

            return response()->json([
                'errors' => $e->errors()
            ], 422);
																
        } catch (\Exception $e) {

            // DB::rollBack();

            Log::error($e);
										
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
     //newly modified by pulak 13-05-26

    // private function handleDocument($request, $randomCode, $reg_no)
    // {
    //     $configPath = config('customconfigpath.PMS_DOCS_PATH');
    //     $configImagePath = config('customconfigpath.PMS_ASSET_IMAGES_PATH');
    //     $rootPath = config('filesystems.disks.external.root');

    //     //Now reads base64 from hidden input instead of hasFile()
    //     if ($request->filled('passportPhoto')) {
    //         $base64String = $request->input('passportPhoto');

    //         // Strip the data URI prefix: "data:image/jpeg;base64,XXXX"
    //         $imageData = base64_decode(
    //             preg_replace('#^data:image/\w+;base64,#i', '', $base64String)
    //         );

    //         $randomNumber = mt_rand(100, 999);

    //         $extension = 'jpg'; // Always jpg since cropper exports as jpeg
    //         $uniqueFileName = 'pms_' . $randomCode . '_' . $randomNumber . '.' . $extension;
    //         $folderPath = $configImagePath . now()->year;

    //         if (!Storage::disk('external')->exists($folderPath)) {
    //             Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
    //         }

    //         //Use put() with raw binary data instead of storeAs()
    //         Storage::disk('external')->put($folderPath . '/' . $uniqueFileName, $imageData);
    //         $completeFilePath = $rootPath . '/' . $folderPath . '/' . $uniqueFileName;

    //         PrtContractorDocumentDetails::create([
    //             'regn_no' => $reg_no,
    //             'file_path' => $completeFilePath,
    //             'file_type' => $extension,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //             'created_by' => auth()->id(),
    //             'updated_by' => auth()->id()
    //         ]);
    //     }
    //     // Code for PAN Card
    //     // Do not change hasFile() value
    //     if ($request->hasFile('panCardDoc')) {
    //         $file = $request->file('panCardDoc');

    //         $extension = $file->getClientOriginalExtension();
    //         $uniqueFileName = $randomCode . '_' . '_1.pdf';
    //         $folderPath = $configPath . now()->year;

    //         // Use the 'external' disk to store the file
    //         if (!Storage::disk('external')->exists($folderPath)) {
    //             Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
    //         }

    //         // Store the file using the 'external' disk
    //         $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
    //         // Combine the root path and folder path to get the complete file path
    //         $completeFilePath = $rootPath . '/' . $filePath;

    //         PrtContractorDocumentDetails::create([
    //             'regn_no' => $reg_no,
    //             'file_path' => $completeFilePath,
    //             'file_type' => $extension,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //             'created_by' => auth()->id(),
    //             'updated_by' => auth()->id()
    //         ]);
    //     }
    // }
}