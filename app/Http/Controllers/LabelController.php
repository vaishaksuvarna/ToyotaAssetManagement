<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Str;


class LabelController extends Controller
{
    public function store(Request $request)
    {
        try{
            $asset = DB::table('assets')->where('assetId','=',$request->assetId)->get();   
            $label = DB::table('labels')->where('assetId','=',$request->assetId)->get();  

            if(count($asset)<=0){
                throw new Exception("AssetID is invalid");

            }else if(count($label)>0){
                throw new Exception("Qrcode is already generated to this Asset");

            }else{        
                $Label = new Label;

                $get = DB::table('assets')->where('assetId','=',$request->assetId)->first();
                $getId = $get->autoAssetId;

                $Label->assetId = $request->assetId;

                $filename =  Str::random(10).'.png';
                //$store =  storage_path().'/app/public/';
                $store = public_path().'/images/';
                base64_encode(QrCode::format('png')->size(100)->generate($getId, $store.$filename));
                // $Label->qrCode =  '/storage/app/public/'.$filename;
                $Label->qrCode =  '/images/'.$filename;

                $Label->save();
                
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


    public function assetGetId(Request $request)
    { 
        $Label = $request->selectAsset;
        $assetName = DB::table('assets')->where('id','=',$Label)->first('assetId');
        $assetName = $assetName->assetId;

        return $assetName;
    }


     // Displaying data
    public function showData(Label $Label)
    {
        try{
            $Label = DB::table('labels')
                ->join('assets','assets.assetId','=','labels.assetId')
                ->join('departments','departments.id','=','assets.department')
                ->join('sections','sections.id','=','assets.section')
                ->join('assettypes','assettypes.id','=','assets.assetType')
                ->select('labels.id','labels.assetId','assets.assetName as assetName','departments.department_name as department','sections.section as section','assettypes.assetType as assetType','labels.created_at')
                ->get();
                
                $response = [
                     'success' => true,
                     'data' => $Label         
                ];
                $status = 201;   
          
        }catch(Exception $e){

            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404; 
             
        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }            
     
        return response($response,$status);
    }    

    
    public function showLabel($id)
    {
 
        try{
            $Label = Label::find($id);

            if(!$Label){
                throw new Exception("data not found");

            }else{
                $Label = DB::table('labels')
                    ->join('assets','assets.assetId','=','labels.assetId')
                    ->join('departments','departments.id','=','assets.department')
                    ->join('sections','sections.id','=','assets.section')
                    ->join('assettypes','assettypes.id','=','assets.assetType')
                    ->where('labels.id','=',$id)
                    ->select('labels.id','labels.assetId','assets.assetName as assetName','departments.department_name as department','sections.section as section','assettypes.assetType as assetType','labels.qrCode','labels.created_at')
                    ->get();

                $response = [
                    'data' => $Label         
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
                "status"=>406
            ];
            $status = 406; 
        }
 
        return response($response,$status);
 
    }
    
    //destroy
    public function destroy(Label $Label, $id)
    {
        try{
            $Label = Label::find($id);
            if(!$Label){
                throw new Exception("data not found");
            }else{
                $Label->delete();

                $response = [
                    "message" => "Label deleted successfully",
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

        }catch(QueryException $e){
            $response = [
                "error" => $e->errorInfo,
                "status"=>406
            ];
            $status = 406; 
        }
 
        return response($response,$status);
       
    }  
}
