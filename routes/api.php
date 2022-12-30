<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorTypeController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AssettypeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ScrapAssetController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AssetMasterController;
use App\Http\Controllers\GetDataController;
use App\Http\Controllers\TransferAssetController;
use App\Http\Controllers\TagAssetController;
use App\Http\Controllers\UntagAssetController;
use App\Http\Controllers\AmcController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RequestServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserModuleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LineController;



Route::get('token', function() {
    $response = [          
        "message" =>  " Token is  required",
        "status" => 400
    ];
    $status = 400;

    return Response($response, $status);
                                                                                                           
})->name('token');


Route::middleware(['auth:sanctum'])->group(function () {
    //All secure URL's

    Route::post('user/{id}/update',[UsersController::class,'update']);
    Route::post('user/{id}/delete',[UsersController::class,'destroy']);
    Route::post('user/{id}/block',[UsersController::class,'block']);
    Route::get('user/showData',[UsersController::class,'showData']);
    Route::post('logout',[UsersController::class,'logout']);
    
});
    

//users
Route::post('user/add',[UsersController::class,'store']);
Route::get('user/empId',[UsersController::class,'empId']);

//login 
Route::post('login',[UsersController::class,'loginUser']);

//vendor
Route::post('vendor/add', [VendorController::class, 'store']);
Route::post('vendor/{id}/update', [VendorController::class, 'update']);
Route::post('vendor/{id}/delete', [VendorController::class, 'destroy']);
Route::get('vendor/showData', [VendorController::class, 'showData']);

//VendorType
Route::post('vendorType/add', [VendorTypeController::class, 'store']);
Route::post('vendorType/{id}/update', [VendorTypeController::class, 'update']);
Route::post('vendorType/{id}/delete', [VendorTypeController::class, 'destroy']);
Route::get('vendorType/showData', [VendorTypeController::class, 'showData']);

//Asset
Route::post('asset/add', [AssetController::class, 'store']);
Route::post('asset/{id}/update', [AssetController::class, 'update']);
Route::post('asset/{id}/delete', [AssetController::class, 'destroy']);
Route::get('asset/showData', [AssetController::class, 'showData']);
Route::get('asset/assetId', [AssetController::class, 'assetId']);
Route::post('asset/import', [AssetController::class, 'import']);
Route::get('asset/template', [AssetController::class, 'template']);


//Department
Route::post('department/add',[DepartmentController::class,'store']);
Route::post('department/{id}/update',[DepartmentController::class,'update']);
Route::post('department/{id}/delete',[DepartmentController::class,'destroy']);
Route::get('department/showData',[DepartmentController::class,'showData']);


//Section
Route::post('section/add',[SectionController::class,'store']);
Route::post('section/{id}/update',[SectionController::class,'update']);
Route::post('section/{id}/delete',[SectionController::class,'destroy']);
Route::get('section/showData',[SectionController::class,'showData']);

//AssetType   
Route::post('assetType/add',[AssettypeController::class,'store']);
Route::post('assetType/{id}/update',[AssettypeController::class,'update']);
Route::post('assetType/{id}/delete',[AssettypeController::class,'destroy']);
Route::get('assetType/showData',[AssettypeController::class,'showData']);

//unit
Route::post('unit/add',[UnitController::class,'store']);
Route::post('unit/{id}/update',[UnitController::class,'update']);
Route::post('unit/{id}/delete',[UnitController::class,'destroy']);
Route::get('unit/showData',[UnitController::class,'showData']);


<<<<<<< HEAD
=======
//Project
Route::post('project/add', [ProjectController::class, 'store']);
Route::post('project/{id}/update', [ProjectController::class, 'update']);
Route::post('project/{id}/delete', [ProjectController::class, 'destroy']);
Route::get('project/showData', [ProjectController::class, 'showData']);


//Line
Route::post('line/add', [LineController::class, 'store']);
Route::post('line/{id}/update', [LineController::class, 'update']);
Route::post('line/{id}/delete', [LineController::class, 'destroy']);
Route::get('line/showData', [LineController::class, 'showData']);


>>>>>>> e24142997c4fe6bdc91b49f1c2676430d205be81
//Label
Route::post('label/add',[LabelController::class,'store']);
Route::post('label/{id}/delete',[LabelController::class,'destroy']);
Route::get('label/showData',[LabelController::class,'showData']);
Route::get('label/{id}/showLabel',[LabelController::class,'showLabel']);
// Route::post('label/assetGetId', [LabelController::class, 'assetGetId']);


//ScrapAssetes
Route::post('scrapAsset/add',[ScrapAssetController::class,'store']);
Route::get('scrapAsset/showData',[ScrapAssetController::class,'showData']);
Route::get('scrapAsset/export',[ScrapAssetController::class,'export']);


