<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Bundle;

class BundleImage extends Model
{
    protected $fillable = [
        "bundle_id", "image"
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
}
