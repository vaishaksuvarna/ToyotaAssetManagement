<?php

namespace App\Exports;

use App\Models\Maintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class MaintenanceExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
            'MaintenanceId',  
            'maintenanceType',
            'Machine',
            'Severity',
            'ProblemNote',
            'Date',
            '',
            'Time'
        ];
    }

    public function styles(Worksheet $sheet)
    {
       
        $sheet->mergeCells('G1:H1');
        $sheet->mergeCells('I1:J1');


        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];

    }

    public function columnWidths(): array
    {
        return [
            'B' => 11,
            'C' => 10, 
            'D' => 16,
            'G' => 10, 
            'H' => 10,
            'E' => 10, 
            'F' => 12.50,                       
        ];
       
    }
}
