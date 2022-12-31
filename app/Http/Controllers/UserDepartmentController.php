<?php

namespace App\Http\Controllers;

use App\Models\userDepartment;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class UserDepartmentController extends Controller
{
    public function store(Request $request)
    {
        try{
            $data = DB::table('user_departments')->where('userDepartment','=',$request->userDepartmentName)->get();

            if(count($data)>0){
                throw new Exception("this userDepartment already exist");

            }else{
                $userDepartment = new userDepartment;

                $userDepartment->userDepartment= $request->userDepartment;
                $userDepartment->description = $request->description;

                $userDepartment->save();

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
            $userDepartment = userDepartment::find($id);

            if(!$userDepartment){
                throw new Exception("userDepartment not found");

            }else{

                $userDepartment->userDepartment= $request->userDepartment;
                $userDepartment->description = $request->description;

                $userDepartment->save();

                $response = [       
                "message" =>' userDepartment Updated Successfully', 
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
            $userDepartment = userDepartment::find($id);

            if(!$userDepartment){
                throw new Exception("userDepartment not found");

            }else{
                $userDepartment->delete();
                $response = [          
                    "message" => " userDepartment Deleted Sucessfully!",
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
            $userDepartment = userDepartment::all();

            if(count($userDepartment)<=0){
                throw new Exception("userDepartment not available");
            }

            $response=[
                "message" => "userDepartment List",
                "data" => $userDepartment
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
