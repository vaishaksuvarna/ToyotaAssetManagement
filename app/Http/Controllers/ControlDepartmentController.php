<?php

namespace App\Http\Controllers;

use App\Models\controlDepartment;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class ControlDepartmentController extends Controller
{
    public function store(Request $request)
    {
        try{
            $data = DB::table('control_departments')->where('controlDepartment','=',$request->controlDepartment)->get();

            if(count($data)>0){
                throw new Exception("this controlDepartment already exist");

            }else{
                $controlDepartment = new controlDepartment;

                $controlDepartment->controlDepartment= $request->controlDepartment;
                $controlDepartment->description = $request->description;

                $controlDepartment->save();

                $response = [
                    "message" => "controlDepartment Added Sucessfully!",
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


    public function update(Request $request,$id)
    {
        try{
            $controlDepartment = controlDepartment::find($id);

            if(!$controlDepartment){
                throw new Exception("controlDepartment not found");

            }else{

                $controlDepartment->controlDepartment = $request->controlDepartment;
                $controlDepartment->description = $request->description;

                $controlDepartment->save();

                $response = [       
                "message" =>' controlDepartment Updated Successfully', 
                "status" => 200
                ];
                $status = 200;  
            }

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
            $controlDepartment = controlDepartment::find($id);

            if(!$controlDepartment){
                throw new Exception("controlDepartment not found");

            }else{
                $controlDepartment->delete();
                $response = [          
                    "message" => " controlDepartment Deleted Sucessfully!",
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
            $controlDepartment = controlDepartment::all();

            if(count($controlDepartment)<=0){
                throw new Exception("controlDepartment not available");
            }

            $response=[
                "message" => "controlDepartment List",
                "data" => $controlDepartment
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
