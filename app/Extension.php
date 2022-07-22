<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\ExtensionImage;
use App\User;

class Extension extends Model
{
    protected $fillable = [
        "title", "price", "thumbnail", "short_description", "description", "code", "icon", "directory"
    ];

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