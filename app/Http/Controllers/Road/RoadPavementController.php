<?php

namespace App\Http\Controllers\Road;

use Illuminate\Http\Request;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterBaseLayerType;
use App\Models\AssetMasterDocumentCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterPavementType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterShoulderType;
use App\Models\AssetMasterSubBaseLayerType;
use App\Models\AssetMasterSurfaceType;
use App\Models\AssetRoadPavementDetail;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\Master\AssetMasterDrainageLineDrainageType;
use App\Models\Road\Master\AssetMasterDrainageSide;
use App\Models\Road\Master\AssetMasterDrainageType;
use App\Models\Road\Master\AssetMasterLandSlideSeverityDetail;
use App\Models\Road\Pavement\AssetRoadPavementDetailsDraft;
use App\Models\Road\Pavement\AssetRoadPavementDocumentDetail;
use App\Models\Road\Pavement\AssetRoadPavementDrainageDetail;
use App\Models\Road\Pavement\AssetRoadPavementImagesDetail;
use App\Models\Road\Pavement\AssetRoadPavementLandSlideDetail;
use App\Models\Road\Pavement\AssetRoadPavementShoulderDetail;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RoadPavementController extends Controller
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
        // saiful # 21-04-2026 # Start
        $asset_plan_id = $request->asset_plan_id;
        // saiful # 21-04-2026 # End
        $roadDetails = AssetRoadDetail::find($systemId);
        session(['system_id' => $systemId]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
        // saiful # 21-04-2026 # Start
        session(['asset_plan_id' => $asset_plan_id]);
        // saiful # 21-04-2026 # End
        return redirect()->route('createPavement');
    }

    public function create(Request $request)
    {
        try {
            $road_system_id = session('system_id');
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();

            $userid = Auth::user()->id;
            $pavementTypes = AssetMasterPavementType::all();
            $subBaseLayers = AssetMasterSubBaseLayerType::all();
            $baseLayers = AssetMasterBaseLayerType::all();
            $surfaceTypes = AssetMasterSurfaceType::all();
            $surfaceConditions = AssetMasterRoadCondition::all();
            $pavementDetails = DB::table('asset_road_pavement_details_draft')
                ->where('rd_system_id', '=', $road_system_id)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();
            return view('road.pavement.create', compact(
                'roadChainage',
                'pavementTypes',
                'subBaseLayers',
                'baseLayers',
                'surfaceTypes',
                'surfaceConditions',
                'pavementDetails',
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

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $system_id = $request->road_system_id;
            $randomCode = mt_rand(1000, 9999);

            $roadPavementData = [
                'rd_pavement_cd' => $randomCode,
                'rd_system_id' => $request->road_system_id,
                'start_chainage' => $request->start_chainage ?? 0.000,
                'end_chainage' => $request->end_chainage ?? (float) $request->road_length,
                'pavement_type_cd' => $request->pavement_type,
                'formation_width' => $request->formation_width,
                'carriage_width' => $request->carriage_width,
                'sub_base_layer_type_cd' => $request->subbase_layer_type,
                'base_layer_type_cd' => $request->base_layer_type,
                'surface_type_cd' => $request->surface_type,
                'sub_base_lyr_thickness' => $request->subbase_layer_thickness,
                'base_lyr_thickness' => $request->base_layer_thickness,
                'surface_lyr_thickness' => $request->surface_layer_thickness,
                'surface_condition' => $request->surface_condition,
                'has_shoulder' => $request->has_shoulder ?? 'N',
                'has_drainage' => $request->has_drainage ?? 'N',
                'is_land_slide_prone' => $request->is_land_slide_prone ?? 'N',
                'year_of_construction' => $request->construction_year,
                'construction_cost' => $request->construction_cost,
                'remarks' => $request->remark,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at_office_cd' => Auth::user()->office ?? null,
                'lat' => $request->lat ?? '0',
                'lng' => $request->lng ?? '0',
                'sent_for_finalize' => 'N',
                'sent_for_finalize_on' => null,
                'sent_for_finalize_by' => null,
                'is_rejected' => 'N',
                'reason_of_rejection' => null,
                'date_of_rejection' => null,
                'rejected_by' => null,
                // Saiful # 21-04-2026 # Start
                'asset_plan_id' => $request->hdn_asset_plan_id ?? null
                // Saiful # 21-04-2026 # End
            ];
            //new code start by Pulak  
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('2');

            if ($makerCheckerStatus === 'Y') {
                $status = AssetRoadPavementDetailsDraft::create($roadPavementData);
            } else {
                $extraData = [
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),
                ];
                $status = AssetRoadPavementDetail::create(array_merge($roadPavementData, $extraData));
            }

            if ($status) {
                //saiful 21-04-2026 -- Start
                if ($request->hdn_asset_plan_id != null)
                    $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                        ->where('id', $request->hdn_asset_plan_id)
                        ->where('no_of_new_asset', '>', 0)
                        ->decrement('no_of_new_asset', 1, [
                            'updated_at' => now(),
                            'remarks' => 'Bridge Created on: ' . now()
                        ]);
                session()->forget('asset_plan_id');
                //saiful 21-04-2026 -- End
                $this->handleDocument($request, $randomCode);
                return redirect()->back()
                    ->with('success', 'Pavement details save successfully with pavement number : ' . $randomCode)
                    ->with('rd_system_id', $system_id);
            } else {
                //saiful -- 22-04-2026 -- start
                session()->forget('asset_plan_id');
                //saiful -- 22-04-2026 -- End
                return redirect()->back()
                    ->with('failed', 'Pavement details not saved! Error occurred!')
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
            //saiful -- 22-04-2026 -- start
            session()->forget('asset_plan_id');
            //saiful -- 22-04-2026 -- End
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            //saiful -- 22-04-2026 -- start
            session()->forget('asset_plan_id');
            //saiful -- 22-04-2026 -- End
            return response()->view('errors.generic', [], 500);
        }
    }

    public function handleDocument($request, $randomCode)
    {
        $configPath = config('customconfigpath.PAVEMENT_DOCS_PATH');
        $configImagePath = config('customconfigpath.PAVEMENT_IMAGES_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $randomNumber = mt_rand(100, 999);
                // get image extension
                $extension = $image->getClientOriginalExtension();
                $uniqueFileName = 'pavement_' . $randomCode . '_' . $randomNumber . '.' . $extension;
                $folderPath = $configImagePath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $image->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                AssetRoadPavementImagesDetail::create([
                    'rd_pavement_cd' => $randomCode,
                    'rd_system_id' => $request->road_system_id,
                    'image_path' => $completeFilePath,
                    'file_type' => $image->getClientOriginalExtension(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'created_at_office_cd' => Auth::user()->office,
                    'lat' => $request->lat ?? '0',
                    'lon' => $request->lng ?? '0',
                ]);
            }
        }

        if ($request->hasFile('workorder')) {
            $file = $request->file('workorder');
            $docCatg = AssetMasterDocumentCategory::select('doc_catg_cd')
                ->where('doc_catg_descr', 'Work Order')->get()->first();
            $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementDocumentDetail::create([
                'rd_pavement_cd' => $randomCode,
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
            $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_2.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementDocumentDetail::create([
                'rd_pavement_cd' => $randomCode,
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
            $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_3.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementDocumentDetail::create([
                'rd_pavement_cd' => $randomCode,
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
            $uniqueFileName = $randomCode . '_' . $docCatg['doc_catg_cd'] . '_4.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetRoadPavementDocumentDetail::create([
                'rd_pavement_cd' => $randomCode,
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

    //new code end by Pulak 

    public function pavementSubsection(Request $request)
    {
        session(['pavement_id' => $request->id]);
        $asset_cd = $request->asset;
        if ($asset_cd == 0) {
            Log::info('Shoulder');
            return redirect()->route('pavement.create.shoulder');
        }
        if ($asset_cd == 1) {
            Log::info('Drainage');
            return redirect()->route('pavement.create.drainage');
        }
        if ($asset_cd == 2) {
            Log::info('Drainage');
            return redirect()->route('pavement.create.landslide');
        }
    }

    public function createShoulder()
    {
        try {
            $road_system_id = session('system_id');
            $pavement_id = session('pavement_id');
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();

            $userid = Auth::user()->id;
            $shoulderTypes = AssetMasterShoulderType::all();
            $pavementShoulderDetails = DB::table('asset_road_pavement_shoulder_details')
                ->where('rd_pavement_cd', '=', $pavement_id)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();
            return view('road.pavement.pavement_shoulder', compact(
                'roadChainage',
                'shoulderTypes',
                'pavementShoulderDetails',
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

    public function storeShoulder(Request $request)
    {
        try {
            $status = AssetRoadPavementShoulderDetail::create([
                'rd_pavement_cd' => $request->pavement_id,
                'shoulder_type_cd' => $request->shoulder_type,
                'shoulder_start_chainage' => $request->start_chainage,
                'shoulder_end_chainage' => $request->end_chainage,
                'shoulder_width' => $request->shoulder_width,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at_office_cd' => Auth::user()->office ?? null,
            ]);

            if ($status) {
                return redirect()->back()
                    ->with('success', 'Pavement shoulder details save successfully.');
            } else {
                return redirect()->back()
                    ->with('failed', 'Pavement shoulder details not saved! Error occurred!');
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

    public function createDrainage()
    {
        try {
            $road_system_id = session('system_id');
            $pavement_id = session('pavement_id');
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();

            $userid = Auth::user()->id;
            $drainageTypes = AssetMasterDrainageType::all();
            $drainageSides = AssetMasterDrainageSide::all();
            $lineDrainageTypes = AssetMasterDrainageLineDrainageType::all();
            $pavementDrainageDetails = DB::table('asset_road_pavement_drainage_details')
                ->where('rd_pavement_cd', '=', $pavement_id)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();
            return view('road.pavement.pavement_drainage', compact(
                'roadChainage',
                'drainageTypes',
                'drainageSides',
                'lineDrainageTypes',
                'pavementDrainageDetails',
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

    public function storeDrainage(Request $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Base Data
            $baseData = [
                'rd_pavement_cd' => $request->pavement_id,
                'drainage_type_cd' => $request->drainage_type,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at_office_cd' => Auth::user()->office ?? null,
                'created_at' => now(),  // Added for consistency
                'updated_at' => now(),  // Added for consistency
            ];

            // Common Closure for Creating Drainage Details
            $createDrainage = function ($side, $startChainage, $endChainage, $lineDrainageType) use ($baseData) {
                $data = array_merge($baseData, [
                    'drainage_side_cd' => $side,
                    'start_chainage' => $startChainage !== '' ? $startChainage : null,
                    'end_chainage' => $endChainage !== '' ? $endChainage : null,
                    'line_drainage_type_cd' => $lineDrainageType !== '' ? $lineDrainageType : null,
                ]);
                return AssetRoadPavementDrainageDetail::create($data);
            };

            $status = false;

            if ($request->drainage_type == '0') {
                if (in_array($request->drainage_side, ['0', '1'])) {
                    $status = $createDrainage(
                        $request->drainage_side,
                        $request->start_chainage,
                        $request->end_chainage,
                        $request->line_drainage_type
                    ) !== null;
                } elseif ($request->drainage_side == '2') {
                    $hillStatus = $createDrainage(
                        $request->hill_drainage_side,
                        $request->hill_start_chainage,
                        $request->hill_end_chainage,
                        $request->hill_line_drainage_type
                    ) !== null;

                    $valleyStatus = $createDrainage(
                        $request->valley_drainage_side,
                        $request->valley_start_chainage,
                        $request->valley_end_chainage,
                        $request->valley_line_drainage_type
                    ) !== null;

                    $status = $hillStatus && $valleyStatus;
                } else {
                    Log::warning("Invalid drainage_side value: " . $request->drainage_side, [
                        'url' => request()->fullUrl(),
                        'method' => request()->method(),
                        'drainage_side' => $request->drainage_side
                    ]);
                    DB::rollBack();
                    return redirect()->back()->with('failed', 'Invalid drainage side value.');
                }
            } else {
                $status = $createDrainage(null, null, null, null) !== null;
            }

            if ($status) {
                DB::commit();
                return redirect()->back()->with('success', 'Pavement drainage details saved successfully.');
            }

            DB::rollBack();
            return redirect()->back()->with('failed', 'Pavement drainage details not saved! Error occurred!');
        } catch (QueryException $e) {
            DB::rollBack();
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
            DB::rollBack();
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function createLandSlide()
    {
        try {
            $road_system_id = session('system_id');
            $pavement_id = session('pavement_id');
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();

            $userid = Auth::user()->id;
            $severityTypes = AssetMasterLandSlideSeverityDetail::all();
            $pavementLansSlideDetails = DB::table('asset_road_pavement_land_slide_details')
                ->where('rd_pavement_cd', '=', $pavement_id)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();
            return view('road.pavement.pavement_landslide', compact(
                'roadChainage',
                'severityTypes',
                'pavementLansSlideDetails',
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


    public function storeLandSlide(Request $request)
    {
        try {
            $status = AssetRoadPavementLandSlideDetail::create([
                'rd_pavement_cd' => $request->pavement_id,
                'land_slide_start_chainage' => $request->start_chainage,
                'land_slide_end_chainage' => $request->end_chainage,
                'severity_cd' => $request->severity_type,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at_office_cd' => Auth::user()->office ?? null,
            ]);

            if ($status) {
                return redirect()->back()
                    ->with('success', 'Pavement LandSlide details save successfully.');
            } else {
                return redirect()->back()
                    ->with('failed', 'Pavement LandSlide details not saved! Error occurred!');
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
}
