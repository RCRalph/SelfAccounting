<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\BundleImage;

class Bundle extends Model
{
    protected $fillable = [
        "title", "price", "thumbnail", "short_description", "description"
    ];

    public function images()
    {
        return $this->hasMany(BundleImage::class);
    }
}
