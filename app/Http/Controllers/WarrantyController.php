<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class WarrantyController extends Controller
{
    public function showData(Request $request)
    {
      try{    
            $warrantyStartDate =$request->warrantyStartDate;
            $warrantyEndDate = $request->warrantyEndDate;

            $result = DB::table('assets')
                    ->where('warrantyStartDate','>=',$warrantyStartDate) 
                    ->where('warrantyEndDate','<=', $warrantyEndDate)
                    ->join('departments','departments.id','=','assets.department')
                    ->select('assets.id','departments.department_name as department','assets.assetName',
                     'assets.warrantyStartDate', 'assets.warrantyEndDate')
                    ->get();

                    if(count($result)<=0){
                        throw new Exception("data not found");
                }
            $response=[
             "message" => "View Warranty List",
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

    public function viewAsset($id)
    {
        try{    
            $asset = Asset::find($id);

            if(!$asset){
               throw new Exception("Asset not found");
            }else{
                $asset = DB::table('assets')
                    ->where('assets.id','=',$id)
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->select('assets.id','assettypes.assetType as assetType',
                     'sections.section as section','assets.manufacturer', 'assets.assetModel',
                      'assets.description', 'assets.assetImage')
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

}
