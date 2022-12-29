<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scrapAsset extends Model
{
    use HasFactory;
    protected $fillable = [
        'scrapType',        
        'department ',
        'section ',
        'assetType',
        'assetName',
        'scrapAprovalLetter',
    ];
}
