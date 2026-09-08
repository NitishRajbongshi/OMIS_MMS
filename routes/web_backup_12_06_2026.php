<?php
use App\Http\Controllers\PMS\AssetPlanCntroller;
use App\Http\Controllers\Master\Projects\FundingAgnecies;
use App\Http\Controllers\Master\Projects\SchemeController;
use App\Http\Controllers\Master\Projects\SchemeFundMappingController;
use App\Http\Controllers\PMS\DraftController;
use App\Http\Controllers\PMS\FinalizeProjectController;
use App\Http\Controllers\PMS\PmsController;
use App\Http\Controllers\PMS\Progress\ProjectProgressController;
use App\Http\Controllers\Road\LoadDraftData\DraftRoadController;
use App\Http\Controllers\PMS\Redefine\RedefineAssetsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MIS\MisController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Road\RoadController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\Road\RoadPCIController;
use App\Http\Controllers\Office\OfficeController;
use App\Http\Controllers\MIS\MISDistressController;
use App\Http\Controllers\Road\RoadBridgeController;
use App\Http\Controllers\Road\RoadCDWorkController;
use App\Http\Controllers\MIS\Road\RoadMISController;
use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\Building\BuildingController;
use App\Http\Controllers\Chainage\ChainageController;
use App\Http\Controllers\Road\RoadPavementController;
use App\Http\Controllers\Road\RoadHabitationController;
use App\Http\Controllers\Road\RoadSurfaceTypeController;
use App\Http\Controllers\Road\ShowRoadDetailsController;
use App\Http\Controllers\ViewTenderAndNoticesController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Mechanical\MechanicalController;
use App\Http\Controllers\Road\ModifyRoadAssetsController;
use App\Http\Controllers\Designation\DesignationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RoadChainage\RoadChainageController;
use App\Http\Controllers\Admin\ChangeStatusOfAssetsController;
use App\Http\Controllers\Admin\MakerCheckerHandleController;
use App\Http\Controllers\finalize_data\FinalizedDataController;
use App\Http\Controllers\DataFinalization\FinalizationController;
use App\Http\Controllers\HQRoadChainage\HqRoadChainageController;
use App\Http\Controllers\Dashboard\AssetRoadDistressDetailsController;
use App\Http\Controllers\Admin\UnlockDataFieldController;
use App\Http\Controllers\AssetImage\AssetImageController;
use App\Http\Controllers\Building\BuildingLocationController;
use App\Http\Controllers\Building\BuildingOccupancyController;
use App\Http\Controllers\Building\BuildingUnitController;
use App\Http\Controllers\BUilding\BuildingViewController;
use App\Http\Controllers\Contractor\ContractorController;
use App\Http\Controllers\FinalizeHousing\FinalizeHousingController;
use App\Http\Controllers\FinalizeMechanical\FinalizeMechanicalController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizeBridgeController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizeCDWorksController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizeHabitationController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizePCIController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizeProtectionWallController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizeRoadsController;
use App\Http\Controllers\FinalizeRoadsAndBridges\FinalizeSurfaceTypeController;
use App\Http\Controllers\Housing\HousingController;												   
use App\Http\Controllers\Master\Administrative\BlockController;
use App\Http\Controllers\Master\Administrative\CircleController;
use App\Http\Controllers\Master\Administrative\DistrictController;
use App\Http\Controllers\Master\Administrative\DivisionController;
use App\Http\Controllers\Master\Administrative\SubDivisionController;
use App\Http\Controllers\Master\Administrative\VillageController;
use App\Http\Controllers\Master\Administrative\ZoneController;
use App\Http\Controllers\Master\Bridge\AbutmentTypeController;
use App\Http\Controllers\Master\Bridge\BearingTypeController;
use App\Http\Controllers\Master\Bridge\BridgeTypeController;
use App\Http\Controllers\Master\Bridge\DeckTypeController;
use App\Http\Controllers\Master\Bridge\ExpansionJointController;
use App\Http\Controllers\Master\Bridge\HeadWallController;
use App\Http\Controllers\Master\Bridge\PierTypeController;
use App\Http\Controllers\Master\Bridge\PileTypeController;
use App\Http\Controllers\Master\Bridge\StreamTypeController;
use App\Http\Controllers\Master\Building\AccessibilityController;
use App\Http\Controllers\Master\Building\BuildingAccessController;
use App\Http\Controllers\Master\Building\BuildingBeamController;
use App\Http\Controllers\Master\Building\BuildingCategoryController;
use App\Http\Controllers\Master\Building\BuildingClassController;
use App\Http\Controllers\Master\Building\BuildingColumnController;
use App\Http\Controllers\Master\Building\BuildingConditionController;
use App\Http\Controllers\Master\Building\BuildingFloorController;
use App\Http\Controllers\Master\Building\BuildingFoundationController;
use App\Http\Controllers\Master\Building\BuildingTypeController;
use App\Http\Controllers\Master\Building\BuildingWallTypeController;
use App\Http\Controllers\Master\Construction\CDWorkTypeController;
use App\Http\Controllers\Master\Construction\ConstructionTypeController;
use App\Http\Controllers\Master\Construction\FaceWallTypeController;
use App\Http\Controllers\Master\Construction\FoundationTypeController;
use App\Http\Controllers\Master\Construction\MaterialTypeController;
use App\Http\Controllers\Master\Construction\RetainWallTypeController;
use App\Http\Controllers\Master\Construction\ToeWallTypeController;
use App\Http\Controllers\Master\Construction\WingWallTypeController;
use App\Http\Controllers\Master\Drainage\DrainageSideController;
use App\Http\Controllers\Master\Drainage\DrainageTypeController;
use App\Http\Controllers\Master\Drainage\LineDrainageType;
use App\Http\Controllers\Master\Mechanical\EquipmentConditionController;
use App\Http\Controllers\Master\Mechanical\EquipmentTypeController;
use App\Http\Controllers\Master\Mechanical\FuelTypeController;
use App\Http\Controllers\Master\Mechanical\VehicleConditionController;
use App\Http\Controllers\Master\Mechanical\VehicleMakerController;
use App\Http\Controllers\Master\Mechanical\VehicleModelController;
use App\Http\Controllers\Master\Mechanical\VehicleTypeController;
use App\Http\Controllers\Master\Pavement\PavementConditionController;
use App\Http\Controllers\Master\Pavement\PavementTypeController;
use App\Http\Controllers\Master\Pavement\SurfaceTypeController;
use App\Http\Controllers\Master\Pavement\TopographyTypeController;
use App\Http\Controllers\Master\Projects\BOQItemController;
use App\Http\Controllers\Master\Projects\ContractorCategoryController;
use App\Http\Controllers\Master\Projects\ContractorDetailsController;
use App\Http\Controllers\Master\Projects\ItemOfWorkMasterController;
use App\Http\Controllers\Master\Projects\ItemUnitController;
use App\Http\Controllers\Master\Projects\OfficeDetailsController;
use App\Http\Controllers\Master\Projects\ProjectTypeController;
use App\Http\Controllers\Master\Projects\SubItemOfWorkController;
use App\Http\Controllers\Master\Projects\WorkplanActivityController;
use App\Http\Controllers\Master\Road\ChainageStepController;
use App\Http\Controllers\Master\Road\HabitationFacilitiesController;
use App\Http\Controllers\Master\Road\HabitationSubFacilityController;
use App\Http\Controllers\Master\Road\RoadCategoryController;
use App\Http\Controllers\Master\Road\RoadConditionController;
use App\Http\Controllers\Master\Road\RoadOwnerController;
use App\Http\Controllers\Master\Road\RoadSubAssetController;
use App\Http\Controllers\Mechanical\FinalizEquipmentController;
use App\Http\Controllers\Mechanical\FinalizVehicleController;
use App\Http\Controllers\Mechanical\MechanicalCitizenDashboard;
use App\Http\Controllers\MIS\Housing\HousingMISController;
use App\Http\Controllers\MIS\Mechanical\MechanicalMISController;
use App\Http\Controllers\MIS\NationalHighway\NationalHighwayMISController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\MIS\OrganisationalStructureController;
use App\Http\Controllers\PMS\ProjectManagementController;
use App\Http\Controllers\PMS\ProjectManagementFinalizeController;
use App\Http\Controllers\PMS\ProjectManagementmodifyController;
use App\Http\Controllers\PMS\Progress\ProjectFinancialProgressController;
use App\Http\Controllers\PMS\Progress\ProjectPhysicalProgressController;
use App\Http\Controllers\PMS\Progress\VerifyProgressController;
use App\Http\Controllers\PMS\WorkItem\WorkItemController;
use App\Http\Controllers\Road\Abstract\CulvertAbstractController;
use App\Http\Controllers\Road\ProtectionWallController;
use App\Http\Controllers\ViewWings\ViewHousingController;
use App\Http\Controllers\ViewWings\ViewMechanicalController;
use App\Http\Controllers\ViewWings\ViewNationalHighwayController;
use App\Http\Controllers\ViewWings\ViewRoadAndBridgeController;

