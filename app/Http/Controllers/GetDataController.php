<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\department;
use App\Models\section;
use App\Models\Allocation;
use App\Models\assettype;
use App\Models\Vendor;
use App\Models\Asset;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class GetDataController extends Controller
{
    //Retrving All Departments  
    public function getDepartment()
    {
        try{
            $department = department::all();

            if(!$department){
                throw new Exception("department not found");
            }else{   

                $department=DB::table('departments')->select('id','department_name')->get();
                
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
