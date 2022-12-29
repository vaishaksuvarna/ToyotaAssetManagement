<?php

namespace App\Http\Controllers;

use App\Models\unit;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use DB;

class UnitController extends Controller
{
    
    public function store(Request $request)
    {
        try{
            $unit = DB::table('units')->where('unitName','=',$request->unitName)->get();

            if(count($unit)>0){
                throw new Exception("unitName already exists");
            }
            else{
                $unit = new unit;

                $unit->unitName= $request->unitName;
                $unit->description= $request->description;

                $unit->save();

                $response = [
                    "message" => "New Unit Added Sucessfully!",
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

            $unit = unit::find($id);

            if(!$unit){
                throw new Exception("unit not found");
            }

            $unit->unitName = $request->unitName;
            $unit->description = $request->description;

            $unit->save();

            $response = [       
               "message" => 'Unit Updated Successfully', 
               "status" => 200
            ];
            $status = 200;  


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

            $unit = unit::find($id);

            if(!$unit){
                throw new Exception("Unit not found");

            }else{
                $unit->delete();

                $response = [          
                    "message" => "Unit Deleted Sucessfully!",
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
            $unit = unit::all();

            if(count($unit)<=0){
             throw new Exception("Units not available");
            }

            $response=[
                "message" => "Unit List",
                "data" => $unit
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
