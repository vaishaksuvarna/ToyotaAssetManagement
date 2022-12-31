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
        $Label = new Label;
         
        $Label->department  = $request->department ;
        $Label->selectSection = $request->selectSection;
        $Label->assetType = $request->assetType;
        $Label->selectAssetType = $request->selectAssetType;

        if($Label->selectAssetType == 'asset'){
            $Label->selectAsset = $request->selectAsset;
            $getId = $this->assetGetId($request);
        } 

        if($Label->selectAssetType == 'assetId'){
            $Label->selectAssetId = $request->selectAssetId;
            $getId = $request->selectAssetId;
        }
        $Label->code = $request->code; 
        
        // QrCode
        if($Label->code == 'qrCode')
        {
            $filename =  Str::random(10).'.png';
            $store =  storage_path().'/app/public/';
            base64_encode(QrCode::format('png')->size(100)->generate($getId, $store.$filename));
            $Label->codeGenerator =  '/storage/app/public/'.$filename;
        }
        
        // BarCode
        /*if($Label->code == 'barCode')
        {
        $name =  Str::random(10).'.png';;
        Storage::disk('public')->put("$name" ,base64_decode(DNS1DFacade::getBarcodePNG($getId, "C128",1.4,22)));
        $Label->codeGenerator =  '/storage/app/public/'.$name;
        }*/
           
            $Label->save();
            
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
                ->join('departments','departments.id','=','labels.department')
                ->join('sections','sections.id','=','labels.selectSection')
                ->leftjoin('assets','assets.id','=','labels.selectAsset')
                ->select('labels.*','labels.id','departments.department_name as department', 
                    'sections.section as selectSection','assets.assetName as selectAsset','labels.selectAssetId','codeGenerator','labels.created_at as date')
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
                $Label = DB::table('labels')->where('labels.id','=',$id)->first();
                $Label = $Label->selectAssetType;

                if($Label == "asset")
                {
                    $Label = DB::table('labels')->where('labels.id','=',$id)
                        ->join('departments','departments.id','=','labels.department')
                        ->join('sections','sections.id','=','labels.selectSection')
                        ->join('assets','assets.id','=','labels.selectAsset')
                        ->select('labels.*','labels.id','departments.department_name as department', 
                            'sections.section as selectSection','assets.assetName as selectAsset','codeGenerator','labels.created_at as date')
                        ->get();
                }
                else{
                    $Label = DB::table('labels')->where('labels.id','=',$id)
                    ->join('departments','departments.id','=','labels.department')
                    ->join('sections','sections.id','=','labels.selectSection')
                    ->select('labels.*','labels.id','departments.department_name as department', 
                        'sections.section as selectSection','codeGenerator','labels.created_at as date')
                    ->get();
                }

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
