<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;



class TemplateExport implements  WithHeadings , WithStyles, WithColumnWidths
{
  
    public function headings() :array
    {
       
        return [
            'AssetId',
            'Department',
            'Section',
            'Asset Name',
            'Financial AssetId',
            'AssetType',
            'Unit',
            'Project',
            'Line',
            'OperationNo',
            'Usage Code',
            'Year Of Mfg',
            'Used Or New',
            'Requester Name',
            'Manufacturer',
            'Description',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 10,
            'D' => 11,
            'E' => 17, 
            'F' => 10, 
            'j' => 12,
            'k' => 11,   
            'l' => 13,
            'm' => 13,
            'n' => 15.50,
            'o' => 13 ,     
            'p' => 10.50 ,                                                 
        ];
    }
}
