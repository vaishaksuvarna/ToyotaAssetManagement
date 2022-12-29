<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class SectionController extends Controller
{
    
    public function store(Request $request)
    {
        try{
            $section = new section;
            $section->department= $request->department;
            $section->section= $request->section;

            $section->save();
            
            $response = [
                "message" => "section Added Sucessfully!",
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
            $section = section::find($id);

            if(!$section){
                throw new Exception("section not found");
            }

            $section->department= $request->department;
            $section->section= $request->section;

            $section->save();

            $response = [       
               "message" =>' Section Updated Successfully', 
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
            $section = section::find($id);
            if(!$section){
                throw new Exception("section not found");
            }else{
                $section->delete();
                $response = [          
                    "message" => " section Deleted Sucessfully!",
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

            $result = DB::table('sections')
                ->join('departments','departments.id','=','sections.department')
                ->select('sections.*','departments.department_name as department','departments.id as departmentId')
                ->get();
            if(!$result){
                throw new Exception("section not found");
            }
            $response=[
              "message" => "section List",
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
