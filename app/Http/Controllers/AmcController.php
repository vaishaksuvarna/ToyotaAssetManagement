<?php

namespace App\Http\Controllers;

use App\Models\Amc;
use Illuminate\Http\Request;
use App\Exports\AmcExport;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Exception;
use Illuminate\Database\QueryException;
use Storage;
use Str;
use DB;

class AMCController extends Controller
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
            $data = DB::table('amcs')->where('assetName','=',$assetName)->get();

            if(count($data)>0){
                throw new Exception("this asset already exist");

            }else{
                $amc = new Amc;

                $amc->vendorName = $request->vendorName;
                $amc->periodFrom = $request->periodFrom;
                $amc->periodTo = $request->periodTo;
                $amc->premiumCost = $request->premiumCost;

                // Amc document
                $image = $request->amcDoc;
                if($image){ // your base64 encoded
                    $extension = $this->getExtension($image);  
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $amc->amcDoc = $imagePath;
                }
            
                $amc->servicePattern = $request->servicePattern;
                $service = $request->servicePattern;

                if($service == 1 )
                {
                    $amc->service1 = $this->service1($request);
                }

                if($service == 2)
                {
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                }

                if($service == 3)
                {
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                    $amc->service3 = $this->service3($request);
                }

                if($service == 4)
                {
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                    $amc->service3 = $this->service3($request);
                    $amc->service4 = $this->service4($request);
                }

                if($service == 5)
                {
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                    $amc->service3 = $this->service3($request);
                    $amc->service4 = $this->service4($request);
                    $amc->service5 = $this->service5($request);
                }

                $amc->department = $request->department;
                $amc->section = $request->section;
                $amc->assetType = $request->assetType;
                $amc->assetName = $request->assetName;
            
                $amc->save();
                $response = [
                    'success' => true,
                    'message' => "successfully added",
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

    // to merge 3 inputs into one
    public function service1(Request $request)
    {
        $s1DateFrom = $request->s1DateFrom;
        $s1DateTo = $request->s1DateTo;
        $s1runHours = $request->s1runHours;
        $service1 = $s1DateFrom."+".$s1DateTo."+".$s1runHours;

        return $service1;

    }
    public function service2(Request $request)
    {
        $s2DateFrom = $request->s2DateFrom;
        $s2DateTo = $request->s2DateTo;
        $s2runHours = $request->s2runHours;
        $service2 = $s2DateFrom."+".$s2DateTo."+".$s2runHours;

        return $service2;
    }

    public function service3(Request $request)
    {
        $s3DateFrom = $request->s3DateFrom;
        $s3DateTo = $request->s3DateTo;
        $s3runHours = $request->s3runHours;
        $service3 = $s3DateFrom."+".$s3DateTo."+".$s3runHours;

        return $service3;
    }

    public function service4(Request $request)
    {
        $s4DateFrom = $request->s4DateFrom;
        $s4DateTo = $request->s4DateTo;
        $s4runHours = $request->s4runHours;
        $service4 = $s4DateFrom."+".$s4DateTo."+".$s4runHours;

        return $service4;
    }

    public function service5(Request $request)
    {
        $s5DateFrom = $request->s5DateFrom;
        $s5DateTo = $request->s5DateTo;
        $s5runHours = $request->s5runHours;
        $service5 = $s5DateFrom."+".$s5DateTo."+".$s5runHours;

        return $service5;
    }

    //update
    public function update(Request $request,$id)
    {
        try{
            $amc = Amc::find($id);          
            if(!$amc){
                throw new Exception("AMC not found");
            }

            $amc->vendorName = $request->vendorName;
            $amc->periodFrom = $request->periodFrom;
            $amc->periodTo = $request->periodTo;
            $amc->premiumCost = $request->premiumCost;

            // Amc document
            $image = $request->amcDoc;
            if($image){ // your base64 encoded
                $extension = $this->getExtension($image);  
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage/app/public/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $amc->amcDoc = $imagePath;

            }
            
            $amc->department = $request->department;
            $amc->section = $request->section;
            $amc->assetType = $request->assetType;
            $amc->assetName = $request->assetName;
        
            $amc->save();
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

    //destroy
    public function destroy(Amc $amc, $id)
    {
        try{

            $amc = Amc::find($id);
             
            if(!$amc){
                throw new Exception("amc not found");
            }else{
                $amc->delete();
                $response = [
                    "message" => "data deleted successfully",
                    "status" => 200
                ];
                $status = 200;                   
            }
 
        }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404;     
        }
 
        return response($response,$status);
    }  

    //showData
    public function showData()
    {
      try{    
            $amc = DB::table('amc');

            if(!$amc){
               throw new Exception("amc not found");
            }else{
                $amc = DB::table('amcs')
                    ->join('vendors','vendors.id','=','amcs.vendorName')
                    ->join('departments','departments.id','=','amcs.department')
                    ->join('sections','sections.id','=','amcs.section')
                    ->join('assettypes','assettypes.id','=','amcs.assetType')
                    ->join('assets','assets.id','=','amcs.assetName')
                    ->select('amcs.*','vendors.vendorName as vendorName','vendors.id as vendorId','periodFrom','periodTo','servicePattern','departments.department_name as department',
                      'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName','departments.id as departmentId','sections.id as sectionsId', 'assettypes.id as assetTypesId',
                      'assets.id as assetNameId')
                    ->get();
                        
                $response=[
                    "message" => "Annual maintainence contract List",
                    "data" => $amc
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

    //Update  Service Dates
    public function updateServiceDate(Request $request, $id)
    {
        try{    
              
            $amc = Amc::find($id); 

            if(!$amc){
                throw new Exception("Data not found");
            }

                if($request->s1DateFrom != null){
                    $amc->service1 = $this->service1($request);
                }

                if($request->s2DateFrom != null){
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                }

                if($request->s3DateFrom != null){
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                    $amc->service3 = $this->service3($request);
                }

                if($request->s4DateFrom != null){
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                    $amc->service3 = $this->service3($request);
                    $amc->service4 = $this->service4($request);
                }

                if($request->s5DateFrom != null){
                    $amc->service1 = $this->service1($request);
                    $amc->service2 = $this->service2($request);
                    $amc->service3 = $this->service3($request);
                    $amc->service4 = $this->service4($request);
                    $amc->service5 = $this->service5($request);  
                }
            
            $amc->save();

            $response=[
                "message" => "details updated successfully",
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

    //view amc Data by ID
    public function showData1(Request $request, $id)
    {
        try{

            $data = DB::table('amcs')
                ->select('*','vendors.vendorName as vendorName','assets.assetName as assetName')
                ->join('vendors','vendors.id','=','amcs.vendorName')
                ->join('assets','assets.id','=','amcs.assetName')
                ->where('amcs.id','=',$id)                
                ->get();

            if(count($data)>0){
                $vendorName = $data[0]->vendorName;
                $assetName = $data[0]->assetName;
                $periodFrom = $data[0]->periodFrom;
                $periodTo = $data[0]->periodTo;
                $servicePattern = $data[0]->servicePattern;

                $rawData = array();
                $rawData["id"] = $id;
                $rawData["vendorName"] = $vendorName;
                $rawData["assetName"] = $assetName;
                $rawData ["periodFrom"] = $periodFrom;
                $rawData ["periodTo"] = $periodTo;
                $rawData["servicePattern"] = $servicePattern;

                $data1 = array();
                for($i=1;$i<=number_format($servicePattern);$i++){
                    
                    if($i == 1){
                        $serviceSplit1 = explode("+",$data[0]->service1);

                        $date["s1startDate"] = $serviceSplit1[0];
                        $date["s1endDate"] = $serviceSplit1[1];
                        $date["s1runHours"] = $serviceSplit1[2];
                    }

                    if($i == 2){
                        $serviceSplit2 = explode("+",$data[0]->service2);
                        $date["s2startDate"] = $serviceSplit2[0];
                        $date["s2endDate"] = $serviceSplit2[1];
                        $date["s2runHours"] = $serviceSplit2[2];
                    }

                    if($i == 3){
                        $serviceSplit3 = explode("+",$data[0]->service3);
                        $date["s3startDate"] = $serviceSplit3[0];
                        $date["s3endDate"] = $serviceSplit3[1];
                        $date["s3runHours"] = $serviceSplit3[2];
                    }

                    if($i == 4){
                        $serviceSplit4 = explode("+",$data[0]->service4);
                        $date["s4startDate"] = $serviceSplit4[0];
                        $date["s4endDate"] = $serviceSplit4[1];
                        $date["s4runHours"] = $serviceSplit4[2];
                    }

                    if($i == 5){
                        $serviceSplit5 = explode("+",$data[0]->service5);
                        $date["s5startDate"] = $serviceSplit5[0];
                        $date["s5endDate"] = $serviceSplit5[1];
                        $date["s5runHours"] = $serviceSplit5[2];
                    }
                }

                $rawData["serviceList"] = $date;
                $response["data"][] = $rawData;
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

      return Response($response,$status);
    }

    // to display service date
    public function showService(Request $request,$id)
    {
        try{
      
            $data = DB::table('amcs')
                ->select('*','vendors.vendorName as vendorName','assets.assetName as assetName')
                ->join('vendors','vendors.id','=','amcs.vendorName')
                ->join('assets','assets.id','=','amcs.assetName')
                ->where('amcs.assetName','=',$id)                
                ->get();

            if(count($data)>0){
               // $id = $data[0]->id;
                $vendorName = $data[0]->vendorName;
                $assetName = $data[0]->assetName;
                $servicePattern = $data[0]->servicePattern;

                $rawData = array();
                $rawData["id"] = $id;
                $rawData["vendorName"] = $vendorName;
                $rawData["assetName"] = $assetName;
                $rawData["servicePattern"] = $servicePattern;

                $data1 = array();
                for($i=1;$i<=number_format($servicePattern);$i++){
                    
                    if($i == 1){
                        $serviceSplit1 = explode("+",$data[0]->service1);
                        $rawData["s1startDate"] = $serviceSplit1[0];
                        $rawData["s1endDate"] = $serviceSplit1[1];
                        $rawData["s1runHours"] = $serviceSplit1[2];
                    }

                    if($i == 2){
                        $serviceSplit2 = explode("+",$data[0]->service2);
                        $rawData["s2startDate"] = $serviceSplit2[0];
                        $rawData["s2endDate"] = $serviceSplit2[1];
                        $rawData["s2runHours"] = $serviceSplit2[2];
                    }

                    if($i == 3){
                        $serviceSplit3 = explode("+",$data[0]->service3);
                        $rawData["s3startDate"] = $serviceSplit3[0];
                        $rawData["s3endDate"] = $serviceSplit3[1];
                        $rawData["s3runHours"] = $serviceSplit3[2];
                    }

                    if($i == 4){
                        $serviceSplit4 = explode("+",$data[0]->service4);
                        $rawData["s4startDate"] = $serviceSplit4[0];
                        $rawData["s4endDate"] = $serviceSplit4[1];
                        $rawData["s4runHours"] = $serviceSplit4[2];
                    }

                    if($i == 5){
                        $serviceSplit5 = explode("+",$data[0]->service5);
                        $rawData["s5startDate"] = $serviceSplit5[0];
                        $rawData["s5endDate"] = $serviceSplit5[1];
                        $rawData["s5runHours"] = $serviceSplit5[2];
                    }
                }

                // $data[] = $rawData;
                $response["data"][] = $rawData;
                $status = 200; 

            }else{
                throw new Exception("data not found");
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

       return Response($response,$status);
    }
    
    //To display the Service Due(End) Date
    public function serviceDue(Request $request,$id)
    {
        try{    

            $periodFrom =$request->periodFrom;
            $periodTo = $request->periodTo;

            $result = DB::table('amcs')
                    ->where('amcs.assetType','=',$id)
                    ->where('periodFrom','>=',$periodFrom) 
                    ->where('periodTo','<=', $periodTo)
                    ->join('vendors','vendors.id','=','amcs.vendorName')
                    ->join('assets','assets.id','=','amcs.assetName')
                    ->select('amcs.id','vendors.vendorName as vendorName',
                     'assets.assetName as assetName','periodTo as serviceDueDate')
                    ->get();

                if(count($result)<=0){
                 throw new Exception("data not found");
                }

            $response=[
             "message" => "Service Due Date List",
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
    
    //To Display The Amc data which are closed to due date
    public function viewAmcRenewal()
    {
        try{

            $result=DB::table('amcs')
                    ->whereBetween('periodTo', [now(), now()->addDays(7)])
                    // ->join('departments','departments.id','=','amcs.department')
                    // ->join('assets','assets.id','=','amcs.assetName')
                    // ->select( 'amcs.id','departments.department_name as department', 
                    //  'assets.assetName as  machineName',
                    //  'periodFrom as amcStartDate','periodTo as amcEndDate')
                    ->get();
                
                if(count($result)<=0){
                    throw new Exception("data not found");
                } 
                
            $response = [
                "message" => "View AMC Renewal List",
                'data' => $result,
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

    //Update The Amc (Date & Service) Renewal
    public function renewalAmc(Request $request,$id)
    {
        try{    
             
            $amc = Amc::find($id); 

            if(!$amc){
                throw new Exception("Data not found");
            }
                
            $amc->periodFrom = $request->periodFrom;
            $amc->periodTo = $request->periodTo;
            $amc->servicePattern = $request->servicePattern;
            
            $service = $request->servicePattern;

            if($service == 1)
            {
                $amc->service1 = $this->service1($request);
            }

            if($service == 2)
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
            }

            if($service == 3)
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
                $amc->service3 = $this->service3($request);
            }

            if($service == 4)
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
                $amc->service3 = $this->service3($request);
                $amc->service4 = $this->service4($request);
            }

            if($service == 5)
            {
                $amc->service1 = $this->service1($request);
                $amc->service2 = $this->service2($request);
                $amc->service3 = $this->service3($request);
                $amc->service4 = $this->service4($request);
                $amc->service5 = $this->service5($request);
            }
            $amc->save();

            $response=[
                "message" => "Updated Sucessfully",
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

    //Downloading Amc Report
    public function export()
    {
      $query = DB::table('amcs')
        ->join('vendors','vendors.id','=','amcs.vendorName')
        ->join('departments','departments.id','=','amcs.department')
        ->join('sections','sections.id','=','amcs.section')
        ->join('assettypes','assettypes.id','=','amcs.assetType')
        ->join('assets','assets.id','=','amcs.assetName')
        ->select('amcs.id','vendors.vendorName as vendorName','periodFrom','periodTo','servicePattern','departments.department_name as department',
            'sections.section as section','assettypes.assetType as assetType','assets.assetName as assetName')
        ->get();
  
      return Excel::download(new AmcExport($query), 'Amc.xlsx');
    }

    public function download($id) 
    {
       
        $get = DB::table('amcs')->where('id','=',$id)->first('amcDoc');
        $url = $get->amcDoc;
        
        $filepath = public_path($url);
        return Response::download($filepath);

    }
}


