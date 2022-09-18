<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Extension extends Model
{
    protected $guarded = [];

    protected $appends = ["thumbnail_link"];

    public function gallery()
    {
        return $this->hasMany(ExtensionImage::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function premium_users()
    {
        return $this->belongsToMany(User::class, "extension_user_premium", "extension_id", "user_id");
    }

    protected function thumbnailLink(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!array_key_exists("thumbnail", $attributes)) {
                    return "";
                }

                return getFileLink(
                    "public",
                    config("constants.directories.public.extensions.thumbnail"),
                    $attributes["thumbnail"]
                );
            }
        );
    }
}
