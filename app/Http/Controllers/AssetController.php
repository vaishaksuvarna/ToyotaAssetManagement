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
                    
                $asset->autoAssetId = $this->autoAssetId($request);
                $asset->assetId = $request->assetId;
                $asset->department  = $request->department;
                $asset->controlDepartment  = $request->controlDepartment;
                $asset->userDepartment  = $request->userDepartment;
                $asset->section = $request->section;
                $asset->assetName = $request->assetName;
                $asset->financialAssetId = $request->financialAssetId;
                $asset->assetType = $request->assetType;
                $asset->unit = $request->unit;
                $asset->project  = $request->project;
                $asset->line = $request->line;
                $asset->operationNo = $request->operationNo;
                $asset->usagecode = $request->usagecode;
                $asset->yearOfMfg = $request->yearOfMfg;
                $asset->usedOrNew = $request->usedOrNew;
                $asset->requesterName = $request->requesterName;
                $asset->requesterDepartment  = $request->requesterDepartment;
                $asset->manufacturer = $request->manufacturer;
                $asset->manufacturerNo = $request->manufacturerNo;
                $asset->weight = $request->weight;
                $asset->description = $request->description;
        
                //imageStoring assteImage
                $image = $request->assetImage;
                if($image){  // your base64 encoded
                    $extension = $this->getExtension($image);
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $asset->assetImage = $imagePath;
                }
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
           $num["data"] = "00001";

        }elseif($numlength == 1){
            $num["data"] = "0000".$id + 1;
        
        }elseif($numlength == 2){
            $num["data"] = "000".$id + 1;
        
        }elseif($numlength == 3){
            $num["data"] = "00".$id + 1;
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

            $asset->assetId = $request->assetId;
            $asset->department  = $request->department;
            $asset->controlDepartment  = $request->controlDepartment;
            $asset->userDepartment  = $request->userDepartment;
            $asset->section = $request->section;
            $asset->assetName = $request->assetName;
            $asset->financialAssetId = $request->financialAssetId;
            $asset->assetType = $request->assetType;
            $asset->unit = $request->unit;
            $asset->project  = $request->project;
            $asset->line = $request->line;
            $asset->operationNo = $request->operationNo;
            $asset->usagecode = $request->usagecode;
            $asset->yearOfMfg = $request->yearOfMfg;
            $asset->usedOrNew = $request->usedOrNew;
            $asset->requesterName = $request->requesterName;
            $asset->requesterDepartment  = $request->requesterDepartment;
            $asset->manufacturer = $request->manufacturer;
            $asset->manufacturerNo = $request->manufacturerNo;
            $asset->weight = $request->weight;
            $asset->description = $request->description;
    
            //imageStoring assteImage
            $image = $request->assetImage;
            if($image){  // your base64 encoded
                $extension = $this->getExtension($image);
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage/app/public/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $asset->assetImage = $imagePath;
            }            
    
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
                "error" => $e->errorInfo,
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
                "error" => $e->getMessage(),
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
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->join('projects','projects.id','=','assets.project')
                    ->join('units','units.id','=','assets.unit')
                    ->join('lines','lines.id','=','assets.line')
                    ->select('assets.*','assets.id','assets.department',
                     'departments.department_name as departmentName', 
                     'assets.section', 'sections.section as sectionName',
                     'assets.assetName', 'assets.assetType','assettypes.assetType as assetTypeName','projects.projectName as projectName','units.unitName as unitName','lines.lineName as lineName'
                    )
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
                "error" => $e->errorInfo,
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