// Authentication
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/captcha/image', [AuthenticatedSessionController::class, 'generateCaptcha'])->name('captcha.image');
Route::post('logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
Route::get('/session-expired', function () {
    return view('sessionExpired');
})->name('session-expired');


// Route for Road Department
Route::group(['middleware' => ['auth', 'prevent.cache', 'road']], function () {
    Route::get('/manage-road', [RoadController::class, 'index'])->name('manageRoad');
});

// Route for PMS Department - Nitish 29-Jan-26
Route::prefix('project-management')->middleware(['auth', 'prevent.cache'])->group(function () {

    Route::get('/manage-project', [ProjectManagementController::class, 'create'])->name('manage-project');
    Route::post('/manage-project', [ProjectManagementController::class, 'store']);
    Route::post('/manage-project/update', [ProjectManagementController::class, 'update'])->name('project.update');
    // view single project details
    Route::post('/project-details', [ProjectManagementController::class, 'view'])->name('view.project');
    #Route::post('/manage-project/update', [ProjectManagementController::class, 'update'])->name('project.update');

    Route::get('/modify-project/{id}', [ProjectManagementController::class, 'index']);
    // Route::put('/modify-project', [ProjectManagementController::class, 'update'])->name('project.update');

    Route::get('/showFundingAgencies', [ProjectManagementController::class, 'showFundingAgencies'])->name('showFundingAgencies');
    // dipshikha-start
    Route::get('/other-details/new-works/{projectId}', [ProjectManagementController::class, 'getOtherDetailsForNewWorks']); //nitish																					
    Route::get('/get-upgradation-detail/{projectId}', [ProjectManagementController::class, 'getUpgradationDetail']);
    Route::get('/get-maintenance-detail/{projectId}', [ProjectManagementController::class, 'getMaintenanceDetail']);
    // end					  
    Route::get('/project-list', [ProjectManagementFinalizeController::class, 'index'])->name('project.verified.list');
    Route::get('/finalize-project', [ProjectManagementFinalizeController::class, 'create'])->name('finalize.project');
    Route::post('/freeze-project-details', [ProjectManagementFinalizeController::class, 'acceptProject'])->name('freeze.project.details');
    Route::post('/reject-project-details', [ProjectManagementFinalizeController::class, 'rejectProject'])->name('reject.project.details');
    //By dipshikha 11-05-2026 Start
    //Project Modificat Routes Start
    Route::get('/request-project-modification', [ProjectManagementmodifyController::class, 'index'])->name('project.request.list');
    Route::get('/modification-approved-project', [ProjectManagementmodifyController::class, 'approvedProjects'])->name('modificationapprovedproject.request.list');
    Route::get('/approved-project-edit/{project_cd}/{request_id}', [ProjectManagementmodifyController::class, 'approvedEdit'])->name('approved.project.edit');
    Route::get('/project-modification-history/{project_cd}', [ProjectManagementmodifyController::class, 'history'])->name('project.modification.history');
    Route::post('/request-modification', [ProjectManagementmodifyController::class, 'requestModification'])->name('request.modification');
    Route::post('/reject-modification', [ProjectManagementmodifyController::class, 'rejectModification'])->name('reject.modification');
    Route::post('/approve-modification', [ProjectManagementmodifyController::class, 'approveModification'])->name('approve.modification');
    Route::get('/pending-project-approval', [ProjectManagementmodifyController::class, 'pendingProjects'])->name('pendingproject.request.list');
    Route::post('/modification-project/update', [ProjectManagementmodifyController::class, 'update'])->name('modificationproject.update');
    //Project Modificat Routes End
    //By dipshikha 11-05-2026 Start
    // master data entry
    Route::post('/contractor/store', [ContractorController::class, 'store'])->name('contractor.store');
    //saiful -- 03-2026 -- Start
    Route::get('/pms-financial-progress', [ProjectFinancialProgressController::class, 'index'])->name('pms.progress.financial');
    Route::get('/pms-financial-progress/{project_cd}', [ProjectFinancialProgressController::class, 'create'])->name('pms.progress.create.financial');
    Route::post('/pms-store-financial-progress', [ProjectFinancialProgressController::class, 'store'])->name('pms.progress.store.financial');
    //saiful -- 03-2026 -- End
    // Nitish - change to resource routes
    // item of work entry
    Route::resource('projects.item-of-work', WorkItemController::class)
        ->names('pms.work-item')
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
    // END
    //Project Progress Report
    Route::get('/pms-physical-progress', [ProjectPhysicalProgressController::class, 'index'])->name('project.progress.report');
    Route::get('/pms-item-wise-progress/{project_cd}', [ProjectPhysicalProgressController::class, 'getProgressDetailsItemWise'])->name('project.progress.itemwise');
    //saiful 26-05-2026 -- Start
    Route::get('/get-financial-progress/{project_cd}', [ProjectFinancialProgressController::class, 'getFinancialProgressDetailsProjectWise'])->name('pms.progress.get.financial');
    //saiful 26-05-2026 -- End
    //Verify Project Progree
    Route::get('/pms-verify-progress', [VerifyProgressController::class, 'index'])->name('progress.verify.index');
    Route::get('/pms-view-progress-submitted/{project_cd}/{progress_cd}', [VerifyProgressController::class, 'getProgressSubmitted'])->name('project.view.progress.submitted');
    Route::post('/approve-progress', [VerifyProgressController::class, 'approveProgress']);

    Route::get('/get-previous-progress/{project_cd}', [VerifyProgressController::class, 'getPreviousSubmittedProgress'])->name('project.previous.pregress');
    //saiful -- 23-04-2026 -- Start
    Route::get('/redefine_asset_project_upgradation', [RedefineAssetsController::class, 'redefineAsset'])->name('project.upgrade.asset.redefine');
    //saiful -- 23-04-2026 -- End
    //get Images url
    Route::get('/view-file/{token}', [VerifyProgressController::class, 'viewExternalFile'])->where('path', '.*');
});

//Saiful -- Start -- Create Assets From Project
Route::prefix('asset-management')->middleware('prevent.cache')->group(function () {
    Route::get('/listAssetPlan', [AssetPlanCntroller::class, 'index'])->name('project.list.assets');
});
//Saiful -- End -- Create Assets From Project

Route::group(['middleware' => ['auth', 'prevent.cache']], function () {

    Route::get('uploadTender', [UploadFileController::class, 'uploadTenderViewPage'])->name('uploadTender');
    Route::post('saveTenderDetails', [UploadFileController::class, 'saveTenderDetails'])->name('saveTenderDetails');
    Route::get('uploadNotification', [UploadFileController::class, 'uploadNotificationsViewPage'])->name('uploadNotification');
    Route::post('saveNotificationDetails', [UploadFileController::class, 'saveNotificationDetails'])->name('saveNotificationDetails');
    //View Tender and Notices for Citizens
    Route::get('viewTenderAndNotices', [ViewTenderAndNoticesController::class, 'viewTenderAndNoticesByCitizen'])->name('viewTenderAndNotices');
    Route::get('download_url/{id}', [ViewTenderAndNoticesController::class, 'download_file'])->name('download_url');
    Route::get('download_notification/{id}', [ViewTenderAndNoticesController::class, 'download_notification_file'])->name('download_notification');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::POST('/home', [HomeController::class, 'switchOffice'])->name('office.switch');
    // New route defined by pulak 07-05-2026
    Route::get('/projects', [ProjectProgressController::class, 'index'])->name('pms.project.list');
    Route::get('/projects/progress/{projectCode}', [ProjectProgressController::class, 'show'])->name('pms.progress');
    Route::post('/pms/progress/submit', [ProjectProgressController::class, 'submit'])->name('pms.progress.submit');
    Route::get('progress/history', [ProjectProgressController::class, 'history'])->name('pms.progress.history');
    // End New route defined by pulak 07-05-2026

    // Work Item-PMS : Nitish 25-Jan-26
    Route::get('/get-items-detail/{id}', [WorkItemController::class, 'getItemsDetail']);
    Route::get('/get-sub-items/{id}/{project_cd}', [WorkItemController::class, 'getSubItems']);
    // END

    // Finalization : Nitish 25-Jan-26
    Route::get('/send-pms-details-finalization', [ProjectManagementController::class, 'sendPMSDetailsFinalization']);
    // Route::get('/pms/finalize-project', [FinalizeProjectController::class, 'create'])->name('finalize.project');
    // route by dipshikha
    Route::get('/get-maintenance-roads-subdivision/{sub_division_cd}', [PmsController::class, 'getMaintenanceRoadsbysubdivision']);
    Route::get('/get-maintenance-buildings-subdivision/{catCd}/{sub_division_cd}', [PmsController::class, 'getMaintenanceBuildingsbysubdivision']);
    Route::get('/get-upgradation-buildings-subdivision/{catCd}/{sub_division_cd}', [PmsController::class, 'getUpgrdationBuildingsbysubdivision']);
    Route::get('/get-buildings-category', [PmsController::class, 'getBuildingsCategory']);
    Route::get('/get-vehicles/{subDivId}', [PmsController::class, 'getVehicles']);
    Route::get('/get-maintenance-vehicles/{subDivId}', [PmsController::class, 'getMaintVehicles']);
    Route::get('/get-equipments/{subDivId}', [PmsController::class, 'getEquipments']);
    Route::get('/get-maintenance-equipments/{subDivId}', [PmsController::class, 'getMaintEquipments']);
    Route::get('/get-vehicle-details/{vehicleId}', [PmsController::class, 'getVehicleDetails']);
    Route::get('/get-equipment-details/{equipmentId}', [PmsController::class, 'getEquipmentDetails']);
    Route::get('/get-building-details/{building_cd}', [PmsController::class, 'getBuildingDetails']);

    Route::get('/pms', [PmsController::class, 'create'])->name('pms');
    Route::get('/get-roads/{division_cd}', [PmsController::class, 'getRoads']);
    Route::get('/get-roads-subdivision/{sub_division_cd}', [PmsController::class, 'getRoadsbysubdivision']); // by dipsikha	
    Route::get('/get-maintenance-assets-subdivision/{sub_division_cd}', [PmsController::class, 'getMaintenanceAssetsbysubdivision']); // by dipsikha												   
    Route::get('/get-road-length/{rd_system_id}', [PmsController::class, 'getRoadLength']);
    Route::post('/pms', [PmsController::class, 'store'])->name('project.store');
    Route::get('/get-modal-detail/{id}/{type}', [PmsController::class, 'getModalDetails']); //created on 1 Nov 2025 by Pulak at 11:15
    Route::get('/check-project-name', [PmsController::class, 'checkProjectName'])->name('check.project.name'); //created on 4 Nov 2025 by Pulak at 19:18
    Route::get('/check-road-name', [PmsController::class, 'checkRoadName'])->name('check.road.name'); //created on 4 Nov 2025 by Pulak at 19:18
    Route::get('/load-draft/{id}', [DraftController::class, 'loadDraft']);
    Route::post('/save-draft', [DraftController::class, 'saveDraft']);
    Route::get('/get-draft-list', [DraftController::class, 'listDrafts']);
    Route::delete('/delete-draft/{id}', [DraftController::class, 'deleteDraft']);
    Route::delete('/draft/delete/{project_cd}', [DraftController::class, 'destroy']);
    Route::get('/get-culverts', [PmsController::class, 'getCulverts']);
    Route::get('/get-bridges', [PmsController::class, 'getBridges']);
    Route::get('/get-walls', [PmsController::class, 'getWalls']);
    Route::get('/get-pavements', [PmsController::class, 'getPavements']);
    Route::get('/get-maintenance-culverts', [PmsController::class, 'getMaintenanceCulverts']);
    Route::get('/get-maintenance-bridges', [PmsController::class, 'getMaintenanceBridges']);
    Route::get('/get-maintenance-walls', [PmsController::class, 'getMaintenanceWalls']);
    Route::get('/get-maintenance-pavements', [PmsController::class, 'getMaintenancePavements']);
    Route::get('/verification', [FinalizeProjectController::class, 'verification'])->name('verify.project');
    Route::post('/project/approve', [FinalizeProjectController::class, 'approve'])->name('project.approve');
    Route::post('/project/reject', [FinalizeProjectController::class, 'reject'])->name('project.reject');
    Route::get('/verified-projects', [FinalizeProjectController::class, 'verifiedProjects'])->name('project.verify');
    Route::get('/track-status', [FinalizeProjectController::class, 'trackStatus'])->name('track');
    Route::get('/track-status/search', [FinalizeProjectController::class, 'trackStatusSearch'])->name('track.search');

    // Load Draft directly from DB (for edit)
    Route::get('/project/load-db-draft/{project_cd}/{department}', [DraftController::class, 'loadDbDraft'])->name('project.loadDbDraft');
    Route::get('/get-upgradation-subassets/{project_cd}', [PmsController::class, 'getUpgradationSubAssets']);
    Route::get('/get-maintenance-subassets/{project_cd}', [PmsController::class, 'getMaintenanceSubAssets']);
    // Departmental Dashboard
    Route::get('/dashboard', [DashboardController::class, 'getDashboard'])->name('dashboard');
    Route::get('/dashboardNH', [DashboardController::class, 'getDashboardNH'])->name('dashboardNH');
    Route::get('/dashboard-housing', [DashboardController::class, 'getHousingDashboard'])->name('dashboard.housing');
    Route::get('/dashboard-mechanical', [DashboardController::class, 'getEquipDashboard'])->name('dashboard.equipment');

    // Read Local File
    Route::get('/read-file', [FileController::class, 'index']);
    Route::get('/read-local-file', [FileController::class, 'getLocalFile']);
    //download files
    Route::get('/uploaded_docs/{path}', [FileController::class, 'download'])->where('path', '.*');

    // Download APK
    Route::get('/apk', [HomeController::class, 'downloadAPK'])->name('apkDownload')->middleware('signed');
    // Create Secret Code
    Route::get('/secret-code', [HomeController::class, 'createSecretCode'])->name('secretCode')->middleware('signed');
    Route::post('/store-secret-code', [HomeController::class, 'storeSecretCode']);

    // get distress details
    Route::get('/distress-details/{id}', [AssetRoadDistressDetailsController::class, 'getDistressDetails']);
    Route::get('/distress-images/{id}', [AssetRoadDistressDetailsController::class, 'getDistressImages']);

    //users
    Route::get('/manage-user', [UserController::class, 'index'])->name('manageUser');
    Route::get('/create-user', [UserController::class, 'create'])->name('createUser');
    Route::post('/create-user', [UserController::class, 'store']);
    Route::post('/update-user', [UserController::class, 'update'])->name('updateUser');
    Route::get('/mis-user', [UserController::class, 'mis'])->name('userMIS');
    Route::post('/assignUserRole', [UserController::class, 'assignUserRole'])->name('assignUserRole');

    Route::post('activeUser', [UserController::class, 'activeUser'])->name('activeUser');
    Route::get('user-menu-access', [UserController::class, 'getMenuAccess']);
    Route::post('edit-user-menu-access', [UserController::class, 'editMenuAccess']);
    Route::get('/view-user', [UserController::class, 'view'])->name('viewUsers');
    Route::get('/user-movement', [UserController::class, 'getUserMovement'])->name('userMovement')->middleware('signed');
    Route::get('/user-movement-temporary', [UserController::class, 'getTemporaryUserMovement'])->name('userMovementTemp')->middleware('signed');
    Route::get('/user-history', [UserController::class, 'getUserListByDesignation'])->name('userHistory')->middleware('signed');
    Route::get('/user-period', [UserController::class, 'getUserByTimePeriod'])->name('user.period');

    // Manage Users additional Info
    Route::get('/manage-user-additional-office/{id}', [UserController::class, 'additionalOfficeInfo'])->name('manageAddOffice');
    Route::get('/manage-additional-office', [UserController::class, 'manageAdditionalOffice'])->name('manageUserAddOffice');
    Route::post('/manage-additional-office', [UserController::class, 'storeAdditionalOffice']);
    Route::get('/change-add-office-status', [UserController::class, 'changeAdditionalOfficeStatus']);

    // New Routes defined by Pulak on 04-02-2026
    // Master Dashboard
    // Administrative
    Route::resource('districts', DistrictController::class)->only(['index', 'store', 'update']);
    Route::resource('block', BlockController::class)->only(['index', 'store', 'update']);
    Route::resource('circle', CircleController::class)->only(['index', 'store', 'update']);
    Route::resource('division', DivisionController::class)->only(['index', 'store', 'update']);
    Route::resource('subdivision', SubDivisionController::class)->only(['index', 'store', 'update']);
    Route::resource('village', VillageController::class)->only(['index', 'store', 'update']);
    Route::resource('zone', ZoneController::class)->only(['index', 'store', 'update']);
    Route::get('get-division-hierarchy/{div_cd}', [SubDivisionController::class, 'getDivisionHierarchy'])->name('getDivisionHierarchy');

    // Road
    Route::resource('roadCategory', RoadCategoryController::class)->only(['index', 'store', 'update']);
    Route::resource('chainageStep', ChainageStepController::class)->only(['index', 'store', 'update']);
    Route::resource('habitationFacilities', HabitationFacilitiesController::class)->only(['index', 'store', 'update']);
    Route::resource('habitationSubFacilities', HabitationSubFacilityController::class)->only(['index', 'store', 'update']);
    Route::resource('roadCondition', RoadConditionController::class)->only(['index', 'store', 'update']);
    Route::resource('roadOwner', RoadOwnerController::class)->only(['index', 'store', 'update']);
    Route::resource('roadSubAsset', RoadSubAssetController::class)->only(['index', 'store', 'update']);

    //Project
    Route::resource('contractorCategory', ContractorCategoryController::class)->only(['index', 'store', 'update']);
    Route::resource('contractorDetails', ContractorDetailsController::class)->only(['index', 'store', 'update']);
    Route::resource('itemOfWorkMaster', ItemOfWorkMasterController::class)->only(['index', 'store', 'update']);
    Route::resource('itemUnit', ItemUnitController::class)->only(['index', 'store', 'update']);
    Route::resource('officeDetails', OfficeDetailsController::class)->only(['index', 'store', 'update']);
    Route::resource('projectType', ProjectTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('subItemOfWork', SubItemOfWorkController::class)->only(['index', 'store', 'update']);
    Route::resource('workplanActivity', WorkplanActivityController::class)->only(['index', 'store', 'update']);
    Route::resource('boqItem', BOQItemController::class)->only(['index', 'store', 'update']);
    Route::resource('scheme', SchemeController::class)->only(['index', 'store', 'update']);
    Route::resource('fundingAgnecies', FundingAgnecies::class)->only(['index', 'store', 'update']);
    Route::resource('mapping', SchemeFundMappingController::class)->only(['index', 'store', 'update']);
    // Buildings 
    Route::resource('buildingAccessibility', AccessibilityController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingAccessType', BuildingAccessController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingBeamType', BuildingBeamController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingCategory', BuildingCategoryController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingClass', BuildingClassController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingColumnType', BuildingColumnController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingFloorType', BuildingFloorController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingFoundationType', BuildingFoundationController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingWallType', BuildingWallTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingTypes', BuildingTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('buildingCondition', BuildingConditionController::class)->only(['index', 'store', 'update']);

    // Bridges
    Route::resource('abutmentType', AbutmentTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('bearingType', BearingTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('bridgeType', BridgeTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('deckType', DeckTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('expansionJointType', ExpansionJointController::class)->only(['index', 'store', 'update']);
    Route::resource('headWall', HeadWallController::class)->only(['index', 'store', 'update']);
    Route::resource('pierType', PierTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('pileType', PileTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('streamType', StreamTypeController::class)->only(['index', 'store', 'update']);

    // Drainage

    Route::resource('drainageType', DrainageTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('lineDrainageType', LineDrainageType::class)->only(['index', 'store', 'update']);
    Route::resource('drainageSide', DrainageSideController::class)->only(['index', 'store', 'update']);

    // Mechanical 

    Route::resource('vehicleType', VehicleTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('vehicleMaker', VehicleMakerController::class)->only(['index', 'store', 'update']);
    Route::resource('vehicleCondition', VehicleConditionController::class)->only(['index', 'store', 'update']);
    Route::resource('vehicleModels', VehicleModelController::class)->only(['index', 'store', 'update']);
    Route::resource('equipmentType', EquipmentTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('equipmentCondition', EquipmentConditionController::class)->only(['index', 'store', 'update']);
    Route::resource('fuelType', FuelTypeController::class)->only(['index', 'store', 'update']);

    // Construction 
    Route::resource('cdWorkType', CDWorkTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('constructionType', ConstructionTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('faceWallType', FaceWallTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('foundationType', FoundationTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('materialType', MaterialTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('retainWallType', RetainWallTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('toeWallType', ToeWallTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('wingWallType', WingWallTypeController::class)->only(['index', 'store', 'update']);

    // Pavements 
    Route::resource('pavementCondition', PavementConditionController::class)->only(['index', 'store', 'update']);
    Route::resource('pavementType', PavementTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('surfaceType', SurfaceTypeController::class)->only(['index', 'store', 'update']);
    Route::resource('topographyType', TopographyTypeController::class)->only(['index', 'store', 'update']);
    // Routes end by Pulak 										
    //department
    Route::get('add-department', [DepartmentController::class, 'GetAddDepartment'])->name('GetAddDepartment');
    Route::post('adddepartment', [DepartmentController::class, 'AddDepartment'])->name('AddDepartment');
    Route::get('department-list', [DepartmentController::class, 'departmentList'])->name('departmentList');
    Route::get('datatable-department-list', [DepartmentController::class, 'DatatableDepartmentList'])->name('DatatableDepartmentList');
    Route::post('updateDepartment', [DepartmentController::class, 'updateDepartment'])->name('updateDepartment');
    Route::get("deletedepartment/{id}", [DepartmentController::class, 'deleteDepartment'])->name('deleteDepartment');

    //designation
    Route::get('/manage-designation', [DesignationController::class, 'index'])->name('manageDesignation');
    Route::post('/manage-designation', [DesignationController::class, 'store']);
    Route::post('/update-Designation', [DesignationController::class, 'update'])->name('updateDesignation');
    Route::get("/delete-designation/{id}", [DesignationController::class, 'destroy'])->name('deleteDesignation');
    Route::get('/designation-history', [DesignationController::class, 'getDesignationHistory'])->name('designationUserHistory')->middleware('signed');

    //office
    Route::get('/manage-office', [OfficeController::class, 'index'])->name('manageOffice');
    Route::post('/manage-office', [OfficeController::class, 'store']);
    Route::post('/update-office', [OfficeController::class, 'update'])->name('updateOffice');
    Route::get("/delete-office/{id}", [OfficeController::class, 'destroy'])->name('deleteOffice');
    Route::get('/getOfficeList', [OfficeController::class, 'listOfOffice'])->name('getOffices');
    Route::get('/getZoneList', [OfficeController::class, 'getZoneList'])->name('getZones');
    Route::get('/getCircleList', [OfficeController::class, 'getCircleList'])->name('getCircles');
    Route::get('/getDivisionList', [OfficeController::class, 'getDivisionList'])->name('getDivisions');
    Route::get('/getSubDivisionList', [OfficeController::class, 'getSubDivisionList'])->name('getSubDivisions');
    Route::get('getDist', [OfficeController::class, 'getDist'])->name('getDist');
    Route::get('getSubDist', [OfficeController::class, 'getSubDist'])->name('getSubDist');
    Route::get('getBlock', [OfficeController::class, 'getBlock'])->name('getBlock');
    Route::get('getVillage', [OfficeController::class, 'getVillage'])->name('getVillage');
    Route::get('getOfficeOnchange', [OfficeController::class, 'getOfficeOnchange'])->name('getOfficeOnchange');
    Route::get('/get-parent-offices/{officeTypeCd}', [OfficeController::class, 'getParentOffices']);

    //posts
    Route::get('/manage-post', [PostController::class, 'index'])->name('managePost');
    Route::post('/manage-post', [PostController::class, 'store']);
    Route::post('/update-post', [PostController::class, 'update'])->name('updatePost');
    Route::get("/delete-post/{id}", [PostController::class, 'destroy'])->name('deletePost');

    //role
    Route::get('add-role', [RoleController::class, 'GetAddRole'])->name('GetAddRole');
    Route::post('addrole', [RoleController::class, 'AddRole'])->name('AddRole');
    Route::get('role-list', [RoleController::class, 'roleList'])->name('roleList');
    Route::get('datatable-role-list', [RoleController::class, 'DatatableRoleList'])->name('DatatableRoleList');
    Route::get('add-role/{rname}', [RoleController::class, 'checkRoleName'])->name('checkRoleName');
    Route::post('updateRole', [RoleController::class, 'updateRole'])->name('updateRole');

    //profile
    Route::get('view-profile', [UserController::class, 'getProfile'])->name('getProfile');
    Route::get('edit-profile', [UserController::class, 'EditProfile'])->name('editProfile');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('resetPassword');
    Route::get('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('forgotPassword')->middleware('signed');
    Route::post('/forgot-password', [PasswordController::class, 'storeNewPassword']);

    // Housing
    Route::get('/manage-housing', [BuildingController::class, 'index'])->name('manageHousing');
    Route::get('/add-housing', [BuildingController::class, 'create'])->name('addHousing');
    Route::get('/get-housing/{id}', [BuildingController::class, 'search'])->name('searchHousing');
    Route::post('/add-housing', [BuildingController::class, 'store']);
    Route::delete('/housing/delete', [BuildingController::class, 'destroy'])->name('destroy.housing');
    // New routes for view all non partial finalized housing data: Nitish
    Route::get('manage-housing/view', [BuildingViewController::class, 'index'])->name('building.view.index');
    Route::get('manage-housing/{buildingId}', [BuildingController::class, 'show'])->name('building.view.show');
    // END																	 
    Route::post('/create-additional-housing-data', [BuildingController::class, 'createAdditionalData'])->name('addHousing.additional');
    Route::post('/store-additional-housing-data', [BuildingController::class, 'storeAdditionalData'])->name('storeHousing.additional');

    // Create Location for Housing Module
    Route::resource('building-location', BuildingLocationController::class);
    Route::resource('building.building-unit', BuildingUnitController::class)->names('building.unit');
    Route::resource('building.building-unit.building-occupancy', BuildingOccupancyController::class)
        ->names('building.occupancy');
    Route::get('building/finalize', [BuildingController::class, 'finalizeBuilding'])->name('finalizeBuilding');
    Route::post('update-building', [BuildingController::class, 'updateBuilding'])->name('updateBuilding');
    Route::post('freeze-building', [BuildingController::class, 'freezeBuilding'])->name('freezeBuilding');
    Route::get('/buildingType', [BuildingController::class, 'getBuildingType']);
    Route::get('/buildingLocation', [BuildingController::class, 'getBuildingLocation']);
    Route::get('/abstract', [BuildingController::class, 'getGeneralAbstract'])->name('abstract');
    Route::get('/housing-coordinates', [BuildingController::class, 'getHousingCoordinates'])->name('getHousingCoordinates');
    Route::get('/housing-coordinates/{id}', [BuildingController::class, 'getHousingCoordinatesById']);
    Route::post('/editDraftBuildingData', [BuildingController::class, 'editDraftBuildingData'])->name('editDraftBuildingData');

    //Roads
    Route::get('/add-road', [RoadController::class, 'create'])->name('road.add-road');
    Route::post('/add-road', [RoadController::class, 'store']);
    Route::post('/edit-road', [RoadController::class, 'update'])->name('update.road');
    Route::delete('/road/delete', [RoadController::class, 'destroy'])->name('destroy.road');
    Route::get('/get-coordinates', [RoadController::class, 'calculateLatLngByChainage']);

    Route::get('modify-road', [RoadController::class, 'GetModifyRoad'])->name('GetModifyRoad');
    Route::post('updateRNo', [RoadController::class, 'updateRNo'])->name('updateRNo');
    Route::post('updateRName', [RoadController::class, 'updateRName'])->name('updateRName');
    Route::post('updateRLTotal', [RoadController::class, 'updateRLTotal'])->name('updateRLTotal');
    Route::post('updatechF', [RoadController::class, 'updatechF'])->name('updatechF');
    Route::post('updatechT', [RoadController::class, 'updatechT'])->name('updatechT');
    Route::post('updateRSLocationFrom', [RoadController::class, 'updateRSLocationFrom'])->name('updateRSLocationFrom');
    Route::post('updateRSLocationTo', [RoadController::class, 'updateRSLocationTo'])->name('updateRSLocationTo');
    Route::post('updateRcat', [RoadController::class, 'updateRcat'])->name('updateRcat');
    Route::post('updateDistNo', [RoadController::class, 'updateDistNo'])->name('updateDistNo');
    Route::post('updateDivision', [RoadController::class, 'updateDivision'])->name('updateDivision');
    Route::post('updateRdClass', [RoadController::class, 'updateRdClass'])->name('updateRdClass');
    Route::post('updateDate', [RoadController::class, 'updateDate'])->name('updateDate');
    Route::post('sendModifyReq', [RoadController::class, 'sendModifyReq'])->name('sendModifyReq');
    Route::post('finalApproveByAdmin', [RoadController::class, 'finalApproveByAdmin'])->name('finalApproveByAdmin');

    Route::get('/get-lat-lng/{id}', [RoadController::class, 'getRoadLatLng'])->middleware(['auth']);
    Route::get('/abstract-road-and-bridge', [RoadController::class, 'getRoadAbstract'])->name('abstract.r&b');
    Route::get('/abstract-road-and-bridge-division', [RoadController::class, 'getRoadAbstractByDivision'])->name('abstract.R&BDivison');
    // Route::get('/get-road-by-division/{id}', [RoadController::class, 'getRoadByDivision'])->middleware(['auth']);
    Route::get('/getRoadsAssetsAbstractDetails/{rd_id}', [RoadController::class, 'getRoadsAssetsAbstractDetails'])->middleware(['auth'])->name('getRoadsAssetsAbstractDetails');

    // Handle maker checker module
    Route::get('/handle-maker-checker-workflow', [MakerCheckerHandleController::class, 'index'])->name('handleMakerChecker');
    Route::post('/update-maker-checker-workflow', [MakerCheckerHandleController::class, 'update'])->name('updateMakerChecker');


    // Road Abstract - get sub-assets details
    Route::get('/getCDWorkDetailsOfARoad/{rd_id}', [CulvertAbstractController::class, 'getCulvertDetails'])->name('getCDWorkDetailsOfARoad');


    // chainage
    Route::get('/chainage/{id}', [ChainageController::class, 'index']);
    Route::get('/get-chainage', [ChainageController::class, 'show'])->name('roadChainage');

    // culvert
    // modified by Pulak 30-04-26
    Route::get('/manage-cdWorks/{id}', [RoadCDWorkController::class, 'index'])->name('manageCDWorks');
    Route::get('/edit-cdworks/{id}', [RoadCDWorkController::class, 'edit'])->name('editCDWorks');
    Route::get('/create-cdworks', [RoadCDWorkController::class, 'create'])->name('createCDWorks');
    Route::post('/create-cdworks', [RoadCDWorkController::class, 'store']);
    Route::get('/get-cd-work-details/{id}', [RoadCDWorkController::class, 'getCDWorkDetails']);
    Route::put('/update-cdworks/{id}', [RoadCDWorkController::class, 'update'])->name('updateCDWorks');
    Route::post('/delete-culvert-file', [DraftRoadController::class, 'deleteCulvertFile']);
    Route::get('/load-culvert-files/{culvert_id}', [DraftRoadController::class, 'loadCulvertFiles']);
    // modified by Pulak 30-04-26
    Route::get('/get-wing-wall-detail/{id}', [RoadCDWorkController::class, 'getWingWallDetails'])->name('getWingWallDetails');
    Route::get('/get-wing-wall-detail-finalized/{id}', [RoadCDWorkController::class, 'getFinalizedWingWallDetails']);
    Route::get('/get-head-wall-detail/{id}', [RoadCDWorkController::class, 'getHeadWallDetails'])->name('getHeadWallDetails');
    Route::get('/get-head-wall-detail-finalized/{id}', [RoadCDWorkController::class, 'getFinalizedHeadWallDetails']);
    Route::get('/get-culvert-lat-lng/{id}', [RoadCDWorkController::class, 'getLatLng']);
    Route::get('/get-culvert-details/{id}', [RoadCDWorkController::class, 'getCulvertDetails']);

    // Pavement
    Route::get('/manage-pavement/{id}', [RoadPavementController::class, 'index'])->name('managePavement');
    Route::get('/create-pavement', [RoadPavementController::class, 'create'])->name('createPavement');
    Route::post('/create-pavement', [RoadPavementController::class, 'store']);
    // Pavement Subsection
    Route::get('/manage-pavement-subsection/{id}/{asset}', [RoadPavementController::class, 'pavementSubsection'])->name('pavement.subsection');
    Route::get('/create-pavement-shoulder', [RoadPavementController::class, 'createShoulder'])->name('pavement.create.shoulder');
    Route::post('/create-pavement-shoulder', [RoadPavementController::class, 'storeShoulder']);
    Route::get('/create-pavement-drainage', [RoadPavementController::class, 'createDrainage'])->name('pavement.create.drainage');
    Route::post('/create-pavement-drainage', [RoadPavementController::class, 'storeDrainage']);
    Route::get('/create-pavement-landslide', [RoadPavementController::class, 'createLandSlide'])->name('pavement.create.landslide');
    Route::post('/create-pavement-landslide', [RoadPavementController::class, 'storeLandSlide']);

    // Bridge
    //modified by Pulak 29/04/2026							  
    Route::get('/add-cd-bridge-details/{id}', [RoadBridgeController::class, 'index'])->name('road.cd-bridge-details');
    Route::get('/edit-cd-bridge-details/{id}', [RoadBridgeController::class, 'show']);
    Route::get('/add-bridge-details', [RoadBridgeController::class, 'create'])->name('bridge.store');
    Route::post('/add-bridge-details', [RoadBridgeController::class, 'store']);
    Route::get('/get-bridge-details/{id}', [RoadBridgeController::class, 'edit']);
    Route::get('/load-bridge-files/{brdigeId}', [DraftRoadController::class, 'loadBridgeFiles']); //modified by pulak 02-05-26
    Route::post('/delete-bridge-file', [DraftRoadController::class, 'deleteBridgeFile']); //modified by pulak 02-05-26
    // modification end by Pulak 29/04/2026																							 
    Route::put('/update-bridge-details/{id}', [RoadBridgeController::class, 'update'])->name('bridge.update');
    Route::get('/get-bridge-wing-wall-detail/{id}', [RoadBridgeController::class, 'bridgeWingWallDetails']);
    Route::get('/get-bridge-wing-wall-detail-finalized/{id}', [RoadBridgeController::class, 'finalizedBridgeWingWallDetails']);
    Route::get('/get-bridge-head-wall-detail/{id}', [RoadBridgeController::class, 'bridgeHeadWallDetails']);
    Route::get('/get-bridge-head-wall-detail-finalized/{id}', [RoadBridgeController::class, 'finalizedBridgeHeadWallDetails']);
    Route::get('/get-abutment-wall-detail/{id}', [RoadBridgeController::class, 'bridgeAbutmentWallDetails']);
    Route::get('/get-abutment-wall-detail-finalized/{id}', [RoadBridgeController::class, 'finalizedBridgeAbutmentWallDetails']);
    Route::get('/get-retain-wall-detail/{id}', [RoadBridgeController::class, 'bridgeRetainWallDetails']);
    Route::get('/get-retain-wall-detail-finalized/{id}', [RoadBridgeController::class, 'finalizedBridgeRetainWallDetails']);
    Route::get('/get-span-details/{id}', [RoadBridgeController::class, 'bridgeSpanDetails']);
    Route::get('/get-span-details-finalized/{id}', [RoadBridgeController::class, 'finalizedBridgeSpanDetails']);
    Route::get('/get-pier-details/{id}', [RoadBridgeController::class, 'bridgePierDetails']);
    Route::get('/get-pier-details-finalized/{id}', [RoadBridgeController::class, 'finalizedBridgePierDetails']);
    Route::get('/get-bridge-lat-lng/{id}', [RoadBridgeController::class, 'getLatLng']);

    // surfaceType 
    Route::get('/manage-surface-type/{id}', [RoadSurfaceTypeController::class, 'index'])->middleware(['auth']);
    Route::get('/add-surface-type', [RoadSurfaceTypeController::class, 'create'])->middleware(['auth'])->name('road.add-surface-type');
    Route::post('/store-surface-type', [RoadSurfaceTypeController::class, 'store'])->middleware(['auth'])->name('road.store-surface-type');
    Route::post('/update-surface-type-detail', [RoadSurfaceTypeController::class, 'update'])->middleware(['auth'])->name('updateSurfaceTypeDetails');

    // PCI 
    Route::get('/road/add-pci/{id}', [RoadPCIController::class, 'index'])->middleware(['auth'])->name('road.pci');
    Route::get('/add-pci-details', [RoadPCIController::class, 'addPCIValue'])->middleware(['auth'])->name('road.add-pci');
    Route::post('/store-pci-details', [RoadPCIController::class, 'storePCIValue'])->middleware(['auth'])->name('road.store-pci');
    Route::post('/update-pci-detail', [RoadPCIController::class, 'updatePCIValue'])->middleware(['auth'])->name('updatePCIDetails');

    // habitation
    Route::get('/road/add-habitation/{id}', [RoadHabitationController::class, 'index'])->middleware(['auth'])->name('road.habitation');
    Route::get('/add-habitation', [RoadHabitationController::class, 'addHabitation'])->middleware(['auth'])->name('road.add-habitation');
    Route::post('/add-habitation', [RoadHabitationController::class, 'storeHabitation'])->middleware(['auth']);
    // Saiful -- 03-2026 -- Start
    Route::post('/destroy-habitation', [RoadHabitationController::class, 'destroyHabitation'])->middleware(['auth'])->name('road.destroy-habitation');
    Route::post('/edit-habitation', [RoadHabitationController::class, 'editHabitation'])->middleware(['auth'])->name('road.edit-habitation');
    // Saiful -- 03-2026 -- End
    Route::get('/village/{id}', [RoadHabitationController::class, 'getVillage'])->name('villageList');
    Route::get('/block/{id}', [RoadHabitationController::class, 'getBlock'])->name('blockList');
    Route::get('/habitation-population/{id}', [RoadHabitationController::class, 'getPopulation'])->middleware(['auth']);

    // protection wall
    Route::get('/add-protection-wall/{id}', [ProtectionWallController::class, 'index'])->name('road.protection.wall');
    Route::get('/add-protection-wall', [ProtectionWallController::class, 'create'])->name('road.store.protection');
    Route::post('/add-protection-wall', [ProtectionWallController::class, 'store']);
    Route::get('/get-protection-wall/{id}', [ProtectionWallController::class, 'getProtectionWall']); //modified by pulak 02-05-26
    Route::get('/edit-protection-wall/{id}', [ProtectionWallController::class, 'edit'])->name('editProtectionWall');//modified by pulak 02-05-26
    Route::put('/update-protection-wall/{id}', [ProtectionWallController::class, 'update'])->name('updateProtectionWall');//modified by pulak 02-05-26
    Route::get('/load-protection-wall-files/{id}', [DraftRoadController::class, 'loadProtectionWallFiles']); //modified by pulak 02-05-26
    Route::post('/delete-protection-wall-file', [DraftRoadController::class, 'deleteProtectionWallFile']); //modified by pulak 02-05-26
    // Start - Show Road & its SubAsset Details
    Route::get('/road/show/{id}', [ShowRoadDetailsController::class, 'showRoadDetails'])->name('showRoad');
    Route::get('/show-roads', [ShowRoadDetailsController::class, 'showAllRoadDetails'])->name('showAllRoadModule');
    Route::get('/road/show-cd-works/{id}', [ShowRoadDetailsController::class, 'getCDWorkDetails']);
    Route::get('/show-cdworks-details', [ShowRoadDetailsController::class, 'showCDWorkDetails'])->name('showCDWorkDetails');
    Route::get('/road/show-bridge-data/{id}', [ShowRoadDetailsController::class, 'getBridgeDetails']);
    Route::get('/show-bridge-details', [ShowRoadDetailsController::class, 'showBridgeDetails'])->name('showBridgeDetails');
    Route::get('/road/show-surface-type-data/{id}', [ShowRoadDetailsController::class, 'getSurfaceTypeDetails']);
    Route::get('/show-surface-type-details', [ShowRoadDetailsController::class, 'showSurfaceTypeDetails'])->name('showSurfaceTypeDetails');
    Route::get('/road/show-pavement-data/{id}', [ShowRoadDetailsController::class, 'getPavementDetails']);
    Route::get('/show-pavement-details', [ShowRoadDetailsController::class, 'showPavementDetails'])->name('showPavementDetails');
    Route::get('/road/show-pci-data/{id}', [ShowRoadDetailsController::class, 'getPCIDetails']);
    Route::get('/show-pci-details', [ShowRoadDetailsController::class, 'showPCIDetails'])->name('showPCIDetails');
    Route::get('/road/show-protection-wall-data/{id}', [ShowRoadDetailsController::class, 'getProtectionWallDetails']);
    Route::get('/show-protection-wall-details', [ShowRoadDetailsController::class, 'showProtectionWallDetails'])->name('showProtectionWallDetails');
    Route::get('/road/show-habitation-data/{id}', [ShowRoadDetailsController::class, 'getHabitationDetails']);
    Route::get('/show-habitation-details', [ShowRoadDetailsController::class, 'showHabitationDetails'])->name('showHabitationDetails');
    // End

    // Road Chainage
    Route::get('/chainage', [RoadChainageController::class, 'index'])->middleware(['auth'])->name('chainage');
    Route::post('/chainage', [RoadChainageController::class, 'store'])->middleware(['auth']);
    Route::get('/chainage/get-road/{id}', [RoadChainageController::class, 'show'])->middleware(['auth']);
    Route::get('/chainage/show-road', [RoadChainageController::class, 'showRoad'])->middleware(['auth']);
    Route::get('/chainage/get-chainage/{id}', [RoadChainageController::class, 'getRoadChainageDetails'])->middleware(['auth']);
    Route::get('/chainage/get-nh-chainage/{road_id}/{id}', [RoadChainageController::class, 'getNHChainageDetails'])->middleware(['auth']);
    Route::get('/chainage/get-detail/{id}', [RoadChainageController::class, 'show'])->middleware(['auth']);
    Route::get('/end-chainage/{id}', [RoadChainageController::class, 'getEndChainage'])->middleware(['auth']);
    Route::get('/nh-end-chainage/{id}', [RoadChainageController::class, 'getNHEndChainage'])->middleware(['auth']);

    // HQ level chainage
    Route::get('/assign-chainage', [HqRoadChainageController::class, 'index'])->middleware('auth')->name('HqChainage');
    Route::post('/assign-chainage', [HqRoadChainageController::class, 'store'])->middleware('auth');
    Route::get('/get-road-by-id/{id}', [HqRoadChainageController::class, 'getRoadById']);

    Route::get('/get-circle/{id}', [LevelController::class, 'getCircleByZone'])->middleware(['auth']);
    Route::get('/get-division/{id}', [LevelController::class, 'getdivisionByCircle'])->middleware(['auth']);
    Route::get('/get-sub-division/{id}', [LevelController::class, 'getSubDivisionByDivision'])->middleware(['auth']);

    // Start- Send asset for finalization
    Route::get('/send-road-details-finalization', [FinalizationController::class, 'finalizeRoad']);
    Route::get('/send-culvert-details-finalization', [FinalizationController::class, 'finalizeCDWork']);
    Route::get('/send-bridge-details-finalization', [FinalizationController::class, 'finalizeBridge']);
    Route::get('/send-protection-wall-details-finalization', [FinalizationController::class, 'finalizeProtectionWall']);
    Route::get('/send-pci-details-finalization', [FinalizationController::class, 'finalizePCI']);
    Route::get('/send-surface-type-details-finalization', [FinalizationController::class, 'finalizeSurfaceType']);
    Route::get('/send-habitation-details-finalization', [FinalizationController::class, 'finalizeHabitation']);
    Route::get('/send-building-details-finalization', [FinalizationController::class, 'finalizeBuilding']);
    Route::get('/send-vehicle-details-finalization', [FinalizVehicleController::class, 'finalizeSingleData']);
    Route::get('/send-equipment-details-finalization', [FinalizEquipmentController::class, 'finalizeSingleData']);
    // End

    // Start - Store Data Finalization
    // Road 
    Route::get('/finalize-road-details', [FinalizeRoadsController::class, 'index'])->name('finalize.road');
    Route::post('/finalize-road-details', [FinalizeRoadsController::class, 'store']);
    Route::get('/freeze-road-details/{id}', [FinalizedDataController::class, 'freezeSingleRoadData']);
    Route::get('/reject-road-details/{id}/{reason}', [FinalizedDataController::class, 'rejectSingleRoadData']);
    // CD Works
    Route::get('/finalize-road-asset/{id}', [FinalizeCDWorksController::class, 'load']);
    Route::get('/finalize-cdworks-details', [FinalizeCDWorksController::class, 'index'])->name('finalize.road.cdworks');
    Route::post('/finalize-cdworks-details', [FinalizeCDWorksController::class, 'store']);
    Route::get('/accept-cdworks-details/{id}', [FinalizeCDWorksController::class, 'acceptSingleCDWorksData']);
    Route::get('/reject-cdworks-details/{id}/{reason}', [FinalizeCDWorksController::class, 'rejectSingleCDWorksData']);
    // Bridges
    Route::get('/finalize-bridge-details', [FinalizeBridgeController::class, 'index'])->name('finalize.road.bridges');
    Route::post('/finalize-bridge-details', [FinalizeBridgeController::class, 'store']);
    Route::get('/accept-bridge-details/{id}', [FinalizeBridgeController::class, 'acceptSingleBridgeData']);
    Route::get('/reject-bridge-details/{id}/{reason}', [FinalizeBridgeController::class, 'rejectSingleBridgeData']);
    // PCI
    Route::get('/finalize-pci-details', [FinalizePCIController::class, 'index'])->name('finalize.road.pci');
    Route::post('/finalize-pci-details', [FinalizePCIController::class, 'store']);
    Route::get('/accept-pci-details/{id}', [FinalizePCIController::class, 'acceptSinglePCIData']);
    Route::get('/reject-pci-details/{id}/{reason}', [FinalizePCIController::class, 'rejectSinglePCIData']);
    // Protection Wall
    Route::get('/finalize-protection-wall-details', [FinalizeProtectionWallController::class, 'index'])->name('finalize.road.protectionWall');
    Route::post('/finalize-protection-wall-details', [FinalizeProtectionWallController::class, 'store']);
    Route::get('/accept-protection-wall-details/{id}', [FinalizeProtectionWallController::class, 'acceptSingleProtectionWallData']);
    Route::get('/reject-protection-wall-details/{id}/{reason}', [FinalizeProtectionWallController::class, 'rejectSingleProtectionWallData']);
    // Surface Type
    Route::get('/finalize-surface-type-details', [FinalizeSurfaceTypeController::class, 'index'])->name('finalize.road.surface.types');
    Route::post('/finalize-surface-type-details', [FinalizeSurfaceTypeController::class, 'store']);
    Route::get('/accept-surface-type-details/{id}', [FinalizeSurfaceTypeController::class, 'acceptSingleSurfaceTypeData']);
    Route::get('/reject-surface-type-details/{id}/{reason}', [FinalizeSurfaceTypeController::class, 'rejectSingleSurfaceTypeData']);
    // pavement

    // Habitation
    Route::get('/finalize-habitation-details', [FinalizeHabitationController::class, 'index'])->name('finalize.road.habitation');
    Route::post('/finalize-habitation-details', [FinalizeHabitationController::class, 'store']);
    Route::get('/accept-habitation-details/{id}', [FinalizeHabitationController::class, 'acceptSingleHabitationData']);
    Route::get('/reject-habitation-details/{id}/{reason}', [FinalizeHabitationController::class, 'rejectSingleHabitationData']);
    // Housing
    Route::get('/finalize-housing-draft-data', [FinalizeHousingController::class, 'index'])->name('housing.finalize');
    Route::get('/freeze-building-details/{id}', [FinalizeHousingController::class, 'acceptHousing']);
    Route::get('/reject-building-details/{id}/{reason}', [FinalizeHousingController::class, 'rejectHousing']);
    // End

    // Mechanical
    // equipment
    Route::get('/manage-mechanical', [MechanicalController::class, 'index'])->name('mechanical');
    Route::get('/add-equipment', [MechanicalController::class, 'addEquipment'])->name('addEquipment');
    Route::post('/add-equipment', [MechanicalController::class, 'storeEquipment']);
    Route::post('/freeze-equipment', [MechanicalController::class, 'freezeEquipment'])->name('freezeEquipment');
    Route::post('/edit-equipment', [MechanicalController::class, 'updateEquipment'])->name('editEquipment');
    Route::delete('/delete-equipment', [MechanicalController::class, 'destroyEquipment'])->name('deleteEquipment');
    Route::get('/equipment-abstract', [MechanicalController::class, 'equipmentAbstract'])->name('equipmentAbstract');
    Route::get('/equipment-abstract', [MechanicalController::class, 'equipmentAbstract'])->name('equipmentAbstract');
    // vehicle
    Route::get('/manage-vehicle', [MechanicalController::class, 'viewVehicle'])->name('vehicle');
    Route::get('/add-vehicle', [MechanicalController::class, 'addVehicle'])->name('addVehicle');
    Route::post('/add-vehicle', [MechanicalController::class, 'storeVehicle']);
    Route::post('/edit-vehicle', [MechanicalController::class, 'editVehicle'])->name('editVehicle');
    Route::post('/editDraftVehicleData', [MechanicalController::class, 'editDraftVehicleData'])->name('editDraftVehicleData');
    Route::delete('/delete-vehicle', [MechanicalController::class, 'destroyVehicle'])->name('deleteVehicle');
    Route::post('/freeze-vehicle', [MechanicalController::class, 'freezeVehicle'])->name('freezeVehicle');
    Route::get('/vehicle-abstract', [MechanicalController::class, 'vehicleAbstract'])->name('vehicleAbstract');
    //show mechanical data in citizen dashboard
    Route::get('/get-mechanical-equipment-data', [MechanicalCitizenDashboard::class, 'showEquipment'])->name('citizen.equipment');
    Route::get('/get-mechanical-vehicle-data', [MechanicalCitizenDashboard::class, 'showVehicle'])->name('citizen.vehicle');

    // finalize mechanical data
    Route::get('/finalize-vehicle-draft-data', [FinalizeMechanicalController::class, 'finalizeVehicle'])->name('vehicle.finalize');
    Route::get('/freeze-vehicle-details/{id}', [FinalizeMechanicalController::class, 'acceptVehicle']);
    Route::get('/reject-vehicle-details/{id}/{reason}', [FinalizeMechanicalController::class, 'rejectVehicle']);
    Route::get('/finalize-equipment-draft-data', [FinalizeMechanicalController::class, 'finalizeEquipment'])->name('equipment.finalize');
    Route::get('/freeze-equipment-details/{id}', [FinalizeMechanicalController::class, 'acceptEquipment']);
    Route::get('/reject-equipment-details/{id}/{reason}', [FinalizeMechanicalController::class, 'rejectEquipment']);

    // National Highway
    // Route::get('/national-highway', [NationalHighwayController::class, 'index'])->name('nationalHighway');
    Route::get('/national-highway', [RoadController::class, 'index'])->name('nationalHighway');

    // Route::get('/add-national-highway', [NationalHighwayController::class, 'addHighway'])->name('addNationalHighway');
    // Route::post('/add-national-highway', [NationalHighwayController::class, 'store']);

    //activity
    Route::get('view-activity', [ActivityController::class, 'GetActivity'])->name('GetActivity');
    Route::get('login-log', [ActivityController::class, 'getLoginLog'])->name('getLoginLog');
    Route::get('getUserOnchange', [ActivityController::class, 'getUserOnchange'])->name('getUserOnchange');



    //Modify Finasiled Road Assets
    Route::get('listRoadsToRequestModification', [ModifyRoadAssetsController::class, 'GetAllRoads'])->name('GetAllRoads');
    Route::get('handleDataModifyReqOnARoad/{id}/{sub_asset_name}', [ModifyRoadAssetsController::class, 'handleDataModifyReqOnARoad'])->name('handleDataModifyReqOnARoad');
    Route::get('listFinalisedCDWorksForRoad', [ModifyRoadAssetsController::class, 'listFinalisedCDWorksForRoad'])->name('GetAllCDWorksForRoad');
    Route::post('reqEditRoadAssets', [ModifyRoadAssetsController::class, 'reqEditRoadAssets'])->name('reqEditRoadAssets');
    Route::post('sendModifyReq', [RoadController::class, 'sendModifyReq'])->name('sendModifyReq');
    Route::get('modify-road', [ModifyRoadAssetsController::class, 'GetModifyRoad'])->name('GetModifyRoad'); //
    Route::post('finalApproveByAdmin', [ModifyRoadAssetsController::class, 'finalApproveByAdmin'])->name('finalApproveByAdmin');

    //MIS -- Distress
    Route::get('misDistressDetails', [MISDistressController::class, 'getAllActiveRoadDistresses'])->name('getDistressDetails');
    Route::post('filterRoadDistresses', [MISDistressController::class, 'filterRoadDistresses'])->name('filterRoadDistresses');
    Route::get('misOfficesWithoutOfficer', [UserController::class, 'misOfficesWithoutOfficer'])->name('misOfficesWithoutOfficer');
    //Distressed Data Update
    Route::get('/list-distress-details', [MISDistressController::class, 'listActiveRoadDistressesToUpdate'])->name('list.distress');
    Route::post('/list-distress-details', [MISDistressController::class, 'updateDistress']);


    // /{cd}/{dscr}
    //Chnage Status url
    Route::get('listOfAssetToChangeStatus', [ChangeStatusOfAssetsController::class, 'listOfAssetToChangeStatus'])->name('listOfAssetToChangeStatus');
    Route::post('updateAssetStatus', [ChangeStatusOfAssetsController::class, 'updateAssetStatus'])->name('updateAssetStatus');


    //unlock datafield by admin
    Route::get('unlockPage', [UnlockDataFieldController::class, 'loadInitialUnlockPage'])->name('unlockPage');
    Route::post('/list_roads_n_bridges_to_unlock', [UnlockDataFieldController::class, 'filterRoadAndBridgesToUnlock'])->name('list_roads_n_bridges_to_unlock');
    Route::post('viewAssetToUnlock', [UnlockDataFieldController::class, 'viewAssetToUnlock'])->name('viewAssetToUnlock');
    Route::post('saveunlockdata', [UnlockDataFieldController::class, 'saveunlockdata'])->name('saveunlockdata');

    //Update Unlocked Data By End User
    Route::get('loadUnlockedData', [UnlockDataFieldController::class, 'loadUnlockedDataToUpdate'])->name('loadUnlockedDataToUpdate');
    Route::post('editUnlockedAssetData', [UnlockDataFieldController::class, 'editUnlockedAssetData'])->name('editUnlockedAssetData');
    Route::post('saveUnlockedAssetDetails', [UnlockDataFieldController::class, 'saveUnlockedAssetDetails'])->name('saveUnlockedAssetDetails');

    //MIS-Organisational Structure
    Route::get('/org-structure', [OrganisationalStructureController::class, 'getENCTreeData'])->name('org-structure');
    Route::get('/getHQTreeData', [OrganisationalStructureController::class, 'getHQTreeData'])->name('getHQTreeData');
    Route::get('/getZOtreeData', [OrganisationalStructureController::class, 'getZOtreeData'])->name('getZOtreeData');
    Route::get('/getCOtreeData', [OrganisationalStructureController::class, 'getCOtreeData'])->name('getCOtreeData');
    Route::get('/getDOtreeData', [OrganisationalStructureController::class, 'getDOtreeData'])->name('getDOtreeData');
    Route::get('/getSDOtreeData', [OrganisationalStructureController::class, 'getSDOtreeData'])->name('getSDOtreeData');
    Route::get('/getCEUserDataAndSEOffficeData', [OrganisationalStructureController::class, 'getCEUserDataAndSEOffficeData'])->name('getCEUserDataAndSEOffficeData');

    Route::get('/getAdditionalOfficeChargeDetails', [OrganisationalStructureController::class, 'getAdditionalOfficeChargeDetails'])->name('getAdditionalOfficeChargeDetails');

    Route::post('/moveApprovedRoadToDraftRoad', [FinalizedDataController::class, 'moveApprovedRoadToDraftRoad'])->name('moveApprovedRoadToDraftRoad');
    Route::get('/shortMsg', [UploadFileController::class, 'loadShortMsgPage'])->name('loadShortMsgPage');
    Route::post('/submitShortMsg', [UploadFileController::class, 'submitShortMsg'])->name('submitShortMsg');

    // Start - view different departments and its related details by higher level user
    // Road
    Route::get('/view-roads', [ViewRoadAndBridgeController::class, 'showRoadDetails'])->name('viewRoadWings');
    Route::get('/view-cdworks', [ViewRoadAndBridgeController::class, 'showCdWorkDetails'])->name('viewCDWorks');
    Route::get('/view-bridges', [ViewRoadAndBridgeController::class, 'showBridgeDetails'])->name('viewBridge');
    Route::get('/view-pci', [ViewRoadAndBridgeController::class, 'showPCIDetails'])->name('viewPCI');
    Route::get('/view-protection-wall', [ViewRoadAndBridgeController::class, 'showProtectionWallDetails'])->name('viewProtectionWall');
    Route::get('/view-surface-types', [ViewRoadAndBridgeController::class, 'showSurfaceTypeDetails'])->name('viewSurfaceType');
    Route::get('/view-habitation', [ViewRoadAndBridgeController::class, 'showHabitationDetails'])->name('viewHabitation');
    // NH
    Route::get('/view-national-highway', [ViewNationalHighwayController::class, 'showNationalHighway'])->name('viewNHWings');
    Route::get('/view-national-highway-cdworks', [ViewNationalHighwayController::class, 'showNHCdWork'])->name('viewNHCDWorks');
    Route::get('/view-national-highway-bridges', [ViewNationalHighwayController::class, 'showNHBridge'])->name('viewNHBridge');
    Route::get('/view-national-highway-pci', [ViewNationalHighwayController::class, 'showNHPCI'])->name('viewNHPCI');
    Route::get('/view-national-highway-protection-wall', [ViewNationalHighwayController::class, 'showNHProtectionWallDetails'])->name('viewNHProtectionWall');
    Route::get('/view-national-highway-surface-types', [ViewNationalHighwayController::class, 'showNHSurfaceType'])->name('viewNHSurfaceType');
    Route::get('/view-national-highway-habitation', [ViewNationalHighwayController::class, 'showNHHabitation'])->name('viewNHHabitation');
    // Building
    Route::get('/view-building', [ViewHousingController::class, 'showBuilding'])->name('viewBuildingWings');
    // Mechanical
    Route::get('/view-vehicle', [ViewMechanicalController::class, 'showVehicle'])->name('viewVehicleWings');
    Route::get('/view-equipment', [ViewMechanicalController::class, 'showEquipment'])->name('viewEquipmentWings');
    // End

    // Show Images for assets and subassets
    Route::get('/view-culvert-images/{id}/{type}', [AssetImageController::class, 'culvertImages'])->name('image.culvert');

    // Start - MIS for all wing according to user search input
    // MIS-Road
    Route::get('/mis-road', [RoadMISController::class, 'searchRoad'])->name('searchRoad');
    Route::get('/mis-road-cdworks', [RoadMISController::class, 'searchCdWorks'])->name('searchCdWorks');
    Route::get('/mis-road-bridge', [RoadMISController::class, 'searchBridge'])->name('searchBridge');
    Route::get('/mis-road-pci', [RoadMISController::class, 'searchPCI'])->name('searchPCI');
    Route::get('/mis-road-protection-wall', [RoadMISController::class, 'searchProtectionWall'])->name('searchProtectionWall');
    Route::get('/mis-road-surfaceType', [RoadMISController::class, 'searchSurfaceType'])->name('searchSurfaceType');
    Route::get('/mis-road-habitation', [RoadMISController::class, 'searchHabitation'])->name('searchHabitation');
    Route::get('/get-road-by-district/{id}', [MisController::class, 'filterRoadByDistrict'])->name('filterRoadByDistrict');
    Route::get('/get-road-by-zone/{id}', [MisController::class, 'filterRoadByZone'])->name('filterRoadByZone');
    Route::get('/get-road-by-circle/{id}', [MisController::class, 'filterRoadByCircle'])->name('filterRoadByCircle');
    Route::get('/get-road-by-division/{id}', [MisController::class, 'filterRoadByDivision'])->name('filterRoadByDivision');
    Route::get('/road-by-division-name', [MisController::class, 'filterRoadByDivisionName'])->name('filterRoadByDivisionName');
    Route::get('/road-by-division-and-chainage', [MisController::class, 'filterRoadByDivisionNameUsingChainage']);
    Route::get('/nh-by-division-name', [MisController::class, 'filterNHByDivisionName'])->name('filterNHByDivisionName');
    Route::get('/get-road-by-subdivision/{id}', [MisController::class, 'filterRoadBySubDivision'])->name('filterRoadBySubDivision');
    // MIS-NH
    Route::get('/mis-nh', [NationalHighwayMISController::class, 'searchNH'])->name('searchNH');
    Route::get('/mis-nh-cdworks', [NationalHighwayMISController::class, 'searchNHCdWorks'])->name('searchNHCdWorks');
    Route::get('/mis-nh-bridge', [NationalHighwayMISController::class, 'searchNHBridge'])->name('searchNHBridge');
    Route::get('/mis-nh-pci', [NationalHighwayMISController::class, 'searchNHPCI'])->name('searchNHPCI');
    Route::get('/mis-nh-protection-wall', [NationalHighwayMISController::class, 'searchNHProtectionWall'])->name('searchNHProtectionWall');
    Route::get('/mis-nh-surfaceType', [NationalHighwayMISController::class, 'searchNHSurfaceType'])->name('searchNHSurfaceType');
    Route::get('/mis-nh-habitation', [NationalHighwayMISController::class, 'searchNHHabitation'])->name('searchNHHabitation');
    // MIS-Building
    Route::get('/mis-building', [HousingMISController::class, 'searchBuilding'])->name('searchBuilding');
    // MIS-mechanicals
    Route::get('/mis-mechanical-equipment', [MechanicalMISController::class, 'searchEquipment'])->name('searchEquipment');
    Route::get('/mis-mechanical-vehicle', [MechanicalMISController::class, 'searchVehicle'])->name('searchVehicle');
    // END


    //Delete Road From Map -- Start
    Route::get('/select-road-frm-map-to-delete', [DashboardController::class, 'viewRoadsInMapToDelete'])->name('viewRoadsInMapToDelete');
    Route::get('/delete-road-frm-map', [RoadController::class, 'deleteRoadFromMap'])->name('deleteRoadFromMap');
    //Delete Road From Map -- End

    Route::get('/asset-images', [MisController::class, 'getAssetImages']);
});



//No Auth Middleware group for below routes as these are public routes and can be accessed without login
Route::group(['middleware' => ['prevent.cache']], function () {
    Route::get('checkUserActive', [UserController::class, 'checkUserActive'])->name('checkUserActive');
    Route::get("/", [WelcomeController::class, 'getWelcomeDashBoard'])->name('getWelcomeDashBoard');
    Route::get('/privacy_policy', [WelcomeController::class, 'getPrivacyPolicy'])->name('getPrivacyPolicy');
    Route::get("getRoadByCatg/{cd}", [WelcomeController::class, 'roadByCatg'])->name('citizen.getRoadByCatg');
    Route::get("loadRoadByCatg", [WelcomeController::class, 'loadRoadByCatg'])->name('loadRoadByCatg');
    Route::get("getNHRoadByCatg", [WelcomeController::class, 'roadNHByCatg'])->name('getNHRoadByCatg');
    Route::get("getHousingByClass/{cd}", [WelcomeController::class, 'housingByClass'])->name('getHousingByClass');
    Route::get('/getAllStatesRoadsGeoJsonData', [WelcomeController::class, 'getAllStatesRoadsGeoJsonData'])->name('getAllStatesRoadsGeoJsonData');
    Route::get('/getAllSHNHMDRRoadsGeoJsonData', [WelcomeController::class, 'getAllSHNHMDRRoadsGeoJsonData'])->name('getAllSHNHMDRRoadsGeoJsonData');
    Route::get('/getAllStatesRoadsGeoJsonDataWithLazyLoading', [WelcomeController::class, 'getAllStatesRoadsGeoJsonDataWithLazyLoading'])->name('getAllStatesRoadsGeoJsonDataWithLazyLoading');
    Route::get('/getDivisionsRoadsGeoJsonData/{division_cd}', [WelcomeController::class, 'getDivisionsRoadsGeoJsonData'])->name('getDivisionsRoadsGeoJsonData');
    Route::get('/getDraftedRoadsWithDivisionRoads/{road_id}', [WelcomeController::class, 'getDraftedRoadsWithDivisionRoads'])->name('getDraftedRoadsWithDivisionRoads');
});