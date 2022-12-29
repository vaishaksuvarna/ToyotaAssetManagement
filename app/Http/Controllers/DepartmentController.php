<?php

namespace App\Http\Controllers;

use App\Models\department;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

class DepartmentController extends Controller
{
   

    public function store(Request $request)
    {
        try{

            $department = new department;

            $department->department_name= $request->department_name;
            $department->description= $request->description;

            $department->save();

            $response = [
                "message" => "Department Added Sucessfully!",
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

            $department = department::find($id);

            if(!$department){
                throw new Exception("department not found");
            }

            $department->department_name = $request->department_name;
            $department->description = $request->description;

            $department->save();

            $response = [       
               "message" =>' department Updated Successfully', 
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

            $department = department::find($id);

            if(!$department){
                throw new Exception("department not found");
            }else{
                $department->delete();
                $response = [          
                    "message" => " Department Deleted Sucessfully!",
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

            $department = department::all();

            if(!$department){
             throw new Exception("department not found");
            }

            $response=[
                "message" => "department List",
                "data" => $department
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
