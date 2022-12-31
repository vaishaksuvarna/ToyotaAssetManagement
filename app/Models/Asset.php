<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
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
        'assetImage',
        'fileUpload',
        'transfer',
        'allocated'
    ];
}
