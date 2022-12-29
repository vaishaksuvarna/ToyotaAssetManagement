<?php

namespace App\Exports;

use App\Models\scrapAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class ScrapAssetsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
   
    protected $query;
    public function __construct($query){
        $this->query = $query;
      
    } 

    public function collection()
    {
        return  collect($this->query);
    }
    
   
    public function headings() :array
    {
        return [
            'SerialNo', 
            'Department', 
            'Section',
            'AssetType',
            'AssetName',
            'Date&Time',
            'User',
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
            'B' => 12,
            'C' => 12,
            'D' => 16,
            'E' => 16, 
            'F' => 18,                   
        ];
    }
}
