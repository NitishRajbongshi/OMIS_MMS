<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        try {
            return view('test');
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function getLocalFile()
    {
        ini_set('memory_limit', '512M');
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '50M');
        ini_set('max_input_time', 300);
        ini_set('max_execution_time', 300);
        $filePath = 'C:\\Live Project\\asset-management-development\\app\Http\\Controllers\\File\\nagaland_roads.json';
        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
            $data = json_decode($fileContent, true);
            if ($data !== null) {
                return response()->json($data);
            } else {
                return response()->json(['error' => 'Invalid JSON format'], 500);
            }
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
    public function download($path)
    {
        if (!Auth::check()) {
            abort(403);
        }
        Log::info("Requested file path: " . $path);
        // base folder from config (NO hardcode)
        $basePath = rtrim(str_replace('\\', '/', config('filesystems.disks.external.root')), '/');
        if (!$basePath) {
            abort(500, 'Storage root not configured');
        }
        // full file path
        $filePath = realpath($basePath . '/' . $path);
        $baseReal = realpath($basePath);

        // security check (prevent directory traversal)
        if (!$filePath || !$baseReal || !str_starts_with($filePath, $baseReal)) {
            abort(403, 'Unauthorized access');
        }

        if (!File::exists($filePath)) {
            abort(404, 'File not found');
        }
        return Response::file($filePath);
    }
}
