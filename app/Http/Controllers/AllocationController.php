<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\users;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Exports\AllocationExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Exception;
use Str;
use Storage;
use Illuminate\Database\QueryException;

class AllocationController extends Controller
{
      // to get extension 
      public function getExtension($image)
      {
          $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          if ($extension == 'jpg') {
              $response = 'jpg';
  
          } elseif ($extension == 'png') {
              $response = 'png';
  
          } elseif ($extension == 'jpeg') {
              $response = 'jpeg';
              
          } elseif ($extension == 'csv') {
              $response = 'csv';
  
          } else {
  
              $extension1 = explode(";", $image)[0];
              $x = explode(".", $extension1)[3];
              if ($x == 'document') {
                  $response = "docx";
              } elseif ($x == 'sheet') {
                  $response = "xlsx";
              } else {
                  $response = $extension;
              }
          }
  
          return $response;
      }

    public function store(Request $request,$id)
    {
        try{
            $asset = Asset::find($id);

            $asset->assetNo  = $request->assetNo ;
            $asset->unitPlant = $request->unitPlant;
            $asset->line = $request->line;
            $asset->yearOfMfg = $request->yearOfMfg;
            $asset->countryOfMfg  = $request->countryOfMfg ;
            $asset->yearOfInstallTKAP = $request->yearOfInstallTKAP;
            $asset->usedOrNew = $request->usedOrNew;
            $asset->usagecode = $request->usagecode;
            $asset->assetWeight  = $request->assetWeight ;
            $asset->controlDepartment = $request->controlDepartment;
            $asset->userDepartment = $request->userDepartment;
            $asset->section = $request->section;

            $image = $request->assetImage;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $asset->assetImage = $imagePath;
            }

            $asset->mfgSlNo = $request->mfgSlNo;
            $asset->status = $request->status;
           
            $asset->save();

            $response = [
                'success' => true,
                'message' => "successfully updated",
                'status' => 201
            ];
            $status = 201;   

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 406;    

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406;             
        }    
     
