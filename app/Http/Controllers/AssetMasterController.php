<?php

namespace App\Http\Controllers;

use App\Models\assetMaster;
use Illuminate\Http\Request;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class AssetMasterController extends Controller
{
    public function store(Request $request)
    {
        try{
            $data = DB::table('asset_masters')->where('assetMasterName','=',$request->assetMasterName)->get();

            if(count($data)>0){
                throw new Exception("this assetMasterName already exist");

            }else{
                $assetMaster = new assetMaster;

                $assetMaster->assetMasterName= $request->assetMasterName;
                $assetMaster->description = $request->description;

                $assetMaster->save();

                $response = [
                    "message" => "assetMaster Added Sucessfully!",
                    "status" => 200
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


    public function update(Request $request,$id)
    {
        try{
            $assetMaster = assetMaster::find($id);

            if(!$assetMaster){
                throw new Exception("assetMaster not found");

            }else{

                $assetMaster->assetMasterName = $request->assetMasterName;
                $assetMaster->description = $request->description;

                $assetMaster->save();

                $response = [       
                "message" =>' assetMaster Updated Successfully', 
                "status" => 200
                ];
                $status = 200;  
            }

        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
                "status" => 406
            ];            
            $status = 200;
            
         }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status" => 406
            ];
            $status = 406; 
         }

        return response($response,$status);
    } 


    public function destroy($id)
    { 
        try{
            $assetMaster = assetMaster::find($id);

            if(!$assetMaster){
                throw new Exception("assetMaster not found");

            }else{
                $assetMaster->delete();
                $response = [          
                    "message" => " assetMaster Deleted Sucessfully!",
                    "status" => 200
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


    public function showData()
    {
        try{   
            $assetMaster = assetMaster::all();

            if(!$assetMaster){
                throw new Exception("assetMaster not available");

            }else{

                $assetMaster=DB::table('asset_masters')->get();

            }

            $response=[
                "message" => "assetMaster List",
                "data" => $assetMaster
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
