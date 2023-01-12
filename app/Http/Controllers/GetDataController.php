<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\unit;
use App\Models\department;
use App\Models\section;
use App\Models\Allocation;
use App\Models\assettype;
use App\Models\Vendor;
use App\Models\Asset;
use App\Models\requesterDepartment;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class GetDataController extends Controller
{

    //Retrving all requesterDepartment
    public function getrequesterDepartment()
    {
        try{
            $requesterDepartment = requesterDepartment::all();

            if(!$requesterDepartment){
                throw new Exception("Requester Department not found");
            }else{   

                $requesterDepartment=DB::table('requester_departments')->select('id','requesterDepartment')->get();
                
                $response = [
                    'success' => true,
                    'data' => $requesterDepartment,
                    'status' => 201
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status"=>406
            ];             
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }

   
    public function getLine($id)
    {
        try{
            $line=DB::table('lines')->where('unitPlant','=',$id)->get();

            if(count($line)<=0){
                throw new Exception("data not found");

            }else{

                $line = DB::table('lines')->select('lines.lineName','lines.id as lineId')->get();                

                $response = [
                    'success' => true,
                    'data' => $line,
                    'status' => 201
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status"=>406
            ];             
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }


    //Retrving all Units
    public function getUnit()
    {
        try{
            $unit = unit::all();

            if(!$unit){
                throw new Exception("unit not found");
            }else{   

                $unit = DB::table('units')->select('units.unitPlant','units.id as unitPlantId')->get();                
                $response = [
                    'success' => true,
                    'data' => $unit,
                    'status' => 201
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status"=>406
            ];             
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }

    //Retrving All Departments  
    public function getDepartment($id)
    {
        try{
            $department=DB::table('departments')->where('unitPlant','=',$id)->get();

            if(count($department)<=0){
                throw new Exception("data not found");

            }else{

                $response = [
                    'success' => true,
                    'data' => $department,
                    'status' => 201
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status"=>406
            ];             
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }
    

    //To Fetch The sections
    public function getSection( $id)
    { 
       try{ 
            $section = DB::table('sections')->where('department','=',$id)->get();
        
            if(count($section)<=0){
                throw new Exception("data not found");

            }else{
                
                $response = [
                    'success' => true,
                    'data' => $section         
                ];
                $status = 201;   
            }

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
     
    //To Fetch The assettypes
    public function getAssetType( $id)
    { 
        try{ 
            $assetType = DB::table('assettypes')->where('section','=',$id)->get();
        
            if(count($assetType)<=0){
                throw new Exception("data not found");
            }else{

                $response = [
                    'success' => true,
                    'data' => $assetType         
                ];
                $status = 201;   
            }

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
    

    //To Fetch The AssetName
    public function getAssetName($id)
    { 
        try{ 

            $assetName = DB::table('assets')->where('assetType','=',$id)->get();


            if(count($assetName)<=0){
                throw new Exception("data not found");
            }else{
               
                $response = [
                    'success' => true,
                    'data' => $assetName         
                ];
                $status = 201;   
            }

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


    //Retrving All AssetName
    public function getMachine()
    {
        try{
            $asset = Asset::all();
 
             if(!$asset){
                 throw new Exception("Machine not found");
             }else{   
 
            $asset=DB::table('assets')->select('id','assetName')->get();
                 
                $response = [
                    'success' => true,
                    'data' => $asset,
                    'status' => 201
                ];
                $status = 201;   
            }

 
        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;
 
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
         
        return response($response, $status);    
    }


    //Retrving All Vendor
    public function getVendor()
    {
        try{
            $Vendor = Vendor::all();

            if(!$Vendor){
                throw new Exception("VendorData not found");
            }else{   

                $Vendor=DB::table('vendors')->select('vendors.*','id as vendorId','vendorName')->get();

                $response = [
                    'success' => true,
                    'data' => $Vendor,
                    'status' => 201
                ];
                $status = 201;   
            }

        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
           return response($response, $status);    
    }
    
    //Fetch The VENDOR data
    public function getVendorData($id)
    {
        try{
            $VendorData = Vendor::find($id);

            if(!$VendorData){
                throw new Exception("VendorData not found");

            }else{   
                
                $VendorData = DB::table('vendors')
                    ->where('id','=',$id)
                    ->select('vendors.*','contactNo','email','address')
                    ->get();

                $response = [
                    'success' => true,
                    'data' => $VendorData,
                    'status' => 201
                ];
                $status = 201;   
            }


        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }

    //to get allocated departments
    public function getAllocatedDepartment()
    {
        try{
            $department = Allocation::all();

            if(count($department)<=0){
                throw new Exception("Departments not available");

            }else{   
                $department = DB::table('allocations')
                        ->distinct('department')
                        ->join('departments','departments.id','=','allocations.department')
                        ->select('allocations.department','departments.department_name as departmentName')
                        ->get();
                
                $response = [
                    'success' => true,
                    'data' => $department,
                    'status' => 201
                ];
                $status = 201;   
            }


        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }


    //to get allocated sections
    public function getAlloactedSection( $id)
    { 
       try{ 
            $section = DB::table('allocations')
                 ->distinct('sections') 
                 ->join('sections','sections.id','=','allocations.section')    
                 ->where('allocations.department','=',$id)
                 ->select('allocations.section','sections.section as sectionName')
                 ->get();
        
            if(count($section)<=0){
                throw new Exception("Sections not available");

            }else{
                
                $response = [
                    'success' => true,
                    'data' => $section         
                ];
                $status = 201;   
            }

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
        
       //To get alloacted assettypes
       public function getAlloactedAssetType( $id)
       { 
           try{ 
               $assetType = DB::table('allocations')
                    ->distinct('assetType')
                    ->where('allocations.section','=',$id)
                    ->join('assettypes','assettypes.id','=','allocations.assetType')  
                    ->select('allocations.assetType','assettypes.assetType as assetTypeName')
                    ->get();
           
               if(count($assetType)<=0){
                   throw new Exception("AssetType not available");
               }else{
   
                   $response = [
                       'success' => true,
                       'data' => $assetType         
                   ];
                   $status = 201;   
               }
   

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
}
