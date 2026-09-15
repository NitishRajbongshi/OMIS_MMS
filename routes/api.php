<?php

use App\Http\Controllers\Api\V1\Maintenance\InspectionController;
use App\Http\Controllers\Api\v1\maintenance\InspectionObservationController;
use App\Http\Controllers\Api\V1\Maintenance\MasterDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Maintenance Inspection Detail Routes
Route::prefix('maintenance/v1')->group(function () {
    Route::get('/inspection-details-all', [InspectionController::class, 'index']);
    Route::post('/inspection-details', [InspectionController::class, 'store']);
    Route::get('/inspection-details-all/{id}', [InspectionController::class, 'show']);

    Route::get('/master-data', [MasterDataController::class, 'index']);

    Route::get('/inspection-observations/{insp_id}', [InspectionObservationController::class, 'getObservations']);
});
