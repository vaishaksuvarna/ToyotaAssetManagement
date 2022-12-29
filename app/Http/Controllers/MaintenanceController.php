<?php

namespace App\Http\Controllers;
use App\Models\Maintenance;
use App\Exports\MaintenanceExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Str;
use Storage;
use Exception;
use Illuminate\Database\QueryException;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
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
    
    public function store(Request $request)
    {
        try{
            $assetName = $request->assetName;
            $data = DB::table('maintenances')->where('assetName','=',$assetName)->get();

            if(count($data)>0){
                throw new Exception("this asset already exist");

            }else{

                $maintenance = new Maintenance;

                $maintenance->userName = $this->getUserName($request);
                $maintenance->maintenanceId = $request->maintenanceId;
                $maintenance->department = $request->department;
                $maintenance->section = $request->section;
                $maintenance->assetType = $request->assetType;
                $maintenance->assetName = $request->assetName;
                $maintenance->amcStatus = $request->amcStatus;
                $maintenance->warrantyStatus = $request->warrantyStatus;
                $maintenance->warrantyType = $request->warrantyType;
                $maintenance->insuranceStatus = $this->insuranceCheck($request);
                $maintenance->maintenanceType = $request->maintenanceType;
                $maintenance->severity = $request->severity;
                $maintenance->problemNote = $request->problemNote;
                
                $image = $request->bpImages1;  // your base64 encoded
                if($image){
                    $extension = $this->getExtension($image); 
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $maintenance->bpImages1 = $imagePath;
                }

                $image = $request->bpImages2;  // your base64 encoded
                if($image){
                    $extension = $this->getExtension($image);
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $maintenance->bpImages2 = $imagePath;
                }

                $image = $request->bpImages3;  // your base64 encoded
                if($image){
                    $extension = $this->getExtension($image);
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $maintenance->bpImages3 = $imagePath;
                }

                $image = $request->bpImages4;  // your base64 encoded
                if($image){
                    $extension = $this->getExtension($image);
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $maintenance->bpImages4 = $imagePath;
                }

                $maintenance->partsOrConsumable = $request->partsOrConsumable;
                $maintenance->affectedMachine = $request->affectedMachine;
                $maintenance->affectedManHours = $request->affectedManHours;

                $type1 = $request->type1;

                if($type1 == "shutDown"){
                    $maintenance->shutdownOrUtilization = "shutDown";
                }

                if($type1 == "machineUtilization"){
                    $maintenance->shutdownOrUtilization = $request->shutdownOrUtilization;
                    $maintenance->machineDetails = $request->machineDetails;
                }

                $type2 = $request->type2;

                if($type2 == "off"){
                    $maintenance->offOrUtilization = "off";
                }

                if($type2 == "manHoursUtilization"){
                    $maintenance->offOrUtilization = $request->offOrUtilization;
                    $maintenance->manHoursDetails = $request->manHoursDetails;
                }

                $maintenance->dateFrom = $request->dateFrom;
                $maintenance->dateTo  = $request->dateTo ;
                $maintenance->timeFrom = $request->timeFrom;
                $maintenance->timeTo = $request->timeTo;
                $maintenance->action = "pending";

                $maintenance->save();
            }   

            $response = [
                'success' => true,
                'message' => "successfully added",
                'status' => 201
            ];
            $status = 201; 
        
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

    public function getUserName(Request $request){

        $email = $request->header('email');
        $data = DB::table('users')->where('email','=',$email)->first();
        $data = $data->id;

        return $data;
    }

    public function insuranceCheck(Request $request){

        $assetName = $request->assetName;

        $data = DB::table('insurances')->where('assetName','=',$assetName)->get();
        $data1 = DB::table('insurances')->where('assetName','=',$assetName)->first();

        if(count($data)>0){
            $get1 = "Insurance is available";
            $get2 = $data1->periodFrom;
            $get3 = $data1->periodTo;

            $get = $get1." "."From: ".$get2." To: ".$get3 ;

        }else{
            $get = "Insurance not available";
        }

        return $get;

    }

    //shoow Maintenance data
    public function showData()
    {
      try{    
            $manintenance = DB::table('maintenances');

            if(!$manintenance){
               throw new Exception("manintenance not found");
            }else{
                $manintenance = DB::table('maintenances')
                    ->select('maintenances.*','users.user_name as userName','departments.department_name as department',
                    'sections.section as section', 'assettypes.assetType as assetType',
                    'assets.assetName as assetName')
                    ->join('users','users.id','=','maintenances.userName')
                    ->join('departments','departments.id','=','maintenances.department')
                    ->join('assettypes','assettypes.id','=','maintenances.assetType')
                    ->join('assets','assets.id','=','maintenances.assetName')
                    ->join('sections','sections.id','=','maintenances.section')
                    ->get();
                        
                $response=[
                    "message" => "manintenance List",
                    "data" => $manintenance
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


   public function getMaintenanceId()
   {
        $last = DB::table('maintenances')->latest( 'id')->first();

            if(!$last){
            $user =  "1";
            }else{
                $user = $last->id + 1;
            }
            $get = "ms-".$user;

            $response = [
                'success' => true,
                'data' =>  $get,
                'status' => 201
            ];
            $status = 201;  

        return Response($response, $status);    
    }

   public function updateAction(Request $request,$id)
   {
        try{

            $maintenance = Maintenance::find($id); 

            if(!$maintenance){
                throw new Exception("Data not found");
            }else{
            
                $action = $request->action;

                if($action == ' '){
                    $maintenance->action = $request->action;
                    $maintenance->rejectReason = $request->rejectReason;

                }else{
                    $maintenance->action = $request->action;
                    $maintenance->rejectReason = null;
                }

                $maintenance->save();
                $response = [
                    'success' => true,
                    'message' => "details updated successfully",
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

       return Response($response, $status);
    }

   public function updateClosedMaintenance(Request $request,$id)
   {
        try{

            $maintenance = Maintenance::find($id); 
            
            $maintenance->closedMaintenance = $request->closedMaintenance;

            $maintenance->save();
            $response = [
                'success' => true,
                'message' => "details updated successfully",
                'status' => 201
            ];
            $status = 201;  

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

        return Response($response, $status);
    }

   public function aprovedShowData()
   {
        try{
            
            $maintenance = DB::table('maintenances')
              ->where('action','=','aprove')
              ->join('assets','assets.id','=','maintenances.assetName')
              ->get();


                if(count($maintenance)<=0){
                    throw new Exception("data not found");
                } 

            $response = [
                'success' => true,
                'data' => $maintenance,
                'status' => 201
            ];
            $status = 201;       

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

      return Response($response, $status);
    }


    public function pendingShowData()
    {
        try{

            $maintenance = DB::table('maintenances')->where('action','=','pending')->get();
             
            
            if(!$maintenance){
                throw new Exception("data not found");
            } 

            $response = [
                'success' => true,
                'data' => $maintenance,
                'status' => 201
            ];
            $status = 201;       

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

       return Response($response, $status);
    }
 

    public function rejectedShowData()
    {
        try{

            $maintenance = DB::table('maintenances')->where('action','=','reject')->get();
            
            if(!$maintenance){
                throw new Exception("data not found");
            } 

            $response = [
                'success' => true,
                'data' => $maintenance,
                'status' => 201
            ];
            $status = 201;       

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
         
        return Response($response, $status);
    }

    public function showClosedMaintenance()
    {
        try{
            $maintenance = DB::table('maintenances')->where('closedMaintenance','!=','null')->get();
        
                if(!$maintenance){
                    throw new Exception("data not found");
                } 

            $response = [
                'success' => true,
                'data' => $maintenance,
                'status' => 201
            ];
            $status = 201;       

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
     
      return Response($response, $status);

    }


    public function showStatus($id)
    {
        try{
         
            $amc = DB::table('amcs')->where('assetName','=',$id)->get();
            $data["id"] = $id;
                if(count($amc)>0){
                    $data["amc"] = "Amc available";

                }else{
                    $data["amc"] = "Amc not available";
                }

            $warranty = DB::table('assets')->where('id','=',$id)->where('warrantyStartDate','!=',null)->first();
                if($warranty){
                    $data["warranty"] = "warranty available";
                    $data["startDate"] = $warranty->warrantyStartDate;
                    $data["endDate"] = $warranty->warrantyEndDate;

                }else{
                    $data["warranty"] = "warranty not available";
                }

            $data["warrantyType"] = "NA";

            $response = $data;
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

        $array["data"][] = $data;
        return Response($array,$status);
    }

    public function export()
    {
      $query = DB::table('maintenances')
            ->where('action','=','aproved')
            ->join('assets','assets.id','=','maintenances.assetName')
            ->select('maintenances.id','maintenanceId','maintenanceType',
             'assets.assetName as assetName','severity','problemNote','dateFrom','dateTo','timeFrom','timeTo')
            ->get();

        return Excel::download(new MaintenanceExport($query), 'Maintenance.xlsx');
    }
}   