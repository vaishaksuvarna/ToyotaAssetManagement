<?php

namespace App\Http\Controllers;

use App\Models\Line;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use DB;

class LineController extends Controller
{
    public function store(Request $request)
    {
        try{
            $data = DB::table('lines')->where('lineName','=',$request->lineName)->get();

            if(count($data)>0){
                throw new Exception("this lineName already exist");

            }else{
                $line = new Line;

                $line->lineName= $request->lineName;
                $line->description= $request->description;
                $line->status = $request->status;

                $line->save();

                $response = [
                    "message" => "Line Added Successfully!",
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
            $line = Line::find($id);

            if(!$line){
                throw new Exception("Line not found");
            }

            $line->lineName = $request->lineName;
            $line->description = $request->description;
            $line->status = $request->status;

            $line->save();

            $response = [       
               "message" =>' Line Updated Successfully', 
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
            $line = Line::find($id);

            if(!$line){
                throw new Exception("Line not found");

            }else{
                $line->delete();

                $response = [          
                    "message" => " Line Deleted Successfully!",
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
            $line = Line::all();

            if(count($line)<=0){
                throw new Exception("Line not found");

            }else{

                $response=[
                    "message" => "Line List",
                    "data" => $line
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