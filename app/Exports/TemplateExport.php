<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TemplateExport implements  WithHeadings
{
  
    public function headings() :array
    {
        return [
            'assetId',
            'department',
            'section',
            'assetName',
            'financialAssetId',
            'assetType',
            'unit',
            'project',
            'line',
            'operationNo',
            'usagecode',
            'yearOfMfg',
            'usedOrNew',
            'requesterName',
            'manufacturer',
            'description',
        ];
    }
}
 