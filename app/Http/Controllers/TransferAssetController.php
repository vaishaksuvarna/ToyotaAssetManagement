<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Exception;
use Illuminate\Database\QueryException;

class TransferAssetController extends Controller
{

    public function transferData(Request $request, $id)
    {
        try{
            $asset = Asset::find($id);

            if(!$asset){
                throw new Exception("data not found");
            }else{

                $data = $request->header('email');

                if($data){
                    $asset->transfer = 1;

                }else{
                    $asset->transfer = 0;
                }
                $asset->department = $request->department;
                $asset->section = $request->section;
                $asset->assetType = $request->assetType;
            
                $asset->save();
              
                $response = [
                    "massage" => "transfered successfully",
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
        }catch(QueryException $e){
                $response = [
                    "error" => $e->errorInfo,
                ];
                $status = 406; 
        }

        return response($response,$status);
      
    }  
}
