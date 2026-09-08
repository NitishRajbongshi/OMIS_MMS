<?php

namespace App\Http\Controllers\Road\LoadDraftData;

use App\Http\Controllers\Controller;
use App\Models\Road\Bridge\AssetRoadBridgeDocumentDetail;
use App\Models\Road\Bridge\AssetRoadBridgeImagesDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkDocumentDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkImageDetail;
use App\Models\Road\Protection_wall\AssetProtectionWallDocumentDetail;
use App\Models\Road\Protection_wall\AssetProtectionWallImagesDetail;																	  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DraftRoadController extends Controller
{
    //new code start by pulak 02-05-26
    public function loadBridgeFiles(Request $request, $brdigeId)
    {
        // $request->validate([
        //     'bridge_cd' => 'required'
        // ]);

        $bridge_cd = $brdigeId;
        $basePath = str_replace('\\', '/', config('filesystems.disks.external.root'));

        // ================= IMAGE =================
        $images = DB::table('public.asset_road_bridge_images_details')
            ->where('rd_bridge_cd', $bridge_cd)
            ->get()
            ->map(function ($img) use ($basePath) {

                $fullPath = $img->image_path ? str_replace('\\', '/', $img->image_path) : '';

                if (str_starts_with($fullPath, $basePath)) {
                    $relativePath = ltrim(substr($fullPath, strlen($basePath)), '/');
                } else {
                    $relativePath = $fullPath;
                }

                return [
                    'id' => $img->id,
                    'image_url' => asset('uploaded_docs/' . $relativePath),
                ];
            });

        // ================= DOCUMENT =================
        $documents = DB::table('public.asset_road_bridge_document_details as d')
            ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
            ->where('d.rd_bridge_cd', $bridge_cd)
            ->select('d.id', 'd.doc_catg', 'd.file_path', 'c.doc_catg_descr')
            ->get()
            ->map(function ($doc) use ($basePath) {

                $fullPath = $doc->file_path ? str_replace('\\', '/', $doc->file_path) : '';

                if (str_starts_with($fullPath, $basePath)) {
                    $relativePath = ltrim(substr($fullPath, strlen($basePath)), '/');
                } else {
                    $relativePath = $fullPath;
                }

                return [
                    'id' => $doc->id,
                    'doc_catg' => $doc->doc_catg,
                    'label' => $doc->doc_catg_descr ?? $doc->doc_catg,
                    'file_url' => asset('uploaded_docs/' . $relativePath),
                ];
            });

        return response()->json([
            'images' => $images,
            'documents' => $documents
        ]);
    }

    public function loadCulvertFiles(Request $request, $culvertId)
    {
        // $request->validate([
        //     'bridge_cd' => 'required'
        // ]);

        $culvert_cd = $culvertId;
        $basePath = str_replace('\\', '/', config('filesystems.disks.external.root'));

        // ================= IMAGE =================
        $images = DB::table('public.asset_road_cdwork_image_details')
            ->where('rd_cdwork_cd', $culvert_cd)
            ->get()
            ->map(function ($img) use ($basePath) {

                $fullPath = $img->image_path ? str_replace('\\', '/', $img->image_path) : '';

                if (str_starts_with($fullPath, $basePath)) {
                    $relativePath = ltrim(substr($fullPath, strlen($basePath)), '/');
                } else {
                    $relativePath = $fullPath;
                }

                return [
                    'id' => $img->id,
                    'image_url' => asset('uploaded_docs/' . $relativePath),
                ];
            });

        // ================= DOCUMENT =================
        $documents = DB::table('public.asset_road_cdwork_document_details as d')
            ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
            ->where('d.rd_cdwork_cd', $culvert_cd)
            ->select('d.id', 'd.doc_catg', 'd.file_path', 'c.doc_catg_descr')
            ->get()
            ->map(function ($doc) use ($basePath) {

                $fullPath = $doc->file_path ? str_replace('\\', '/', $doc->file_path) : '';

                if (str_starts_with($fullPath, $basePath)) {
                    $relativePath = ltrim(substr($fullPath, strlen($basePath)), '/');
                } else {
                    $relativePath = $fullPath;
                }

                return [
                    'id' => $doc->id,
                    'doc_catg' => $doc->doc_catg,
                    'label' => $doc->doc_catg_descr ?? $doc->doc_catg,
                    'file_url' => asset('uploaded_docs/' . $relativePath),
                ];
            });

        return response()->json([
            'images' => $images,
            'documents' => $documents
        ]);
    }

    public function deleteBridgeFile(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:image,document'
        ]);

        try {
            $fileId = $request->id;
            $fileType = $request->type;

            // =========================
            // IMAGE DELETE
            // =========================
            if ($fileType === 'image') {

                $file = AssetRoadBridgeImagesDetail::find($fileId);

                if (!$file) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image not found'
                    ]);
                }

                $file->delete();
            }

            // =========================
            // DOCUMENT DELETE
            // =========================
            elseif ($fileType === 'document') {

                $file = AssetRoadBridgeDocumentDetail::find($fileId);

                if (!$file) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Document not found'
                    ]);
                }
                $file->delete();
            }

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteCulvertFile(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:image,document'
        ]);

        try {
            $fileId = $request->id;
            $fileType = $request->type;

            // =========================
            // IMAGE DELETE
            // =========================
            if ($fileType === 'image') {

                $file = AssetRoadCdworkImageDetail::find($fileId);

                if (!$file) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image not found'
                    ]);
                }

                $file->delete();
            }

            // =========================
            // DOCUMENT DELETE
            // =========================
            elseif ($fileType === 'document') {

                $file = AssetRoadCdworkDocumentDetail::find($fileId);

                if (!$file) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Document not found'
                    ]);
                }
                $file->delete();
            }

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    //end code by pulak 02-05-26

