<?php

namespace App\Exports;

use App\Models\Allocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class AllocationExport implements FromCollection, WithHeadings, WithStyles
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
            'AssetId',
            'Assigneduser',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }
}
