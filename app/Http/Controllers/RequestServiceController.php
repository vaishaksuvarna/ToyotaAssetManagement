<?php

namespace App\Http\Controllers;

use App\Models\RequestService;
use Illuminate\Http\Request;
use App\Exports\RequestServiceExport;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use Storage;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class RequestServiceController extends Controller
{


    public function showData()
    {
        try{
            $manintenance = DB::table('request_services')->get();

            if(count($manintenance)<=0){
                throw new Exception("requestServices not found");

            }else{

                $manintenance = DB::table('request_services')
                    ->select('request_services.*','users.user_name as userName','departments.department_name as department',
                    'assettypes.assetType as assetType','sections.section as section','assets.assetName as assetName')
                    ->join('users','users.id','=','request_services.userName')
                    ->join('assets','assets.id','=','request_services.assetName')
                    ->join('departments','departments.id','=','assets.department')
                    ->join('assettypes','assettypes.id','=','assets.assettype')
                    ->join('sections','sections.id','=','assets.section')
                    ->get();
            } 

            $response=[
                "message" => "manintenance List",
                "data" => $manintenance
            ];
            $status = 200; 


        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
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

    public function showData1($id)
    {
        try{
            $service = DB::table('request_services')->where('id','=',$id)->get();

            if(count($service)<=0){
                throw new Exception("No data available"); 

            }else{

                $service = DB::table('request_services')
                    ->select('request_services.*','users.user_name as userName','departments.department_name as department',
                    'assettypes.assetType as assetType','sections.section as section','assets.assetName as assetName')
                    ->where('request_services.id','=',$id)
                    ->join('users','users.id','=','request_services.userName')
                    ->join('assets','assets.id','=','request_services.assetName')
                    ->join('departments','departments.id','=','assets.department')
                    ->join('assettypes','assettypes.id','=','assets.assettype')
                    ->join('sections','sections.id','=','assets.section')
                    ->get();
            } 

            $response=[
                "message" => "service List",
                "data" => $service
            ];
            $status = 200; 


        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
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


    public function update(Request $request,$id)
    {
        try{
            $service = RequestService::find($id);

            if(!$service){
                throw new Exception("data not found");

            }else{
        
                $service->vendorName = $request->vendorName;
                $service->vendorEmail = $request->vendorEmail;
                $service->vendorAddress = $request->vendorAddress;
                $service->vendorPhone = $request->vendorPhone;
                $service->gstNo = $request->gstNo;
                $service->dateOrDay = $request->dateOrDay;

                if($service->dateOrDay == "date"){
                    $service->expectedDate = $request->expectedDate;
                    $service->expectedDay = null;
                }

                if($service->dateOrDay == "day"){
                $service->expectedDate = null;
                $service->expectedDay = $request->expectedDay;
                }
                $service->eWayBill  = $request->eWayBill ;
                $service->chargable = $request->chargable;
                $service->returnable = $request->returnable;
                $service->delivery = $request->delivery;
                $service->jobWork  = $request->jobWork ;
                $service->repair = $request->repair;
                $service->personName = $request->personName;
            }

            $service->save();

            $response=[
                "message" => "manintenance List",
                "data" => "details updated successfully"
            ];
            $status = 200;


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

   
    public function updateServiceStatus(Request $request, $id)
    {
        try{

            $data = DB::table('request_services')->where('id','=',$id)->first();
            $get = $data->id;

            $update = RequestService::find($get);
            
            $update->status = $request->status;
            $update->serviceStatus = $request->serviceStatus;

            
            $update->save();

            $response = [
                "data" => "status updated successfully!",
                "status" => 200
            ];
            $status = 200;

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

  

    public function export()
    {
      $query = DB::table('maintenances')
            ->select('maintenances.id','departments.department_name as department','sections.section as section','assets.assetName as assetName','amcStatus','warrantyStatus','insuranceStatus','problemNote','users.user_name as userName')
            ->join('users','users.id','=','maintenances.userName')
            ->join('departments','departments.id','=','maintenances.department')
            ->join('sections','sections.id','=','maintenances.section')
            ->join('assets','assets.id','=','maintenances.assetName')
            ->get(); 
    
        return Excel::download(new RequestServiceExport($query), 'RequestServices.xlsx');
    }


}