        return response($response, $status);        
    }

    public function getUserId(Request $request){

        $name = $request->user;
        $data = DB::table('users')->where('user_name','=',$name)->first();
        $data = $data->id;

        return $data;
    }

    //update
    public function update(Request $request, $id)
    {
        try{
            $allocation = Allocation::find($id);          
            if(!$allocation){
                throw new Exception("data not found");
            }
            $allocation->department = $request->department;
            $allocation->section  = $request->section ;
            $allocation->assetType = $request->assetType;
            $allocation->assetName = $request->assetName;
            $allocation->userType = $request->userType;
            
            if($allocation->userType == 'empId'){
                $allocation->empId = $request->empId;
            }
            
            $allocation->user = $request->user; 

            if($allocation->userType == 'department'){
                $allocation->userDepartment = $request->userDepartment;
            }
            $allocation->position = $request->position;
            if($allocation->position == 'temporary'){
                $allocation->fromDate = $request->fromDate;
                $allocation->toDate = $request->toDate; 
            }
            if($allocation->position == 'permanent'){
                $allocation->fromDate = null;
                $allocation->toDate =  null;
            }
           
            $allocation->save();

            $response = [
                'success' => true,
                'message' => "details updated successfully",
            ];
            $status = 201;   
            

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
            ];            
            $status = 406;

        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }

    //Get the EmpID from Users table
    public function getEmpId()
    {
        try{

            $empId = Users::all();

            if(!$empId){
                throw new Exception("empId not found");
            }else{   

                $empId = DB::table('users')
                 ->select('id','employee_id')
                 ->get();
                
                $response = [
                    'data' => $empId,
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

    //Fetch the EmployeeName based on EmployeeId from Users table
    public function getEmpName($id)
    {
        try{
        
            $empName = DB::table('users')->where('employee_id','=',$id)->get();

            if(count($empName)<=0){
               throw new Exception("data not found");

            }else{
                $empName = DB::table('users')
                 ->where('employee_id','=',$id)
                 ->first('user_name');

                $empName1 =$empName->user_name;

                $response["empName"] = $empName1;
                $status = 200;
    
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

    //Fetch the UserName based on department from Users table
    public function getUser($id)
    {
        try{
            $empUser = DB::table('users') ->where('department','=',$id)->get();
            
            if(count($empUser)<=0){
                throw new Exception("data not found");

            }else{
    
                $empUser = DB::table('users')
                  ->where('department','=',$id)
                  ->select('id','user_name')
                  ->get();

                $response = [
                    'data' =>  $empUser
                ]; 
                $status = 201;
            }
        
        }catch(Exception $e){
            $response = [
                "error"=>$e->getMessage(),
            ];            
            $status = 406;
        
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
            ];
            $status = 406; 
        }
                
        return response($response, $status); 
       
    }

    //Showdata
    public function showData(Request $request)
    {
      try{    
            $fromDate =$request->fromDate;
            $toDate = $request->toDate;

            $result = DB::table('allocations')
                ->where('fromDate','>=',$fromDate) 
                ->where('toDate','<=', $toDate)
                ->join('departments','departments.id','=','allocations.department')
                ->join('assettypes','assettypes.id','=','allocations.assetType')
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('sections','sections.id','=','allocations.section')
                ->leftjoin('users as A','A.id','=','allocations.user')
                ->leftjoin('users as B','B.id','=','allocations.empId')
                ->select('allocations.*','departments.department_name as department',
                 'sections.section as section', 'assettypes.assetType as assetType',
                 'assets.assetName as assetName','assets.assetId as assetId',
                 'B.employee_id as empId','A.employee_name as user',
                 'departments.department_name as userDepartment', 'departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId',
                 'assets.id as assetNameId','A.id as usersId','B.id as EmpId','assets.id as assetId')
                ->get();
                
            if(!$result){
              throw new Exception("data not found");
            }

            $response=[
             "message" => "Allocations List",
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
                "message" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
        }
        return response($response,$status); 
    }
 
    //Downloading Allocation Data
    public function export(Request $request)
    {
        $fromDate =$request->fromDate;
        $toDate = $request->toDate;

        $query = DB::table('allocations')
            ->where('fromDate','>=',$fromDate) 
            ->where('toDate','<=', $toDate)
            ->join('departments','departments.id','=','allocations.department')
            ->join('assettypes','assettypes.id','=','allocations.assetType')
            ->join('assets','assets.id','=','allocations.assetName')
            ->join('sections','sections.id','=','allocations.section')
            ->join('users','users.id','=','allocations.user')
            ->select('allocations.id','departments.department_name as department',
             'sections.section as section', 'assettypes.assetType as assetType',
             'assets.assetName as assetName','assets.assetId as assetId',
             'users.user_name as user')
            ->get(); 
  
        return Excel::download(new AllocationExport($query), 'Allocation.xlsx');
    }


    public function showRequestReturnAsset()
    {
        try{

            $result = DB::table('allocations')
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('users','users.id','=','allocations.user')
                ->where('requestedReturnAsset','=',1)
                ->select('allocations.id','assets.assetId as assetId','assets.assetName as assetName','users.user_name as user')
                ->get();

                if(count( $result)<=0){
                    throw new Exception("data not found");
                  }
            $response=[
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


    public function updateRequestedReturnAsset(Request $request,$id)
    {
        try{  

            $update = Allocation::find($id);

            $update->requestedReturnAsset = $request->requestedReturnAsset;
            $update->returnedDate = $request->returnedDate;
            $update->returnStatus = "returned";
            $update->selfAssessmentStatus = null;
            $update->assetImage1 = null;
            $update->assetImage2 = null;

            $name = $update->assetName;
            $asset = DB::table('assets')->where('id','=',$name)->first();
                
            $asset = Asset::find($name);
            $asset->allocated = 0;
            $asset->save();

            $update->save();
        
            if(!$update){
                throw new Exception("data not found");
            }

            $response=[
                "data" => "updated successfully"
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

    public function viewReturnAsset()
    {
        try{

            $result = DB::table('allocations')
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('users','users.id','=','allocations.user')
                ->where('returnStatus','=','returned')
                ->select('allocations.id','assets.assetId as assetId','assets.assetName as   assetName', 'users.user_name as user','allocations.returnedDate')
                ->get();

                if(count( $result)<=0){
                    throw new Exception("data not found");
                }

            $response=[
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


    public function viewSelfAssessment()
    {
        try{

            $result = DB::table('allocations')
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('users','users.id','=','allocations.user')
                ->where('selfAssessmentStatus','!=',null)
                ->select('allocations.id','assets.assetId as assetId','assets.assetName as assetName','users.user_name as user'
                ,'allocations.selfAssessmentStatus')
                ->get();

            if(count( $result)<=0){
                throw new Exception("data not found");
            }
    
            $response=[
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


    

