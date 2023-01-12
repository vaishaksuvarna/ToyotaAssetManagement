<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Vendor;
use App\Imports\AssetsImport;
use App\Exports\TemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use DB;
use Str;
use Storage;
use ZipArchive;

class AssetController extends Controller
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
            $asset = DB::table('assets')->where('assetName','=',$request->assetName)->get();

            if(count($asset)>0){
                throw new Exception("AssetName already exists!");

            }else{
                $asset = new Asset;
                    
                $asset->assetId = $this->autoAssetId($request);
                $asset->assetNo = $request->assetNo;
                $asset->projectName  = $request->projectName;
                $asset->requesterDepartment  = $request->requesterDepartment;
                $asset->unitPlant = $request->unitPlant;
                $asset->line = $request->line;
                $asset->component = $request->component;
                $asset->operationNo = $request->operationNo;
                $asset->assetName = $request->assetName;
                $asset->operationName = $request->operationName;
                $asset->equipmentType  = $request->equipmentType;
                $asset->dateOfRequest = $request->dateOfRequest;
                $asset->requesterName = $request->requesterName;
                $asset->yearOfMfg = "NA";
                $asset->countryOfMfg = "NA";
                $asset->yearOfInstallTKAP = "NA";
                $asset->usedOrNew = "NA";
                $asset->usagecode = "NA";
                $asset->assetWeight = "NA";
                $asset->controlDepartment  = "NA";
                $asset->userDepartment  = "NA";
                $asset->section = "NA";
                $asset->assetImage = "NA";
                $asset->mfgSlNo = "NA";
                $asset->status = "NA";
            }

            $asset->save();
            $response = [
                'success' => true,
                'message' => "successfully added",
                'status' => 201
            ];
            $status = 201;   
          

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status"=>406
            ];            
            $status = 406;
            
        }catch(QueryException $e){
            $response = [
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);        
    }

    //To generate default asset-id
    public function autoAssetId()
    {
        $last = DB::table('assets')->latest('id')->first();

        if(!$last){
           $user = "1";
        }else{
            $user = $last->id + 1;
        }
        $get = "asset-".$user;

        return $get;
    }


    //To generate default asset-id
    public function id()
    {
        
        $last = DB::table('assets')->latest()->first('id');
        $id = $last->id;
        $numlength = strlen((string)$id);
 
        if(!$last){
           $num["data"] = "0001";

        }elseif($numlength == 1){
            $num["data"] = "000".$id + 1;
        
        }elseif($numlength == 2){
            $num["data"] = "00".$id + 1;
        
        }elseif($numlength == 3){
            $num["data"] = "0".$id + 1;
        }
       
        return $num;
    }

    //asset update
    public function update(Request $request, $id)
    {
        try{
            $asset = Asset::find($id);          
            if(!$asset){
                throw new Exception("asset not found");
            }
            
            $asset->assetNo = $request->assetNo;
            $asset->projectName  = $request->projectName;
            $asset->requesterDepartment  = $request->requesterDepartment;
            $asset->unitPlant = $request->unitPlant;
            $asset->line = $request->line;
            $asset->component = $request->component;
            $asset->operationNo = $request->operationNo;
            $asset->assetName = $request->assetName;
            $asset->operationName = $request->operationName;
            $asset->equipmentType  = $request->equipmentType;
            $asset->dateOfRequest = $request->dateOfRequest;
            $asset->requesterName = $request->requesterName;
            $asset->yearOfMfg = $request->yearOfMfg;
            $asset->countryOfMfg = $request->countryOfMfg;
            $asset->yearOfInstallTKAP = $request->yearOfInstallTKAP;
            $asset->usedOrNew = $request->usedOrNew;
            $asset->usagecode = $request->usagecode;
            $asset->assetWeight = $request->assetWeight;
            $asset->controlDepartment  = $request->controlDepartment;
            $asset->userDepartment  = $request->userDepartment;
            $asset->section = $request->section;
            $asset->assetImage = $request->assetImage;
            $asset->mfgSlNo = $request->mfgSlNo;
            $asset->status = $request->status;

            $asset->update();
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
                "message" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
        
        return response($response, $status);    
    }
    
  
    //destroy
    public function destroy(Asset $asset, $id)
    {
        try{
            $asset = Asset::find($id);

            if(!$asset){
                throw new Exception("asset not found");
            }else{
                $asset->delete();
                $response = [
                    "message" => "asset deleted successfully",
                    "status" => 200
                ];
                $status = 200;                   
            }

        }catch(Exception $e){
            $response = [
                "message" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404;     
        }

        return response($response,$status);
    }  

    public function showData()
    {
        try{    
            $asset = DB::table('assets')->get();

            if(count($asset)<=0){
               throw new Exception("Asset details not available");

            }else{
                $asset = DB::table('assets')
                    ->join('units','units.id','=','assets.unitPlant')
                    ->join('lines','lines.id','=','assets.line')
                    // ->join('asset_masters','asset_masters.assetMasterName','=','assets.assetMaster')
                    // ->join('control_departments','control_departments.id','=','assets.controlDepartment')
                    // ->join('user_departments','user_departments.id','=','assets.userDepartment')
                    ->join('requester_departments','requester_departments.id','=','assets.requesterDepartment')
                     ->select('assets.*','assets.unitPlant as unitPlantId','units.unitPlant as unitPlant','lines.lineName as lineName')
                     ->get();
                        
                $response=[
                    "message" => "Asset List",
                    "data" => $asset
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
                "message" => $e->errorInfo,
                "status" => 406
               ];
            $status = 406; 
        }
        return response($response,$status); 
    }

    public function import(Request $request)
    {
        try{

            // $image = $request->file;
                
                // $data = explode(',',$image)[1];  
                // $imageName = Str::random(10).'.xlsx';
                // $imagePath = '/storage/app/public/'.$imageName;
                // Storage::disk('public')->put($imageName, base64_decode($data));
                // $file = $imageName;

            // Excel::import(new AssetsImport, storage_path('/app/public/'.$file));
            Excel::import(new AssetsImport, storage_path('/app/public/Asset(W).xlsx'));
            $response=[
                "message" => "Data Imported Sucessfully",
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

    public function template()
    {
        return Excel::download(new TemplateExport, 'Template.xlsx');
    }
}
