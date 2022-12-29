<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagAsset extends Model
{
    use HasFactory;
    protected $fillable = [
        "tagAssetType",
        "assetId",
        "department",
        "section",
        "assetType",
        "assetName",
        "scanRfidNo",
        "rfidNo",
    ];
}
