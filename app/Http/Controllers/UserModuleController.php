<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestService;
use App\Models\Allocation;
use Str;
use Storage;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class UserModuleController extends Controller
{
    public function showAsset(Request $request)
    {
        try{

            $user = $request->header('email');
            $data = DB::table('users')->where('email','=',$user)->first();
            $data = $data->id;

            $data1 = DB::table('allocations')->where('user','=',$data)->get();
            if(count($data1)<=0){
                  throw new Exception("No data available");
            }
            else{
                $get = DB::table('allocations')
                    ->where('user','=',$data)
                    ->join('assets','assets.id','=','allocations.assetName')
                    ->join('departments','departments.id','=','allocations.department')
                    ->join('sections','sections.id','=','allocations.section')
                    ->join('assettypes','assettypes.id','=','allocations.assetType')
                    ->select('allocations.*', 'sections.section as section', 'departments.department_name as department',
                    'assettypes.assetType as assetType','assets.assetName as assetName','assets.manufacturer as manufacturer',
                    'assets.assetModel as assetModel','assets.warrantyStartDate as warrantyStartDate','assets.warrantyEndDate as warrantyEndDate')
                    ->get();
            }

            $response = [
                "data" => $get,
                "status" => 200
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

    
    public function store(Request $request)
    {
        try{
            $service = new RequestService;

            $service->userName = $this->getUserName($request);
            $service->assetName = $request->assetName;
            $service->amcStatus = $request->amcStatus;
            $service->warrantyStatus  = $request->warrantyStatus;
            $service->insuranceStatus = $request->insuranceStatus;
            $service->problem = $request->problem;
            $service->problemNote  = $request->problemNote;
            $service->problemRemark = $request->problemRemark;

            $image = $request->image1;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $service->image1 = $imagePath;
            }

            $image = $request->image2;  // your base64 encoded
            if($image){
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $service->image2 = $imagePath;
            }
            $service->save();

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

    public function viewServiceRequest(Request $request)
    {
        try{  

            $email = $request->header('email');
            $user = DB::table('users')->where('email','=',$email)->first();
            $user1 = $user->id;

            $data = DB::table('request_services')
                ->where('userName','=',$user1)
                ->select('request_services.*','users.user_name as userName','departments.department_name as department',
                    'assettypes.assetType as assetType','sections.section as section','assets.assetName as assetName')
                ->join('users','users.id','=','request_services.userName')
                ->join('assets','assets.id','=','request_services.assetName')
                ->join('departments','departments.id','=','assets.department')
                ->join('assettypes','assettypes.id','=','assets.assettype')
                ->join('sections','sections.id','=','assets.section')
                ->get();

                if(count($data)<=0){
                    throw new Exception("No data available");
                }
             
            $response = [
                "data" => $data,
                "status" => 200
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

    public function getAssetName(Request $request)
    {
        try{

            $user = $request->header('email');
            $data = DB::table('users')->where('email','=',$user)->first();
            $data = $data->id;

            $data1 = DB::table('allocations')->where('user','=',$data)->get();
            if(count($data1)<=0){
                throw new Exception("No data available");
            }
            else{
                $get = DB::table('allocations')
                ->where('user','=',$data)
                ->join('assets','assets.id','=','allocations.assetName')
                ->select('assets.id','assets.assetName as assetName',)
                ->get();
            }

            $response = [
                "data" => $get,
                "status" => 200
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

    public function showStatus($id)
    {
        try{
         
            $amc = DB::table('amcs')->where('assetName','=',$id)->get();
            $number = mt_rand(1, 900);

                if(count($amc)>0){
                    $data["id"] = $number;
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

            $insurance = DB::table('insurances')->where('assetName','=',$id)->get();

            if(count($insurance)<=0){
                $data["insurance"] = "insurance not available";

            }else{
                $data["insurance"] = "insurance is available";
            }

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


    public function showReturnAsset(Request $request)
    {

        try{

            $user = $request->header('email');
            $data = DB::table('users')->where('email','=',$user)->first();
            $data = $data->id;

            $data1 = DB::table('allocations')->where('user','=',$data)->get();
            if(count($data1)<=0){
                throw new Exception("No data available");
            }

            else{
            $get = DB::table('allocations')
                ->whereRaw("returnStatus IN ('pending','inUse')")
                //->where('returnStatus','=','pending')
                //->where('returnStatus','=','inUse')
               
                ->join('assets','assets.id','=','allocations.assetName')
                ->join('departments','departments.id','=','allocations.department')
                ->join('sections','sections.id','=','allocations.section')
                ->join('assettypes','assettypes.id','=','allocations.assetType')
                ->select('allocations.id','assets.assetName as assetName','returnStatus')
                ->get();
            }

            $response = [
                "data" => $get,
                "status" => 200
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


    public function updateReturnAsset(Request $request,$id)
    {
        try{  

            $update = Allocation::find($id);

            if(!$update){
                throw new Exception("data not found");
            }

            $update->requestedReturnAsset = $request->requestedReturnAsset;
            $update->returnReason = $request->returnReason;
            $update->returnStatus = "pending";

            $update->save();
            

            $response = [
                "data" => "updated successfully",
                "status" => 200
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


    public function updateSelfAssessment(Request $request,$id)
    {
        try{  

            $update = Allocation::find($id);

            if(!$update){
                throw new Exception("data not found");
            }

            $update->selfAssessmentStatus = $request->selfAssessmentStatus;
            
            $image = $request->assetImage1;
                if($image){ // your base64 encoded
                    $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage'.'/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $update->assetImage1 = $imagePath;

                }

            $image = $request->assetImage2;
                if($image){ // your base64 encoded
                    $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1]; 
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage'.'/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $update->assetImage2 = $imagePath;

                }

            $update->save();
            
            $response = [
                "data" => $update,
                "status" => 200
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

}
