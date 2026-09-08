<?php

namespace App\Http\Controllers\Road;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterDocumentCategory;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\AssetRoadDetail;
use App\Models\Road\Master\AssetMasterProtectionWallStructureType;
use App\Models\Road\Master\AssetMasterProtectionWallType;
use App\Models\Road\Protection_Wall\AssetProtectionWallDetail;
use App\Models\Road\Protection_Wall\AssetProtectionWallDetailsDraft;
use App\Models\Road\Protection_wall\AssetProtectionWallDocumentDetail;
use App\Models\Road\Protection_wall\AssetProtectionWallImagesDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProtectionWallController extends Controller
{
    public function __construct()
    {
        Log::info('Protection Wall Controller.');
        DB::enableQueryLog();
        $this->middleware("auth");
    }
    function index(Request $request)
    {
        $systemId = $request->id;
        // saiful # 21-04-2026 # Start
        $asset_plan_id = $request->asset_plan_id;
        // saiful # 21-04-2026 # End
        $roadDetails = AssetRoadDetail::find($systemId);
        session(['system_id' => $systemId]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
        // saiful # 21-04-2026 # Start
        session(['asset_plan_id' => $asset_plan_id]);
        // saiful # 21-04-2026 # End
        return redirect()->route('road.store.protection');
    }
    function create(Request $request)
    {
        try {
            //Saiful -- 29-04-2026 -- Start
            $assetPlanId = session('asset_plan_id');
            $redefineAssetFromProject = $request->redefineAssetFromProject ?? false;
            //Saiful -- 29-04-2026 -- Start
            $road_system_id = session('system_id');
            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();
            $protectionWallDetails = DB::table('asset_protection_wall_details_draft')
                ->select(
                    'asset_protection_wall_details_draft.*',
                    'asset_master_protection_wall_type.wall_type_descr',
                    'asset_master_protection_wall_structure_type.structure_type_descr',
                    //Saiful -- 05-05-2026 -- Start
                    'pp.project_cd'
                    //Saiful -- 05-05-2026 -- End
                )
                ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details_draft.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
                ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details_draft.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
                ->leftJoin('prt_project_asset_plan as pp', 'pp.id', '=', 'asset_protection_wall_details_draft.asset_plan_id')//saiful 05-05-2026
                ->where('asset_protection_wall_details_draft.rd_system_id', '=', $road_system_id)
                ->where('sent_for_finalize', '=', 'N')
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();
            $protectionWallTypes = AssetMasterProtectionWallType::all();
            $superStructureTypes = AssetMasterProtectionWallStructureType::all();
            Log::info(DB::getQueryLog());
            return view('road.protection_wall.index', compact(
                'road_system_id',
                'roadChainage',
                'protectionWallDetails',
                'protectionWallTypes',
                'superStructureTypes',
                //saiful # 29-04-2026 # Start
                'assetPlanId',
                'redefineAssetFromProject'
                //saiful # 29-04-2026 # End
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
    function store(Request $request)
    {
        try {
            $userid = Auth::user()->id;
            $protectionWallId = $userid . time() . mt_rand(1000, 9999);
            $data = [
                'protection_wall_cd' => $protectionWallId,
                'rd_system_id' => $request->road_system_id,
                'chainage' => $request->chainage,
                'wall_type_cd' => $request->wall_type_cd,
                'structure_type_cd' => $request->structure_type_cd,
                'bottom_width' => $request->bottom_width,
                'top_width' => $request->top_width,
                'length' => $request->length,
                'height' => $request->height,
                'updated_by' => Auth::user()->id,
                'created_by' => Auth::user()->id,
                'created_at_office_cd' => Auth::user()->office,
                'remarks' => $request->remarks,
                'year_of_construction' => $request->year_of_construction,
                'year_of_renovation' => $request->year_of_renovation,
                // Saiful # 21-04-2026 # Start
                'asset_plan_id' => $request->hdn_asset_plan_id ?? null,
                // Saiful # 21-04-2026 # End
            ];
            //new code start by Pulak 
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('1');

            if ($makerCheckerStatus === 'Y') {
                $protectionWall = AssetProtectionWallDetailsDraft::create($data);
            } else {
                $extraData = [
                    'approved_by' => Auth::user()->id,
                    'approved_at' => now(),
                ];
                $protectionWall = AssetProtectionWallDetail::create(array_merge($data, $extraData));
            }


            if ($protectionWall) {
                //saiful 21-04-2026 -- Start
                if ($request->hdn_asset_plan_id != null)
                    $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                        ->where('id', $request->hdn_asset_plan_id)
                        ->where('no_of_new_asset', '>', 0)
                        ->decrement('no_of_new_asset', 1, [
                            'updated_at' => now(),
                            'remarks' => 'Culvert Created on: ' . now()
                        ]);
                session()->forget('asset_plan_id');
                //saiful 21-04-2026 -- End
                $this->handleDocument($request, $protectionWallId);

                return redirect()->back()
                    ->with('success', 'New protection wall record saved successfully with protection wall ID: ' . $protectionWallId);
            } else {
                //saiful -- 22-04-2026 -- start
                session()->forget('asset_plan_id');
                //saiful -- 22-04-2026 -- End
                return redirect()->back()
                    ->with('failed', 'Failed to save the record due to an error!!');
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

    public function handleDocument($request, $protectionWallId)
    {
        $configPath = config('customconfigpath.PROTECTION_WALL_DOCS_PATH');
        $configImagePath = config('customconfigpath.PROTECTION_WALL_IMAGES_PATH');
        $rootPath = config('filesystems.disks.external.root');

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $randomNumber = mt_rand(100, 999);
                // get image extension
                $extension = $image->getClientOriginalExtension();
                $uniqueFileName = 'protection_wall_' . $protectionWallId . '_' . $randomNumber . '.' . $extension;
                $folderPath = $configImagePath . now()->year;

                // Use the 'external' disk to store the file
                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
                }

                // Store the file using the 'external' disk
                $filePath = $image->storeAs($folderPath, $uniqueFileName, 'external');
                // Combine the root path and folder path to get the complete file path
                $completeFilePath = $rootPath . '/' . $filePath;

                AssetProtectionWallImagesDetail::create([
                    'protection_wall_cd' => $protectionWallId,
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
            $uniqueFileName = $protectionWallId . '_' . $docCatg['doc_catg_cd'] . '_1.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetProtectionWallDocumentDetail::create([
                'protection_wall_cd' => $protectionWallId,
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
            $uniqueFileName = $protectionWallId . '_' . $docCatg['doc_catg_cd'] . '_2.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetProtectionWallDocumentDetail::create([
                'protection_wall_cd' => $protectionWallId,
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
            $uniqueFileName = $protectionWallId . '_' . $docCatg['doc_catg_cd'] . '_3.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetProtectionWallDocumentDetail::create([
                'protection_wall_cd' => $protectionWallId,
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
            $uniqueFileName = $protectionWallId . '_' . $docCatg['doc_catg_cd'] . '_4.pdf';
            $folderPath = $configPath . now()->year;

            // Use the 'external' disk to store the file
            if (!Storage::disk('external')->exists($folderPath)) {
                Storage::disk('external')->makeDirectory($folderPath, 0775, true, true);
            }

            // Store the file using the 'external' disk
            $filePath = $file->storeAs($folderPath, $uniqueFileName, 'external');
            // Combine the root path and folder path to get the complete file path
            $completeFilePath = $rootPath . '/' . $filePath;

            AssetProtectionWallDocumentDetail::create([
                'protection_wall_cd' => $protectionWallId,
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

    //modified by Pulak 03-05-26
    public function getProtectionWall(Request $request)
    {

        $road_system_id = session('system_id');
        $protectionWallDetails = DB::table('asset_protection_wall_details_draft')
            ->select(
                'asset_protection_wall_details_draft.*',
                'asset_master_protection_wall_type.wall_type_descr',
                'asset_master_protection_wall_structure_type.structure_type_descr',
            )
            ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details_draft.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
            ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details_draft.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
            ->where('asset_protection_wall_details_draft.rd_system_id', '=', $road_system_id)
            ->where('sent_for_finalize', '=', 'N')
            ->orderBy('updated_at', 'desc')
            ->get();

        $protectionWallDetail = $protectionWallDetails->first();

        return response()->json($protectionWallDetail);
    }

    //modified by Pulak 03-05-26

    public function edit(Request $request)
    {

        $protection_wall_id = $request->id;
        $road_system_id = session('system_id');
        //Saiful -- 29-04-2026 -- Start
        $assetPlanId = $request->assetPlanId ?? null;
        $redefineAssetFromProject = $request->redefineAssetFromProject ?? false;
        //Saiful -- 29-04-2026 -- Start
        $roadChainage = DB::table('asset_road_chainage_mappings')
            ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
            ->where('rd_system_id', '=', $road_system_id)
            ->get()->first();
        $protectionWallDetails = DB::table('asset_protection_wall_details_draft')
            ->select(
                'asset_protection_wall_details_draft.*',
                'asset_master_protection_wall_type.wall_type_descr',
                'asset_master_protection_wall_structure_type.structure_type_descr',
                //Saiful -- 22-04-2026 -- Start
                'pp.project_cd'
                //Saiful -- 22-04-2026 -- End
            )
            ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details_draft.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
            ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details_draft.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
            ->where('asset_protection_wall_details_draft.rd_system_id', '=', $road_system_id)
            //Saiful -- 22-04-2026 -- Start
            ->leftJoin('prt_project_asset_plan as pp', 'asset_protection_wall_details_draft.asset_plan_id', '=', 'pp.id')
            //Saiful -- 22-04-2026 -- End
            ->where('sent_for_finalize', '=', 'N')
            ->orderBy('updated_at', 'desc')
            ->get();
        $protectionWallTypes = AssetMasterProtectionWallType::all();
        $superStructureTypes = AssetMasterProtectionWallStructureType::all();
        Log::info(DB::getQueryLog());
        return view('road.protection_wall.editProtectionWall', compact(
            'road_system_id',
            'roadChainage',
            'protectionWallDetails',
            'protectionWallTypes',
            'superStructureTypes',
            'protection_wall_id',
            //Saiful -- 29-04-2026 -- Start
            'assetPlanId',
            'redefineAssetFromProject'
            //Saiful -- 29-04-2026 -- End
        ));
    }

    //modified by Pulak 03-05-26
    public function update(Request $request, $id)
    {
        //  dd($request->all());
        try {
            $userid = Auth::user()->id;

            $data = [
                'rd_system_id' => $request->road_system_id,
                'chainage' => $request->chainage,
                'wall_type_cd' => $request->wall_type_cd,
                'structure_type_cd' => $request->structure_type_cd,
                'bottom_width' => $request->bottom_width,
                'top_width' => $request->top_width,
                'length' => $request->length,
                'height' => $request->height,
                'updated_by' => $userid,
                'remarks' => $request->remarks,
                'year_of_construction' => $request->year_of_construction,
                'year_of_renovation' => $request->year_of_renovation,
                'updated_at' => now(),
                //saiful -- 29-04-2026 -- start
                'asset_plan_id' => $request->hdn_asset_plan_id ?? null,
                //saiful -- 29-04-2026 -- end
            ];


            // Update Draft Table
            $protectionWall = AssetProtectionWallDetailsDraft::where('protection_wall_cd', $id)
                ->update($data);


            if ($protectionWall) {
                //saiful -- 29-04-2026 -- start
                if ($request->hdn_redefine_asset_from_project == true)
                    $updateAssetPlanStatus = DB::table('prt_project_asset_plan')
                        ->where('id', $request->hdn_asset_plan_id)
                        ->update(['status' => 1]);
                //saiful -- 29-04-2026 -- end
                // Handle documents again (update / replace logic inside function)
                $this->handleDocument($request, $id);

                return redirect()->route('road.store.protection')->with('success', 'Protection wall record updated successfully!');
            } else {
                return redirect()->route('road.store.protection')->with('failed', 'No changes made or record not found!');
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

            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->view('errors.generic', [], 500);
        }
    }

    //modified by Pulak 03-05-26

}
