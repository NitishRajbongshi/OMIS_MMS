<?php

namespace App\Http\Controllers\Api\V1\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\Road\PCI\AssetRoadPavementConditionIndexesDraft;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InspectionPciController extends Controller
{
    public function getPciDetails(string $pci_section_cd)
    {
        try {
            if ($pci_section_cd <= 0) {
                return response()->json([
                    'success' => false,
                    'status_code' => 422,
                    'message' => 'Invalid PCI section code.',
                    'error_code' => 'INVALID_PCI_SECTION_CD',
                    'data' => null,
                ], 422);
            }

            $inspectionExists = AssetRoadPavementConditionIndexesDraft::where('pci_section_cd', $pci_section_cd)
                ->exists();

            if (!$inspectionExists) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'PCI section not found.',
                    'error_code' => 'PCI_SECTION_NOT_FOUND',
                    'data' => null,
                ], 404);
            }

            $pciDetails = AssetRoadPavementConditionIndexesDraft::where('pci_section_cd', $pci_section_cd)
                ->orderBy('pci_section_cd')
                ->get();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => $pciDetails->isEmpty()
                    ? 'No PCI details found for this inspection.'
                    : 'PCI details retrieved successfully.',
                'data' => $pciDetails,
                'count' => $pciDetails->count(),
            ], 200);
        } catch (QueryException $e) {
            Log::error('Database error while retrieving inspection PCI details.', [
                'pci_section_cd' => $pci_section_cd,
                'sql_state' => $e->getCode(),
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'A database error occurred while retrieving PCI details.',
                'error_code' => 'DB_ERROR',
                'data' => null,
            ], 500);
        } catch (Throwable $e) {
            Log::error('Unexpected error while retrieving inspection PCI details.', [
                'pci_section_cd' => $pci_section_cd,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'An unexpected error occurred while retrieving PCI details.',
                'error_code' => 'SERVER_ERROR',
                'data' => null,
            ], 500);
        }
    }
}
