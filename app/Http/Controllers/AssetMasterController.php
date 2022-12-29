<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Exports\AssetsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Exception;
use Illuminate\Database\QueryException;

class AssetMasterController extends Controller
{

  public function showData($id)
  {
    try{
        $result = DB::table('assets')
        ->where('assets.assetType','=',$id)
        ->join('departments','departments.id','=','assets.department')
        ->join('sections','sections.id','=','assets.section')
        ->join('assettypes','assettypes.id','=','assets.assetType')
        ->select('assets.*','departments.department_name as department','sections.section as 
          section','assettypes.assetType as  assetype','assetName','manufacturer','poNo',
        'assetModel','warrantyStartDate')
        ->get();    
        
        if(!$result){
          throw new Exception("data not found");
        }

        $response=[
          "message" => "Assets List",
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

  public function export($id)
  {
    $query = DB::table('assets')
      ->where('assets.assetType','=',$id)
      ->join('departments','departments.id','=','assets.department')
      ->join('sections','sections.id','=','assets.section')
      ->join('assettypes','assettypes.id','=','assets.assetType')
      ->select('assets.id','departments.department_name as department', 
        'sections.section as section','assets.assetName','assettypes.assetType as assetType',
        'assets.manufacturer', 'assets.assetModel','assets.poNo','assets.invoiceNo', 
        'assets.warrantyStartDate', 'assets.warrantyEndDate')
      ->get();

    return Excel::download(new AssetsExport($query), 'Asset.xlsx');
  }
  
}

