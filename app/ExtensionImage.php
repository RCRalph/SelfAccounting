<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Extension;

class ExtensionImage extends Model
{
    protected $fillable = [
        "extension_id", "image"
    ];

    protected $appends = ["image_link"];

    public function extension()
    {
        return $this->belongsTo(Extension::class);
    }

    protected function imageLink(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!array_key_exists("image", $attributes)) {
                    return "";
                }

                return getFileLink(
                    "public",
                    config("constants.directories.public.extensions.gallery"),
                    $attributes["image"]
                );
            }
        );
    }
}
