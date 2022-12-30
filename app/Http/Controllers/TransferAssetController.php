<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Exception;
use Illuminate\Database\QueryException;
use Str;
use Storage;

class TransferAssetController extends Controller
{

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
                $asset->unit = $request->unit;
                $asset->project = $request->project;
                $asset->department = $request->department;
                $asset->section = $request->section;
                $asset->line = $request->line;
                $asset->remarks = $request->remarks;

                $image = $request->fileUpload;
                if($image){  // your base64 encoded
                    $extension = $this->getExtension($image);
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $image = str_replace($replace, '', $image); 
                    $image = str_replace(' ', '+', $image); 
                    $imageName = Str::random(10).'.'.$extension;
                    $imagePath = '/storage/app/public/'.$imageName;
                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $asset->fileUpload = $imagePath;
                }
            
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

    //To Get All The AssetID
    public function getAssetId($id)
    { 
        try{ 

            $result = DB::table('assets')->select('id','assetId')->get();

            if(count($result)<=0){
                throw new Exception("data not found");
            }else{
               
                $response = [
                    'data' => $result         
                ];
                $status = 201;   
            }

        }catch(Exception $e){
                $response = [
                    "message" => $e->getMessage(),
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

    
    public function getAssetList($id)
    { 
        try{ 

            $result = DB::table('assets')->where('id','=',$id)->get();


            if(count($result)<=0){
                throw new Exception("data not found");
            }else{
               
                $response = [
                    'success' => true,
                    'data' => $result         
                ];
                $status = 201;   
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
