<?php

namespace App\Http\Controllers\Road;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Road\AssetRoadDetail;
use App\Models\AssetMasterHeadWall;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterDocumentCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AssetMasterRdCdWorksType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterPavementCondition;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\PCI\AssetRoadPavementConditionIndex;
use App\Models\Road\PCI\AssetRoadPavementConditionIndexesDocumentDetail;
use App\Models\Road\PCI\AssetRoadPavementConditionIndexesDraft;
use App\Models\Road\PCI\AssetRoadPavementConditionIndexesImagesDetail;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RoadPCIController extends Controller
{
    public function __construct()
    {
        Log::info('PCI Controller.');
        DB::enableQueryLog();
        $this->middleware("auth");
    }

    public function index(Request $request)
    {
        $systemId = $request->id;
        $roadDetails = AssetRoadDetail::find($systemId);
        session(['system_id' => $systemId]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
        return redirect()->route('road.add-pci');
    }

    public function addPCIValue(Request $request)
    {
        try {
            $road_system_id = session('system_id');
            $headWalls = AssetMasterHeadWall::all();
            $cdWorkTypes = AssetMasterRdCdWorksType::all();
            $roadConditions = AssetMasterRoadCondition::all();
            $pavementCondition = AssetMasterPavementCondition::all();
            $pciDetails = DB::table('asset_road_pavement_condition_indexes_draft')
                ->select('*')
                ->where('rd_system_id', '=', $road_system_id)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->where('sent_for_finalize', '=', 'N')
                ->orderBy('updated_at', 'desc')
                ->get();
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();
            $pciPrevValue = DB::table('asset_road_pavement_condition_indexes_draft')
                ->select('pci_section_length_in_meter', 'chainage', 'updated_at')
                ->where('rd_system_id', '=', $road_system_id)
                ->orderBy('updated_at', 'desc')
                ->get()
                ->first();
            Log::info(DB::getQueryLog());
            return view('road.PCI.index', compact(
                'cdWorkTypes',
                'road_system_id',
                'headWalls',
                'roadConditions',
                'roadChainage',
                'pavementCondition',
                'pciDetails',
                'pciPrevValue'
            ));
        } catch (QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function storePCIValue(Request $request)
    {
        try {
            $system_id = $request->road_system_id;
            $userid = Auth::user()->id;
            $pci_val_endpoint = config('customconfigpath.PCI_FINAL_VALUE');

            // generating PCI code
            $randomNumber = mt_rand(100, 999);
            $currentTime = time();
            $pciCode = 'PCI_' . $userid . '_' . $currentTime . '_' . $randomNumber;

            $data = [
                'pci_section_cd' => $pciCode,
                'pci_section_length_in_meter' => $request->pci_section_length_in_meter,
                'rd_system_id' => $request->road_system_id,
                'chainage' => $request->chainage,
                'cracking_percent' => null,
                'ravelling_percent' => null,
                'pot_holes_percent' => null,
                'shoving_percent' => null,
                'patching_percent' => null,
                'settlement_depression_percent' => null,
                'rut_depth' => null,
                'tot_motorized_traffic_per_day' => $request->tot_motorized_traffic_per_day,
                'pv_traffic_light' => $request->pv_traffic_light,
                'pci_value' => null,
                'pci_remarks' => $request->pci_remarks,
                'created_by' => $userid,
                'updated_by' => $userid,
                'created_at_office_cd' => Auth::user()->office
            ];

            // Enter PCI manually
            if ($request->pci_method == 'M') {
                $data['pci_value'] = $request->pci;
            }

            // Calculate PCI 
            if ($request->pci_method == 'P') {
                // PCI parameters
                $crackingPercent = $request->cracking_percent ?? 0;
                $ravellingPercent = $request->ravelling_percent ?? 0;
                $potHolesPercent = $request->pot_holes_percent ?? 0;
                $shovingPercent = $request->shoving_percent ?? 0;
                $patchingPercent = $request->patching_percent ?? 0;
                $settlementDepressionPercent = $request->settlement_depression_percent ?? 0;
                $rutDepthPercent = $request->rut_depth ?? 0;

                // Calling the API to calculate PCI value
                $client = new Client();
                $response = $client->get($pci_val_endpoint, [
                    'query' => [
                        'cracking_percent' => $crackingPercent,
                        'ravelling_percent' => $ravellingPercent,
                        'pot_holes_percent' => $potHolesPercent,
                        'shoving_percent' => $shovingPercent,
                        'patching_percent' => $patchingPercent,
                        'settlement_depression_percent' => $settlementDepressionPercent,
                        'rut_depth_percent' => $rutDepthPercent,
                    ],
                ]);
                // Get the response body as a string
                $responseBody = $response->getBody()->getContents();
                // Decode the JSON response
                $pciData = json_decode($responseBody, true);
                // Access the final_pci_value
                $finalPciValue = $pciData['final_pci_value'];
                Log::info("Calculated PCI Value: " . $finalPciValue);
                if ($finalPciValue) {
                    $data['cracking_percent'] = $crackingPercent;
                    $data['ravelling_percent'] = $ravellingPercent;
                    $data['pot_holes_percent'] = $potHolesPercent;
                    $data['shoving_percent'] = $shovingPercent;
                    $data['patching_percent'] = $patchingPercent;
                    $data['settlement_depression_percent'] = $settlementDepressionPercent;
                    $data['rut_depth'] = $rutDepthPercent;
                    $data['pci_value'] = $finalPciValue;
                } else {
                    return redirect()->back()
                        ->with('failed', 'Failed to calculate the PCI value.')
                        ->with('rd_system_id', $system_id);
                }
            }
            //new code start by Pulak 
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('9');

            if ($makerCheckerStatus === 'Y') {
                $status = AssetRoadPavementConditionIndexesDraft::create($data);
            } else {
                $extraData = [
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),

                ];
                $status = AssetRoadPavementConditionIndex::create(array_merge($data, $extraData));
            }
            if ($status) {

                $this->handleDocument($request, $pciCode);
                return redirect()->back()
                    ->with('success', 'Value inserted successfully PCI Code is: ' . $pciCode)
                    ->with('rd_system_id', $system_id);
            } else {
                return redirect()->back()
                    ->with('failed', 'Value not inserted.')
                    ->with('rd_system_id', $system_id);
            }
        } catch (QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function handleDocument($request, $pciCode)
    {
        $configPath = config('customconfigpath.PCI_DOCS_PATH');
        $configImagePath = config('customconfigpath.PCI_IMAGES_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $randomNumber = mt_rand(100, 999);
                // get image extension
                $extension = $image->getClientOriginalExtension();
                $uniqueFileName = 'pci_' . $pciCode . '_' . $randomNumber . '.' . $extension;
                $folderPath = $configImagePath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $image->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                AssetRoadPavementConditionIndexesImagesDetail::create([
                    'pci_section_cd' => $pciCode,
                    'rd_system_id' => $request->road_system_id,
                    'image_path' => $completeFilePath,
                    'file_type' => $image->getClientOriginalExtension(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'created_at_office_cd' => Auth::user()->office,
                    'lat' => null,
                    'lon' => null,
                ]);
            }
        }

        if ($request->hasFile('workorder')) {
            $file = $request->file('workorder');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Work Order')->get()->first();
            $uniqueFileName = $pciCode . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementConditionIndexesDocumentDetail::create([
                'pci_section_cd' => $pciCode,
                'rd_system_id' => $request->road_system_id,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('design_doc')) {
            $file = $request->file('design_doc');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Design Document')->get()->first();
            $uniqueFileName = $pciCode . '_' . $docCatg['doc_catg_cd'] . '_2.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementConditionIndexesDocumentDetail::create([
                'pci_section_cd' => $pciCode,
                'rd_system_id' => $request->road_system_id,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('sanction_order')) {
            $file = $request->file('sanction_order');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Sanction Order')->get()->first();
            $uniqueFileName = $pciCode . '_' . $docCatg['doc_catg_cd'] . '_3.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementConditionIndexesDocumentDetail::create([
                'pci_section_cd' => $pciCode,
                'rd_system_id' => $request->road_system_id,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }

        if ($request->hasFile('inspection_report')) {
            $file = $request->file('inspection_report');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Inspection Report (Last)')->get()->first();
            $uniqueFileName = $pciCode . '_' . $docCatg['doc_catg_cd'] . '_4.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementConditionIndexesDocumentDetail::create([
                'pci_section_cd' => $pciCode,
                'rd_system_id' => $request->road_system_id,
                'file_path' => $completeFilePath,
                'file_type' => 'pdf',
                'doc_catg' => $docCatg['doc_catg_cd'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at_office_cd' => Auth::user()->office,
            ]);
        }
    }
}
//new code end by Pulak 