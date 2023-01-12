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
            'assetType' =>$row['5'],
            'unit'=>$row['6'],
            'project'=>$row['7'],
            'line'=>$row['8'],
            'operationNo'  =>$row['9'],
            'usagecode' =>$row['10'],
            'yearOfMfg' =>$row['11'],
            'usedOrNew' =>$row['12'],
            'requesterName' =>$row['13'],
            'manufacturer'=>$row['14'],
            'description' =>$row['15'],

            // 'assetId' =>$row['0'],  
            // 'assetNo' =>$row['1'],
            // 'projectName' =>$row['2'],
            // 'requesterDepartment' =>$row['3'],
            // 'unitPlant' =>$row['4'],
            // 'line' =>$row['5'],
            // 'component' =>$row['6'],
            // 'operationNo' =>$row['7'],
            // 'assetName' =>$row['8'],
            // 'operationName' =>$row['9'],
            // 'equipmentType' =>$row['10'],
            // 'dateOfRequest' =>$row['11'],
            // 'requesterName' =>$row['12'],
         ]);
    }
}
