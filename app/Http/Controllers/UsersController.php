<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Exception;
use Illuminate\Database\QueryException;


class UsersController extends Controller
{ 
    
    public function store(Request $request)
    {
        try{
            $users = new Users;
            $users->userType= $request->userType;
            $users->employee_id= $request->employee_id;
            $users->employee_name= $request->employee_name;
            $users->department= $request->department;
            $users->designation= $request->designation;
            $users->mobile_number= $request->mobile_number;
            $users->email= $request->email;
            $users->user_name= $request->user_name;
            $users->password= $request->password;

            $users->save();
            $response = [
                "message" => "User Added Sucessfully!",
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
     
    //Default EmpID
    public function empId()
    {
        $last = DB::table('users')->latest('id')->first();
        if(!$last){
           $emp = "1";
        }else{
            $emp = $last->id + 1;
        }
        $get = "emp-".$emp;

        $response = [
            'success' => true,
            'data' =>  $get,
            'status' => 201
        ];
        $status = 201;   

        return Response($response,$status);
    }


    public function update(Request $request,$id)
    {
        try{
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }
            
            $users->userType= $request->userType;
            $users->employee_id = $request->employee_id;
            $users->employee_name = $request->employee_name;
            $users->department= $request->department;
            $users->designation= $request->designation;
            $users->mobile_number= $request->mobile_number;
            $users->email= $request->email;
            $users->user_name= $request->user_name;
            $users->password= $request->password;

            $users->update();
           
            
            $response = [       
               "message" => 'user Updated Successfully', 
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
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }else{
                $users->delete();
                $response = [          
                    "message" => $users->user_name. " user Deleted Sucessfully!",
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

 
    public function loginUser(Request $request)
    {   
        $email = $request->email;
        $password = $request->password;
        $data = DB::table('users')->where('email','=',$email)->where('password','=',$password)->get();


        $users = users::where(['email' => $request->email])->first();
       
        if(!$users){    

            $response = [
                'error' => 'Entered email has not been registered. Please enter the registered email id',
                "status" => 401
            ];
            $status=401;
            
            }elseif($users['blocked']){

                $response = [
                    'message'=>"User is blocked, please contact admin",
                ];
                $status = 403; 
                            
            }elseif(!$users  = Users::where(['password' => $request->password])->first()){  
                
                $response = [
                    'error' => 'Entered password is invalid',
                    "status" => 401
                ];
                $status=401;
            
            }elseif(count($data)<=0){

            $response = [
                'error' => 'Entered email and password is invalid',
                    "status" => 401
                ];
            $status=401;  

        }elseif(count($data)>0){
                    
            $users = users::where('email', $request['email'])->firstOrFail();
            $token = $users->createToken('auth_token')->plainTextToken;
            $response = [
                'userDetails' =>[ 
                    'id' =>$users->id,
                    'username' => $users->user_name,
                    'email'=>$users->email,
                    'userRole'=>$users->userType
                ],
                'access_token' => $token, 
            ];
            $status = 200;    
                        
        }
        return response($response, $status);
    }

    public function logout(Request $request) 
    {
        try{
           if ($request->user()) { 
                $request->user()->tokens()->delete();
            }
            $response = [          
                "message" =>  " user Logout Sucessfully!",
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

    public function block($id)
    {
        try{
            $users = Users::find($id);
            if(!$users){
                throw new Exception("user not found");
            }else{
                $users->update(['blocked' => true]);
                $response = [
                    'message'=>"User is blocked Sucessfully!",
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
            $result = DB::table('users')
                ->join('departments','departments.id','=','users.department')
                ->select('users.*','departments.department_name as department','departments.id as departmentId')
                ->get();
            if(!$result){

             throw new Exception("user not found");
            }

            $response=[
                "message" => "Users List",
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