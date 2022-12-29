<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use Illuminate\Http\Request;
use App\Exports\untagAssetExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Database\QueryException;
use DB;

class UntagAssetController extends Controller
{
    public function update(Request $request,$id)
    {
        try{  

            $data = DB::table('allocations')->where('assetName','=',$id)->get();
            
            if(count($data)<=0){
                throw new Exception("This asset is not allocated to Untag");

            }else{
                $get = DB::table('allocations')->where('assetName','=',$id)->first();
                $get1 = $get->id;
                $untag = Allocation::find($get1);

                $untag->reasonForUntag = $request->reasonForUntag;
                $untag->tag = $request->tag;

                $untag->save();

                $response = [
                    "message" => "untagged successfuly",
                    "status" => 200
                ];
                $status = 200;
            }
        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
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


     public function untagUpdate(Request $request,$id)
    {
        try{
            $data = DB::table('allocations')
             ->where('id','=',$id)
             ->get();
       
            if(count($data)<=0){
                throw new Exception("data not found");

            }else{
                $get =DB::table('allocations')->where('id','=',$id)->first();
                $get = $get->id;

                $untag = Allocation::find($get);

                $untag->department = $request->department;
                $untag->section  = $request->section ;
                $untag->assetType = $request->assetType;
                $untag->assetName = $request->assetName;
                $untag->userType = $request->userType;
                
                if($untag->userType == 'empId'){
                    $untag->empId = $request->empId;
                }
                
                $untag->user = $request->user; 

                if($untag->userType == 'department'){
                    $allocation->userDepartment = $request->userDepartment;
                }
                $untag->position = $request->position;
                if($untag->position == 'temporary'){
                    $untag->fromDate = $request->fromDate;
                    $untag->toDate = $request->toDate; 
                }
                if($untag->position == 'permanent'){
                    $untag->fromDate = null;
                    $untag->toDate =  null;
                }
                $untag->reasonForUntag = null;
                $untag->tag = null;

                $untag->save();
            }
                

            $response = [
                'success' => true,
                'message' => "details updated successfully",
            ];
            $status = 201;   
            

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

    public function showData(Request $request)
    {
      try{    
            $fromDate =$request->fromDate;
            $toDate = $request->toDate;

            $result = DB::table('allocations')
                ->where('fromDate','>=',$fromDate) 
                ->where('toDate','<=', $toDate)
                ->where('reasonForUntag','!=',"null")
                ->join('departments','departments.id','=','allocations.department')
                ->join('sections','sections.id','=','allocations.section')
                ->join('assettypes','assettypes.id','=','allocations.assetType')
                ->join('assets','assets.id','=','allocations.assetName')
                ->leftjoin('users as A','A.id','=','allocations.user')
                ->leftjoin('users as B','B.id','=','allocations.empId')
                ->select('allocations.*','departments.department_name as department',
                  'sections.section as  section','assettypes.assetType as assetType',
                  'assets.assetName as assetName','A.user_name as user',
                  'departments.department_name as userDepartment','B.employee_id as empId','departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId','assets.id as assetNameId','A.id as userId')  
                ->get();
                    
                    if(count($result)<=0){
                        throw new Exception("data not found");
                    }
            $response=[
             "message" => "UnTag Assets List",
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

    public function export(Request $request)
    {
        $fromDate =$request->fromDate;
        $toDate = $request->toDate;

        $query = DB::table('allocations')
            ->where('fromDate','>=',$fromDate) 
            ->where('toDate','<=', $toDate)
            ->where('reasonForUntag','!=',"null")
            ->join('assets','assets.id','=','allocations.assetName')
            ->join('sections','sections.id','=','allocations.section')
            ->join('users','users.id','=','allocations.user')
            ->select('allocations.id','sections.section as section', 
             'assets.assetName as assetName','assets.assetId as assetId',
             'users.employee_name as user')
            ->get();
       
        return Excel::download(new untagAssetExport($query), 'UnTagAssets.xlsx');
    }
}
