<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\BundleImage;
use App\User;

class Bundle extends Model
{
    protected $fillable = [
        "title", "price", "thumbnail", "short_description", "description", "code"
    ];

    public function gallery()
    {
        return $this->hasMany(BundleImage::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, "bundle_user");
    }

    public function premium_users()
    {
        return $this->belongsToMany(User::class, "bundle_user_premium", "bundle_id", "user_id");
    }
}
