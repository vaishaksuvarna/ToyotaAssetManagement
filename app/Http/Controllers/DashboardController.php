<?php

namespace App\Http\Controllers;
use App\Models\Asset;
use App\Models\Allocation;
use App\Models\tagAsset;
use App\Models\scrapAsset;
use App\Models\RequestService;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    public function assetsCount()
    {
        $count = Asset::count();
        $counts = $count;
        return $counts;

    }

    public function assets()
    {
      try{    
            $asset = DB::table('assets');

            if(!$asset){
               throw new Exception("Asset not found");

            }else{
                $asset = DB::table('assets')
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->select('assets.*','assets.id','assets.department',
                     'departments.department_name as departmentName', 
                     'assets.section', 'sections.section as sectionName',
                     'assets.assetName', 'assets.assetType','assettypes.assetType as assetTypeName',
                     'assets.manufacturer', 'assets.assetModel', 'assets.warrantyStartDate', 
                     'assets.warrantyEndDate')
                    ->get();
                        
                $response=[
                    "message" => "Asset List",
                    "data" => $asset
                ];
                $status = 200; 
            } 

        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function newAssetCount()
    {
        $count = DB::table('assets')->where('allocated','=','new')->count();
        $counts= $count;
        return $counts;
    }

    public function newAsset()
    {
      try{    
            $asset = DB::table('assets')->where('allocated','=','new')->get();

            if(count($asset)<=0){
               throw new Exception("Asset not found");

            }else{
                $asset = DB::table('assets')
                    ->where('allocated','=','new')
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->select('assets.*','assets.id','departments.department_name as departmentName', 
                      'sections.section as sectionName','assets.assetName', 'assettypes.assetType as assetTypeName',
                     'assets.manufacturer', 'assets.assetModel', 'assets.warrantyStartDate', 
                     'assets.warrantyEndDate')
                    ->get();
                        
                $response=[
                    "message" => "Asset List",
                    "data" => $asset
                ];
                $status = 200; 
            } 

        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function tagAssetsCount()
    {
        $count = tagAsset::count();
        $counts = $count;
        return $counts;

    }


    public function tagAsset()
    {
      try{    
            $tagAsset = DB::table('tag_assets');

            if(!$tagAsset){
               throw new Exception("Asset not found");
            }else{
                $tagAsset = DB::table('tag_assets')
                    ->join('departments','departments.id','=','tag_assets.department')
                    ->join('sections','sections.id','=','tag_assets.section')
                    ->join('assettypes','assettypes.id','=','tag_assets.assetType')
                    ->join('assets','assets.id','=','tag_assets.assetName')
                    ->select('tag_assets.*','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName')
                    ->get();
                        
                $response=[
                    "message" => "Tag Assets List",
                    "data" => $tagAsset
                ];
                $status = 200; 
            } 

        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function untagCount()
    {
        $count = DB::table('allocations')->where('reasonForUntag','!=','null')->count();
        $counts = $count;
        return $counts;

    }

    public function untagAsset()
    {
      try{    
            $tagAsset = DB::table('allocations');

            if(!$tagAsset){
               throw new Exception("Asset not found");
            }else{
                $tagAsset = DB::table('allocations')
                    ->join('departments','departments.id','=','allocations.department')
                    ->join('sections','sections.id','=','allocations.section')
                    ->join('assettypes','assettypes.id','=','allocations.assetType')
                    ->join('assets','assets.id','=','allocations.assetName')
                    ->select('allocations.*','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName')
                    ->get();
                        
                $response=[
                    "message" => "UnTag Assets List",
                    "data" => $tagAsset
                ];
                $status = 200; 
            } 

        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function warrantyDueCount()
    {
        $count = DB::table('assets')->whereBetween('warrantyEndDate', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }

    public function warrantyDue(Request $request)
    {
        try{    
            $result = DB::table('assets')
                     ->whereBetween('warrantyEndDate', [now(), now()->addDays(7)])
                     ->join('departments','departments.id','=','assets.department')
                     ->select('assets.id','departments.department_name as department','assets.assetName',
                     'assets.warrantyStartDate', 'assets.warrantyEndDate')
                     ->get();

                    if(count($result)<=0){
                    throw new Exception("data not found");
                }
            $response=[
             "message" => "View Warranty List",
             "data" => $result
            ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
            ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function serviceeDueCount()
    {
        $count = DB::table('amcs')->whereBetween('periodTo', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }

    public function serviceeDue()
    {
        try{    

            $amc = DB::table('amcs')
                ->whereBetween('periodTo', [now(), now()->addDays(7)])
                ->join('vendors','vendors.id','=','amcs.vendorName')
                ->join('assets','assets.id','=','amcs.assetName')
                ->select('amcs.id','vendors.vendorName as vendorName','assets.assetName as assetName','amcs.periodTo as serviceDueDate')
                ->get();
                                            
                if(count($amc)<=0){
                    throw new Exception("No Data Available");
                }

            $response=[
                "message" => "Service Due Date List",
                "data" => $amc
            ];
            $status = 200; 
        
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function inspectionDueCount()
    {
        $count = DB::table('certificates')->whereBetween('expireDate', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }


    public function inspectionDue()
    {
        try{ 

            $certificate = DB::table('certificates')
                ->whereBetween('expireDate', [now(), now()->addDays(7)])
                ->join('vendors','vendors.id','=','certificates.vendorName')
                ->join('assets','assets.id','=','certificates.assetName')
                ->select('certificates.id','vendors.vendorName as vendorName', 'assets.assetName as assetName','expireDate as inspectionDueDate')
                ->get();
                     
                if(count($certificate)<=0){
                    throw new Exception("No Data Available");
                }

            $response=[
                "message" => "inspectionDue ",
                "data" => $certificate
            ];
            $status = 200; 
        
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function amcDueCount()
    {
        $count = DB::table('amcs')->whereBetween('periodTo', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }

    public function amcDue()
    {
        try{    

            $amc = DB::table('amcs')
                ->whereBetween('periodTo', [now(), now()->addDays(7)])
                ->join('vendors','vendors.id','=','amcs.vendorName')
                ->join('departments','departments.id','=','amcs.department')
                ->join('sections','sections.id','=','amcs.section')
                ->join('assettypes','assettypes.id','=','amcs.assetType')
                ->join('assets','assets.id','=','amcs.assetName')
                ->select('amcs.*','vendors.vendorName as vendorName','vendors.id as vendorId','periodFrom','periodTo','servicePattern','departments.department_name as department',
                  'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName','departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId',
                  'Assets.id as assetNameId')
                ->get();
                                            
                if(count($amc)<=0){
                    throw new Exception("No Data Available");
                }

            $response=[
                "message" => "Annual maintainence Due List",
                "data" => $amc
            ];
            $status = 200; 
        
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function certificateDueCount()
    {
        $count = DB::table('certificates')->whereBetween('expireDate', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }

    public function certificateDue()
    {
        try{   

                $certificate = DB::table('certificates')
                    ->whereBetween('expireDate', [now(), now()->addDays(7)])
                    ->join('vendors','vendors.id','=','certificates.vendorName')
                    ->join('departments','departments.id','=','certificates.department')
                    ->join('sections','sections.id','=','certificates.section')
                    ->join('assettypes','assettypes.id','=','certificates.assetType')
                    ->join('assets','assets.id','=','certificates.assetName')
                    ->select('certificates.id','vendors.vendorName as vendorName','certificateDate','expireDate','inspectionPattern','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType',
                      'assets.assetName as assetName', 'departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId',
                      'assets.id as assetNameId')
                    ->get();
                     
                    if(count($certificate)<=0){
                        throw new Exception("No Data Available");
                    }

                $response=[
                    "message" => "Certificate Due",
                    "data" => $certificate
                ];
                $status = 200; 
        
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function insuranceDueCount()
    {
        $count = DB::table('insurances')->whereBetween('periodTo', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }

    public function insuranceDue()
    {
      try{    

          $insurance = DB::table('insurances')
              ->whereBetween('periodTo', [now(), now()->addDays(7)])
              ->join('vendors','vendors.id','=','insurances.vendorName')
              ->join('departments','departments.id','=','insurances.department')
              ->join('sections','sections.id','=','insurances.section')
              ->join('assettypes','assettypes.id','=','insurances.assetType')
              ->join('assets','assets.id','=','insurances.assetName')
              ->select('insurances.id','vendors.vendorName as vendorName','periodFrom','periodTo',
                'departments.department_name as department', 'sections.section as section',
                'assettypes.assetType as assetType','assets.assetName as assetName','departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId',
                'assets.id as assetNameId')
              ->get();
              
              if(count($insurance)<=0){
                  throw new Exception("No Data Available");
              }
                        
          $response=[
            "message" => "insurance Due List",
            "data" => $insurance
          ];
          $status = 200; 
          

      }catch(Exception $e){
          $response = [
           "message"=>$e->getMessage(),
            "status" => 406
            ];            
          $status = 406;
          
      }catch(QueryException $e){
          $response = [
              "error" => $e->errorInfo,
              "status" => 406
             ];
          $status = 406; 
      }
      return response($response,$status); 

    }

    public function transferDueCount()
    {
        $count = DB::table('allocations')->whereBetween('toDate', [now(), now()->addDays(7)])->count();
        $counts = $count;
        return $counts;

    }

    public function transferDue(Request $request)
    {
        try{    
            $result = DB::table('allocations')
                ->whereBetween('toDate', [now(), now()->addDays(7)])
                ->join('departments','departments.id','=','allocations.department')
                ->join('sections','sections.id','=','allocations.section')
                ->join('assettypes','assettypes.id','=','allocations.assetType')
                ->join('assets','assets.id','=','allocations.assetName')
                ->select('allocations.id','departments.department_name as department',
                'sections.section as section',
                'assettypes.assetType as assetType','assets.assetName as assetName')
                ->get();

                    if(count($result)<=0){
                    throw new Exception("data not found");
                }
            $response=[
             "message" => "View Warranty List",
             "data" => $result
            ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
            ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function auditDueCount()
    {
        $count = DB::table('audits')->whereBetween('auditDate', [now(), now()->addDays(10)])->count();
        $counts = $count;
        return $counts;

    }

    public function auditDue()
     {
        try{

            $audit = DB::table('audits')
                ->whereBetween('auditDate', [now(), now()->addDays(10)])
                ->join('departments','departments.id','=','audits.department')
                ->join('sections','sections.id','=','audits.section')
                ->join('assettypes','assettypes.id','=','audits.assetType')
                ->select('audits.*','departments.department_name as department',
                  'sections.section as section','assettypes.assetType as assetType', 
                  'departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId')
                ->get();
                
                if(count($audit)<=0){
                    throw new Exception("No Data Available");
                }

                $response = [
                    'success' => true,
                    'data' => $audit         
                ];
                $status = 201;   
            
 
        }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404;     

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
            ];
            $status = 406; 
        }

        return response($response,$status);
    } 

    public function eolCount()
    {
        $count = DB::table('scrap_assets')->where('scrapType','=','EOL')->count();
        $counts = $count;
        return $counts;

    }

    public function eol()
    {
        try{    
            $scrapAsset = DB::table('scrap_assets')
                ->where('scrapType','=','EOL')
                ->join('departments','departments.id','=','scrap_assets.department')
                ->join('sections','sections.id','=','scrap_assets.section')
                ->join('assettypes','assettypes.id','=','scrap_assets.assetType')
                ->join('assets','assets.id','=','scrap_assets.assetName')
                ->select('scrap_assets.id','departments.department_name as department',
                 'sections.section as section','assettypes.assetType as assetType',
                 'assets.assetName as assetName','scrap_assets.created_at as dateAndTime','scrap_assets.user')
               ->get();
          
            if(!$scrapAsset){
             throw new Exception("No Data Available");
            }

            $response=[
             "message" => "ScrapAsset List",
             "data" => $scrapAsset
            ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }


    public function notInuseCount()
    {
        $count = DB::table('assets')->where('allocated','=',0)->count();
        $counts= $count;
        return $counts;
    }

    public function notInuse()
    {
      try{    
            $asset = DB::table('assets');

            if(!$asset){
               throw new Exception("Asset not found");

            }else{
                $asset = DB::table('assets')
                    ->where('allocated','=',0)
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->select('assets.*','assets.id','departments.department_name as departmentName', 
                      'sections.section as sectionName','assets.assetName', 'assettypes.assetType as assetTypeName',
                     'assets.manufacturer', 'assets.assetModel', 'assets.warrantyStartDate', 
                     'assets.warrantyEndDate')
                    ->get();
                        
                $response=[
                    "message" => "Asset List",
                    "data" => $asset
                ];
                $status = 200; 
            } 

        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function damageCount()
    {
        $count = DB::table('allocations')->where('reasonForUntag','=','defect')->count();
        $counts = $count;
        return $counts;

    }

    public function damage(Request $request)
    {
      try{    
            $result = DB::table('allocations')
                ->where('reasonForUntag','=','defect')
                ->join('departments','departments.id','=','allocations.department')
                ->join('sections','sections.id','=','allocations.section')
                ->join('assettypes','assettypes.id','=','allocations.assetType')
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('tag_assets','tag_assets.assetName','=','allocations.assetName')
                ->select('allocations.*','departments.department_name as department',
                  'sections.section as  section','assettypes.assetType as assetType',
                  'assets.assetName as assetName','tag_assets.rfidNo as rfidNo')  
                ->get();
                    
                if(count($result)<=0){
                    throw new Exception("data not found");
                }
            $response=[
             "message" => "Damage Assets List",
             "data" => $result
            ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
            ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function transferCount()
    {
        $count = DB::table('assets')->where('transfer','=','1')->count();
        $counts = $count;
        return $counts;

    }

    public function transfer()
    {
        try{    
           
            $asset = DB::table('assets')
                ->where('transfer','=','1')
                ->join('departments','departments.id','=','assets.department')
                ->join('sections','sections.id','=','assets.section')
                ->join('assettypes','assettypes.id','=','assets.assetType')
                ->select('assets.*','assets.id','assets.department',
                 'departments.department_name as departmentName', 
                 'assets.section', 'sections.section as sectionName',
                 'assets.assetName', 'assets.assetType','assettypes.assetType as assetTypeName',
                 'assets.manufacturer', 'assets.assetModel', 'assets.warrantyStartDate', 
                 'assets.warrantyEndDate')
                ->get();
                
                if(count($asset)<=0){
                    throw new Exception("data not found");
                }

            $response=[
                "message" => "Asset List",
                "data" => $asset
            ];
            $status = 200; 
    

        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
              ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function inServiceCount()
    {
        $count = RequestService::count();
        $counts = $count;
        return $counts;

    }


    public function salesCount()
    {
        $count = DB::table('allocations')->where('reasonForUntag','=','sale')->count();
        $counts= $count;
        return $counts;

    }

    public function sales(Request $request)
    {
      try{    
            $result = DB::table('allocations')
                ->where('reasonForUntag','=','sale')
                ->join('departments','departments.id','=','allocations.department')
                ->join('sections','sections.id','=','allocations.section')
                ->join('assettypes','assettypes.id','=','allocations.assetType')
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('tag_assets','tag_assets.assetName','=','allocations.assetName')
                ->select('allocations.*','departments.department_name as department',
                  'sections.section as  section','assettypes.assetType as assetType',
                  'assets.assetName as assetName','tag_assets.rfidNo as rfidNo')  
                ->get();
                    
                if(count($result)<=0){
                    throw new Exception("data not found");
                }
                
            $response=[
             "message" => "Sale Assets List",
             "data" => $result
            ];
            $status = 200; 
            
        }catch(Exception $e){
            $response = [
             "message"=>$e->getMessage(),
              "status" => 406
            ];            
            $status = 406;
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function scrapCount()
    {
        $count = scrapAsset::count();
        $counts = $count;
        return $counts;

    }
   
    
    public function maintenanceCount()
    {
        $count = Maintenance::count();
        $counts = $count;
        return $counts;

    }


   

    public function getCount(Request $request)
    {

        $response["assetsCount"] = $this->assetsCount();
        $response["newAssetCount"] = $this->newAssetCount();
        $response["tagAssetsCount"] = $this->tagAssetsCount();
        $response["untagCount"] = $this->untagCount();
        $response["warrantyDueCount"] = $this->warrantyDueCount();
        $response["serviceeDueCount"] = $this->serviceeDueCount();
        $response["inspectionDueCount"] = $this->inspectionDueCount();
        $response["amcDueCount"] = $this->amcDueCount();
        $response["certificateDueCount"] = $this->certificateDueCount();
        $response["insuranceDueCount"] = $this->insuranceDueCount();
        $response["transferDueCount"] = $this->transferDueCount();
        $response["auditDueCount"] = $this->auditDueCount();
        $response["eolCount"] = $this->eolCount();
        $response["notInuseCount"] = $this->notInuseCount();
        $response["damageCount"] = $this->damageCount();
        $response["transferCount"] = $this->transferCount();
        $response["inServiceCount"] = $this->inServiceCount();
        $response["salesCount"] = $this->salesCount();
        $response["scrapCount"] = $this->scrapCount();
        $response["maintenanceCount"] = $this->maintenanceCount();
        return $response;

    } 

    public function service(Request $request)
    {
        try{

            $data = DB::table('amcs')
                ->select('*','vendors.vendorName as vendorName','assets.assetName as assetName')
                ->join('vendors','vendors.id','=','amcs.vendorName')
                ->join('assets','assets.id','=','amcs.assetName')         
                ->get();

            if(count($data)>0){
                $vendorName = $data[0]->vendorName;
                $assetName = $data[0]->assetName;
                $periodFrom = $data[0]->periodFrom;
                $servicePattern = $data[0]->servicePattern;

                $rawData = array();
                $rawData["vendorName"] = $vendorName;
                $rawData["assetName"] = $assetName;
                $rawData ["periodFrom"] = $periodFrom;
                $rawData["servicePattern"] = $servicePattern;

                $data1 = array();
                for($i=1;$i<=number_format($servicePattern);$i++){
                    
                    if($i == 1){
                        $serviceSplit1 = explode("+",$data[0]->service1);

                        $date["s1startDate"] = $serviceSplit1[0];
                        $date["s1endDate"] = $serviceSplit1[1];
                        $date["s1runHours"] = $serviceSplit1[2];
                    }

                    if($i == 2){
                        $serviceSplit2 = explode("+",$data[0]->service2);
                        $date["s2startDate"] = $serviceSplit2[0];
                        $date["s2endDate"] = $serviceSplit2[1];
                        $date["s2runHours"] = $serviceSplit2[2];
                    }

                    if($i == 3){
                        $serviceSplit3 = explode("+",$data[0]->service3);
                        $date["s3startDate"] = $serviceSplit3[0];
                        $date["s3endDate"] = $serviceSplit3[1];
                        $date["s3runHours"] = $serviceSplit3[2];
                    }

                    if($i == 4){
                        $serviceSplit4 = explode("+",$data[0]->service4);
                        $date["s4startDate"] = $serviceSplit4[0];
                        $date["s4endDate"] = $serviceSplit4[1];
                        $date["s4runHours"] = $serviceSplit4[2];
                    }

                    if($i == 5){
                        $serviceSplit5 = explode("+",$data[0]->service5);
                        $date["s5startDate"] = $serviceSplit5[0];
                        $date["s5endDate"] = $serviceSplit5[1];
                        $date["s5runHours"] = $serviceSplit5[2];
                    }
                }

                $rawData["serviceList"] = $date;
                $response["data"][] = $rawData;
                $status = 200; 
            }
        
        }catch(Exception $e){
            $response = [
            "message"=>$e->getMessage(),
            "status" => 406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }

      return Response($response,$status);
    }
    
    public function displayData(Request $request){
        return "Hello";
    }
   
}