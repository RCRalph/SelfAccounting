<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Extension;

class ExtensionImage extends Model
{
    protected $fillable = [
        "extension_id", "image"
    ];

    public function extension()
    {
        return $this->belongsTo(Extension::class);
    }
}