//Audit
Route::post('audit/add', [AuditController::class, 'store']);
Route::post('audit/{id}/update', [AuditController::class, 'update']);
Route::post('audit/{id}/delete', [AuditController::class, 'destroy']);
Route::get('audit/showData', [AuditController::class, 'showData']);
Route::post('audit/{id}/viewAuditReport', [AuditController::class, 'viewAuditReport']);
Route::get('audit/{id}/export', [AuditController::class, 'export']);


//Allocation
Route::post('allocation/add', [AllocationController::class, 'store']);
Route::post('allocation/{id}/update', [AllocationController::class, 'update']);
Route::post('allocation/showData', [AllocationController::class, 'showData']);
Route::get('allocation/getEmpId',[AllocationController::class, 'getEmpId']);
Route::post('allocation/{id}/getEmpName',[AllocationController::class, 'getEmpName']);
Route::get('allocation/{id}/getUser',[AllocationController::class, 'getUser']);
Route::get('allocation/export', [AllocationController::class, 'export']);


//Allocation->RequestedAsset
Route::get('allocation/showRequestReturnAsset', [AllocationController::class, 'showRequestReturnAsset']);
Route::post('allocation/{id}/updateRequestedReturnAsset', [AllocationController::class, 'updateRequestedReturnAsset']);
Route::get('allocation/viewReturnAsset', [AllocationController::class, 'viewReturnAsset']);
Route::get('allocation/viewSelfAssessment', [AllocationController::class, 'viewSelfAssessment']);


//TransferAsset
Route::post('transferAsset/{id}', [TransferAssetController::class, 'transferData']);

// AssetMaster
Route::get('assetMaster/{id}/showData', [AssetMasterController::class, 'showData']);
Route::get('assetMaster/{id}/export', [AssetMasterController::class, 'export']);


//GetData
Route::get('getDepartment', [GetDataController::class, 'getDepartment']);
Route::get('getSection/{id}', [GetDataController::class, 'getSection']);
Route::get('getAssetType/{id}', [GetDataController::class, 'getAssetType']);
Route::get('getAssetName/{id}', [GetDataController::class, 'getAssetName']);
Route::get('getMachine', [GetDataController::class, 'getMachine']);
Route::get('getVendor', [GetDataController::class, 'getVendor']);
Route::get('getVendorData/{id}', [GetDataController::class, 'getVendorData']);


//TagAsset
Route::post('tagAsset/add', [TagAssetController::class, 'store']);
Route::get('tagAsset/{id}/getAssetId', [TagAssetController::class, 'getAssetId']);
Route::get('tagAsset/selectAssetId', [TagAssetController::class, 'selectAssetId']);
Route::get('tagAsset/rfid', [TagAssetController::class, 'rfid']);


//UnTagAsset
Route::post('untagAsset/{id}/update', [UntagAssetController::class, 'update']);
Route::post('untagAsset/showData', [UntagAssetController::class, 'showData']);
Route::post('untagAsset/{id}/untagUpdate', [UntagAssetController::class, 'untagUpdate']);
Route::get('untagAsset/export', [UntagAssetController::class, 'export']);


//Maintenance
Route::post('maintenance/add', [MaintenanceController::class, 'store']);
Route::get('maintenance/showData', [MaintenanceController::class, 'showData']);
Route::get('maintenance/getMaintenanceId', [MaintenanceController::class, 'getMaintenanceId']);
Route::post('maintenance/{id}/showStatus', [MaintenanceController::class, 'showStatus']);
Route::post('maintenance/{id}/updateAction', [MaintenanceController::class, 'updateAction']);
Route::post('maintenance/{id}/updateClosedMaintenance', [MaintenanceController::class, 'updateClosedMaintenance']);
Route::get('maintenance/aprovedShowData', [MaintenanceController::class, 'aprovedShowData']);
Route::get('maintenance/export', [MaintenanceController::class, 'export']);
Route::get('maintenance/pendingShowData', [MaintenanceController::class, 'pendingShowData']);
Route::get('maintenance/rejectedShowData', [MaintenanceController::class, 'rejectedShowData']);
Route::get('maintenance/showClosedMaintenance', [MaintenanceController::class, 'showClosedMaintenance']);
Route::get('maintenance/insuranceCheck', [MaintenanceController::class, 'insuranceCheck']);
Route::get('maintenance/getUserName', [MaintenanceController::class, 'getUserName']);


//Amc
Route::post('amc/add', [AmcController::class, 'store']);
Route::post('amc/{id}/update', [AmcController::class, 'update']);
Route::post('amc/{id}/delete', [AmcController::class, 'destroy']);
Route::get('amc/showData', [AmcController::class, 'showData']);
Route::post('amc/{id}/updateServiceDate', [AmcController::class, 'updateServiceDate']);
Route::get('amc/{id}/showData1', [AmcController::class, 'showData1']);
Route::get('amc/{id}/showService', [AmcController::class, 'showService']);
Route::post('amc/{id}/serviceDue', [AmcController::class, 'serviceDue']);
Route::get('amc/viewAmcRenewal', [AmcController::class, 'viewAmcRenewal']);
Route::post('amc/{id}/renewalAmc', [AmcController::class, 'renewalAmc']);
Route::get('amc/export', [AmcController::class, 'export']);
Route::get('amc/{id}/download', [AmcController::class, 'download']);