public function loadProtectionWallFiles(Request $request,$id){
        $protectionWallid = $id;
        $basePath = str_replace('\\', '/', config('filesystems.disks.external.root'));

        // ================= IMAGE =================
        $images = AssetProtectionWallImagesDetail::where('protection_wall_cd', $protectionWallid)
        ->get()
        ->map(function ($img) use ($basePath) {

            $fullPath = $img->image_path ? str_replace('\\', '/', $img->image_path) : '';

            if (str_starts_with($fullPath, $basePath)) {
                $relativePath = ltrim(substr($fullPath, strlen($basePath)), '/');
            } else {
                $relativePath = $fullPath;
            }

            return [
                'id' => $img->id,
                'image_url' => asset('uploaded_docs/' . $relativePath),
            ];
        });

    // ================= DOCUMENT =================
    $documents = DB::table('public.asset_protection_wall_document_details as d')
        ->leftJoin('asset_master_document_category as c', 'd.doc_catg', '=', 'c.doc_catg_cd')
        ->where('d.protection_wall_cd', $protectionWallid)
        ->select('d.id', 'd.doc_catg', 'd.file_path', 'c.doc_catg_descr')
        ->get()
        ->map(function ($doc) use ($basePath) {

            $fullPath = $doc->file_path ? str_replace('\\', '/', $doc->file_path) : '';

            if (str_starts_with($fullPath, $basePath)) {
                $relativePath = ltrim(substr($fullPath, strlen($basePath)), '/');
            } else {
                $relativePath = $fullPath;
            }

            return [
                'id' => $doc->id,
                'doc_catg' => $doc->doc_catg,
                'label' => $doc->doc_catg_descr ?? $doc->doc_catg,
                'file_url' => asset('uploaded_docs/' . $relativePath),
            ];
        });

    return response()->json([
        'images' => $images,
        'documents' => $documents
    ]);  
        
    }

    public function deleteProtectionWallFile(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required|in:image,document'
        ]);

    try {
        $fileId = $request->id;
        $fileType = $request->type;

        // =========================
        // IMAGE DELETE
        // =========================
        if ($fileType === 'image') {

            $file = AssetProtectionWallImagesDetail::find($fileId);

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found'
                ]);
            }

            $file->delete();
        }

        // =========================
        // DOCUMENT DELETE
        // =========================
        elseif ($fileType === 'document') {

            $file = AssetProtectionWallDocumentDetail::find($fileId);

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ]);
            }
            $file->delete();
        }

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    }
}
