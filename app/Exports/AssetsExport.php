<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class AssetsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
            'MachineName',
            'AssetType',
            'Manufaturer',
            'AssetModel',
            'PODetails',
            'InvoiceDetails',
            'WarrantyStartDate',
            'WarrantyEndDate',
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
            'E' => 12, 
            'F' => 12, 
            'g' => 12,   
            'h' => 10, 
            'i' => 13.50,
            'j' => 17.50,  
            'k' => 17,                                
        ];
    }

}
