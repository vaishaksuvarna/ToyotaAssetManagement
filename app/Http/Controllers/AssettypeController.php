<?php

namespace App\Http\Controllers;

use App\Models\assettype;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class AssettypeController extends Controller
{
    public function store(Request $request)
    {
        try{
            
            $assettype = new assettype;
            $assettype->department= $request->department;
            $assettype->section= $request->section;
            $assettype->assetType= $request->assetType;

            $assettype->save();

            $response = [
                "message" => "Asset Type Added Sucessfully!",
                "status" => 200
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


    public function update(Request $request,$id)
    {
        try{

            $assettype = assettype::find($id);

            if(!$assettype){
                throw new Exception("Asset Type not found");
            }
            $assettype->department= $request->department;
            $assettype->section= $request->section;
            $assettype->assetType= $request->assetType;

            $assettype->save();

            $response = [       
               "message" =>'Asset Type Updated Successfully', 
               "status" => 200
            ];
            $status = 200;  

            }catch(Exception $e){ 
               $response = [
                   "message"=>$e->getMessage(),
                   "status" => 406
               ];            
               $status = 200;

            }catch(QueryException $e){
               $response = [
                   "error" => $e->errorInfo,
                   "status" => 406
               ];
               $status = 406; 
            }

            return response($response,$status);
    } 


    public function destroy($id)
    { 
        try{

            $assettype = assettype::find($id);

            if(!$assettype){
                throw new Exception("Asset Type not found");
            }else{

                $assettype->delete();
                $response = [          
                    "message" => " Asset Type Deleted Sucessfully!",
                    "status" => 200
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

    public function showData()
    {
        try{    

            $result = DB::table('assettypes')
                ->join('departments','departments.id','=','assettypes.department')
                ->join('sections','sections.id','=','assettypes.section')
                ->select('assettypes.*','departments.department_name as department','sections.section as section','departments.id as departmentId','sections.id as sectionId' )
                ->get();

            if(!$result){
              throw new Exception("Asset Type not found");
            }

            $response=[
             "message" => "Asset Type List",
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

}
