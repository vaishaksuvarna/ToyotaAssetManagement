<?php

namespace App\Http\Controllers;

use App\Models\requesterDepartment;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class RequesterDepartmentController extends Controller
{
    public function store(Request $request)
    {
        try{
            $data = DB::table('requester_departments')->where('requesterDepartment','=',$request->requesterDepartmentName)->get();

            if(count($data)>0){
                throw new Exception("this requesterDepartment already exist");

            }else{
                $requesterDepartment = new requesterDepartment;

                $requesterDepartment->requesterDepartment= $request->requesterDepartment;
                $requesterDepartment->description = $request->description;

                $requesterDepartment->save();

                $response = [
                    "message" => "Data Added Sucessfully!",
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
            $requesterDepartment = requesterDepartment::find($id);

            if(!$requesterDepartment){
                throw new Exception("requesterDepartment not found");

            }else{

                $requesterDepartment->requesterDepartment= $request->requesterDepartment;
                $requesterDepartment->description = $request->description;

                $requesterDepartment->save();

                $response = [       
                "message" =>' requesterDepartment Updated Successfully', 
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
            $requesterDepartment = requesterDepartment::find($id);

            if(!$requesterDepartment){
                throw new Exception("requesterDepartment not found");

            }else{
                $requesterDepartment->delete();
                $response = [          
                    "message" => " requesterDepartment Deleted Sucessfully!",
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
            $requesterDepartment = requesterDepartment::all();

            if(count($requesterDepartment)<=0){
                throw new Exception("requesterDepartment not available");
            }

            $response=[
                "message" => "requesterDepartment List",
                "data" => $requesterDepartment
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
