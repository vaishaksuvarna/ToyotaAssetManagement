<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendorType extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendorType',
        'description',
    ];
}
