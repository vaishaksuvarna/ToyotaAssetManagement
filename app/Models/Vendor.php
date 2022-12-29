<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendorName',
        'vendorType',
        'address',
        'email',
        'altEmail',
        'contactNo',
        'altContactNo',
        'reMarks',
        'gstNo',
        'gstCertificate',
        'msmeCertificate',
        'canceledCheque'
    ];
}
