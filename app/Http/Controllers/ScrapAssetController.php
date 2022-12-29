<?php

namespace App\Http\Controllers;

use App\Models\scrapAsset;
use App\Exports\ScrapAssetsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use DB;
use Str;
use Storage;

class ScrapAssetController extends Controller
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
            $x = explode(".", $extension1 )[3];
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
            $data = DB::table('scrap_assets')->where('assetName','=',$assetName)->get();

            if(count($data)>0){
                throw new Exception("this asset already exist");

            }else{

                $scrapAsset = new scrapAsset;
                    
                $scrapAsset->scrapType = $request->scrapType;
                $scrapAsset->department = $request->department;
                $scrapAsset->section = $request->section;
                $scrapAsset->assetType = $request->assetType;
                $scrapAsset->assetName = $request->assetName;
            
                //imageStoring
                $image = $request->scrapAprovalLetter;
                if($image){  // your base64 encoded
                    $extension = $this->getExtension($image); 
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));

                    $scrapAsset->scrapAprovalLetter = $imagePath;
                }

                $scrapAsset->user='Admin';

                $scrapAsset->save();
                $response = [
                    'success' => true,
                    'message' => $request->scrapType." Added successfully",
                    'status' => 201
                ];
                $status = 201;   
            }    
          
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
    
    public function showData()
    {
        try{    
            $scrapAsset = DB::table('scrap_assets')
                ->join('departments','departments.id','=','scrap_assets.department')
                ->join('sections','sections.id','=','scrap_assets.section')
                ->join('assettypes','assettypes.id','=','scrap_assets.assetType')
                ->join('assets','assets.id','=','scrap_assets.assetName')
                ->select('scrap_assets.id','departments.department_name as department',
                 'sections.section as section','assettypes.assetType as assetType',
                 'assets.assetName as assetName','scrap_assets.created_at as dateAndTime','scrap_assets.user')
               ->get();
          
            if(!$scrapAsset){
             throw new Exception("ScrapAsset not found");
            }

            $response=[
             "message" => "ScrapAsset List",
             "data" => $scrapAsset
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

    public function export()
    {
      $query = DB::table('scrap_assets')
        ->join('departments','departments.id','=','scrap_assets.department')
        ->join('sections','sections.id','=','scrap_assets.section')
        ->join('assettypes','assettypes.id','=','scrap_assets.assetType')
        ->join('assets','assets.id','=','scrap_assets.assetName')
        ->select('scrap_assets.id','departments.department_name as department',
         'sections.section as section','assettypes.assetType as assetType',
         'assets.assetName as assetName','scrap_assets.created_at as dateAndTime','scrap_assets.user')
        ->get();
  
      return Excel::download(new ScrapAssetsExport($query), 'ScrapAsset.xlsx');
    }
}
