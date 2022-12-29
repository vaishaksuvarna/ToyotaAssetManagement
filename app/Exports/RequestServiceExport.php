<?php

namespace App\Exports;

use App\Models\RequestService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class RequestServiceExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
            'AssetName',
            'AmcStatus',
            'WarrantyStatus',
            'InsuranceStatus',
            'ProblemNote',
            'UserName'
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
            'B' => 11,
            'D' => 16,
            'E' => 17, 
            'F' => 17, 
            'g' => 47.60,   
            'h' => 12.20, 
            'i' => 10.20,
        ];
    }
}
