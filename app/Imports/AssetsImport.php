<?php

namespace App\Imports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\ToModel;

class AssetsImport implements ToModel
{
    
    public function model(array $row)
    {
        return new Asset([
            'assetId' =>$row['0'],
            'department' =>$row['1'],
            'section' =>$row['2'],
            'assetName' =>$row['3'],
            'financialAssetId' =>$row['4'],
            'vendorName' =>$row['5'],
            'phoneNumber'=>$row['6'],
            'email'=>$row['7'],
            'vendorAddress'=>$row['8'],
            'assetType'  =>$row['9'],
            'manufacturer' =>$row['10'],
            'assetModel' =>$row['11'],
            'poNo' =>$row['12'],
            'invoiceNo' =>$row['13'],
            'typeWarranty'=>$row['14'],
            'warrantyStartDate' =>$row['15'],
            'warrantyEndDate' =>$row['16'],
            'description'=>$row['17'],
        ]);
    }
}