//Certificate
Route::post('certificate/add', [CertificateController::class, 'store']);
Route::post('certificate/{id}/update', [CertificateController::class, 'update']);
Route::post('certificate/{id}/delete', [CertificateController::class, 'destroy']);
Route::get('certificate/showData', [CertificateController::class, 'showData']);
Route::post('certificate/{id}/updateInspectionDate', [CertificateController::class, 'updateInspectionDate']);
Route::get('certificate/{id}/showData1', [CertificateController::class, 'showData1']);
Route::post('certificate/{id}/inspectionDue', [CertificateController::class, 'inspectionDue']);
Route::get('certificate/viewCertificateRenewal', [CertificateController::class, 'viewCertificateRenewal']);
Route::get('certificate/{id}/showInspection', [CertificateController::class, 'showInspection']);
Route::post('certificate/{id}/renewalCertificate', [CertificateController::class, 'renewalCertificate']);
Route::get('certificate/export', [CertificateController::class, 'export']);
Route::get('certificate/{id}/download', [CertificateController::class, 'download']);


//Warranty
Route::post('warranty/showData', [WarrantyController::class, 'showData']);
Route::get('warranty/{id}/viewAsset', [WarrantyController::class, 'viewAsset']);

//Insurence
Route::post('insurance/add', [InsuranceController::class, 'store']);
Route::post('insurance/{id}/update', [InsuranceController::class, 'update']);
Route::post('insurance/{id}/delete', [InsuranceController::class, 'destroy']);
Route::get('insurance/showData', [InsuranceController::class, 'showData']);
Route::post('insurance/{id}/insuranceDue', [InsuranceController::class, 'insuranceDue']);
Route::get('insurance/viewInsuranceRenewal', [InsuranceController::class, 'viewInsuranceRenewal']);
Route::post('insurance/{id}/renewalInsurance', [InsuranceController::class, 'renewalInsurance']);
Route::get('insurance/export', [InsuranceController::class, 'export']);

//RequestService
Route::get('requestService/showData', [RequestServiceController::class, 'showData']);
Route::post('requestService/{id}/update', [RequestServiceController::class, 'update']);
Route::get('requestService/{id}/showData1', [RequestServiceController::class, 'showData1']);
Route::post('requestService/{id}/updateServiceStatus', [RequestServiceController::class, 'updateServiceStatus']);
Route::get('requestService/export', [RequestServiceController::class, 'export']);

//DashBoard
Route::get('assets/showData', [DashboardController::class, 'assets']);
Route::get('newAsset/showData', [DashboardController::class, 'newAsset']);
Route::get('tagAsset/showData', [DashboardController::class, 'tagAsset']);
Route::get('untagAsset/showData', [DashboardController::class, 'untagAsset']);
Route::get('warrantyDue/showData', [DashboardController::class, 'warrantyDue']);
Route::get('serviceeDue/showData', [DashboardController::class, 'serviceeDue']);
Route::get('inspectionDue/showData', [DashboardController::class, 'inspectionDue']);
Route::get('amcDue/showData', [DashboardController::class, 'amcDue']);
Route::get('certificateDue/showData', [DashboardController::class, 'certificateDue']);
Route::get('insuranceDue/showData', [DashboardController::class, 'insuranceDue']);
Route::get('transferDue/showData', [DashboardController::class, 'transferDue']);
Route::get('auditDue/showData', [DashboardController::class, 'auditDue']);
Route::get('eol/showData', [DashboardController::class, 'eol']);
Route::get('notInuse/showData', [DashboardController::class, 'notInuse']);
Route::get('damage/showData', [DashboardController::class, 'damage']);
Route::get('transfer/showData', [DashboardController::class, 'transfer']);
Route::get('sales/showData', [DashboardController::class, 'sales']);
Route::get('getCount', [DashboardController::class, 'getCount']);
Route::post('getDisplayData', [DashboardController::class, 'displayData']);

//UserModule
Route::get('um/showAsset', [UserModuleController::class, 'showAsset']);
Route::post('um/add', [UserModuleController::class, 'store']);
Route::get('um/getAssetName', [UserModuleController::class, 'getAssetName']);
Route::get('um/{id}/showStatus', [UserModuleController::class, 'showStatus']);
Route::get('um/viewServiceRequest', [UserModuleController::class, 'viewServiceRequest']);
Route::get('um/showReturnAsset', [UserModuleController::class, 'showReturnAsset']);
Route::post('um/{id}/updateReturnAsset', [UserModuleController::class, 'updateReturnAsset']);
Route::post('um/{id}/updateSelfAssessment', [UserModuleController::class, 'updateSelfAssessment']);

