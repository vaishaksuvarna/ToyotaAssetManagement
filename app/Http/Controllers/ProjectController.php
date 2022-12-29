<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use DB;

class ProjectController extends Controller
{

    public function store(Request $request)
    {
        try{
            $data = DB::table('projects')->where('projectName','=',$request->projectName)->get();

            if(count($data)>0){
                throw new Exception("this ProjectName already exist");

            }else{
                $project = new Project;

                $project->projectName= $request->projectName;
                $project->description= $request->description;

                $project->save();

                $response = [
                    "message" => "Project Added Sucessfully!",
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
            $project = Project::find($id);

            if(!$project){
                throw new Exception("Project not found");

            }else{

                $project->projectName = $request->projectName;
                $project->description = $request->description;

                $project->save();

                $response = [       
                "message" =>' Project Updated Successfully', 
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
            $project = Project::find($id);

            if(!$project){
                throw new Exception("Project not found");

            }else{
                $project->delete();
                $response = [          
                    "message" => " Project Deleted Sucessfully!",
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
            $project = Project::all();

            if(count($project)<=0){
                throw new Exception("Projects not available");
            }

            $response=[
                "message" => "Project List",
                "data" => $project
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
