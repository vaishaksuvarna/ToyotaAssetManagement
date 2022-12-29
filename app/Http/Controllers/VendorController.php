<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Http\Controllers\BaseController as BaseController;
use Exception;
use Illuminate\Database\QueryException;
use Validator;
use DB;
use Str;
use Storage;


class VendorController extends Controller
{
    // to get extension 
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
 
 
    // vendorRegistration
    public function store(Request $request)
    {
       try{                
            $vendor = new Vendor;
                
            $vendor->vendorName = $request->vendorName;
            $vendor->vendorType = $request->vendorType;
            $vendor->address = $request->address;
            $vendor->email = $request->email;
            $vendor->altEmail = $request->altEmail;
            $vendor->contactNo = $request->contactNo;
            $vendor->altContactNo = $request->altContactNo;
            $vendor->contactPerson = $request->contactPerson;
            $vendor->reMarks = $request->reMarks;
            $vendor->gstNo = $request->gstNo;
            
            //imageStoring
            $image = $request->gstCertificate; // your base64 encoded

            if($image){ 
                $extension = $this->getExtension($image); 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                $imagePath = '/storage/app/public/'.$imageName;
                $vendor->gstCertificate = $imagePath;
            }

            $image = $request->msmeCertificate;  
            if($image){ 
                $extension = $this->getExtension($image);
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage/app/public/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $vendor->msmeCertificate = $imagePath;
            }

            $image = $request->canceledCheque;  // your base64 encoded
            if($image){ 
                $extension = $this->getExtension($image);
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage/app/public/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $vendor->canceledCheque = $imagePath;
            }

            $vendor->save();
            $response = [
                'success' => true,
                'message' => $request->vendorName." Registered successfully",
                'status' => 201
            ];
            $status = 201;   
          
        }catch(Exception $e){
            $response = [
                "message"=>$e->getMessage(),
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

     //update
    public function update(Request $request, $id)
    {
    
        try{

            $vendor = Vendor::find($id);          
            if(!$vendor){
                throw new Exception("Vendor name not found");
            }
                
            $vendor->vendorName = $request->vendorName;
            $vendor->vendorType = $request->vendorType;
            $vendor->address = $request->address;
            $vendor->email = $request->email;
            $vendor->altEmail = $request->altEmail;
            $vendor->contactNo = $request->contactNo;
            $vendor->altContactNo = $request->altContactNo;
            $vendor->contactPerson = $request->contactPerson;
            $vendor->reMarks = $request->reMarks;
            $vendor->gstNo = $request->gstNo;

            $image = $request->gstCertificate; // your base64 encoded

            if($image){ 
                $extension = $this->getExtension($image);
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage'.'/'.$imageName;
                $imagePath = '/storage/app/public/'.$imageName;
                $vendor->gstCertificate = $imagePath;
            }

            $image = $request->msmeCertificate;  
            if($image){ 
                $extension = $this->getExtension($image);
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage/app/public/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $vendor->msmeCertificate = $imagePath;
            }

            $image = $request->canceledCheque;  // your base64 encoded
            if($image){ 
                $extension = $this->getExtension($image); 
                $replace = substr($image, 0, strpos($image, ',')+1); 
                $image = str_replace($replace, '', $image); 
                $image = str_replace(' ', '+', $image); 
                $imageName = Str::random(10).'.'.$extension;
                $imagePath = '/storage/app/public/'.$imageName;
                Storage::disk('public')->put($imageName, base64_decode($image));

                $vendor->canceledCheque = $imagePath;
            }

            $vendor->save();
            $response = [
                'success' => true,
                'message' => "Vendor updated successfully",
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

     
    //destroy
    public function destroy(Vendor $vendor, $id)
    {
        try{
            $vendor = Vendor::find($id);
            if(!$vendor){
                throw new Exception("Vendor name not found");
            }else{
                $vendor->delete();
                $response = [
                    "message" => "Vendor deleted successfully",
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
        }

        return response($response,$status);
      
    }  

    // Displaying VendorsDetails
    public function showData(Vendor $vendor)
    {

        try{
            $vendor = DB::table('vendors')
                ->join('vendor_types','vendor_types.id','=','vendors.vendorType')
                ->select('vendors.*','vendorName','address','email','contactNo','contactPerson','vendor_types.id as vendorTypeId')->orderby('id','asc')->get();

            if(!$vendor){
                throw new Exception("Vendor details not found");
            }else{
                $response = [
                    'success' => true,
                    'data' => $vendor         
                ];
                $status = 201;   
            return response($response,$status);
            }

        }catch(Exception $e){
            $response = [
                "error" => $e->getMessage(),
                "status" => 404
            ];
            $status = 404; 
        
        }

        return response($response,$status);

    }      
}