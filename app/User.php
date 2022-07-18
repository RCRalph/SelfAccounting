<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'admin', 'darkmode', 'profile_picture', 'premium_expiration', 'hide_all_tutorials', 'send_activity_reminders', 'last_page_visit'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'premium_expiration' => 'date:Y-m-d'
    ];

    protected $appends = ["profile_picture_link", "account_type"];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function meansOfPayment()
    {
        return $this->hasMany(MeanOfPayment::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function outcome()
    {
        return $this->hasMany(Outcome::class);
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_user', 'user_id', 'bundle_id')->withPivot('enabled');
    }

    public function premiumBundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_user_premium', 'user_id', 'bundle_id');
    }

    public function backup()
    {
        return $this->hasOne(Backup::class);
    }

    public function cashMeans()
    {
        return $this->belongsToMany(MeanOfPayment::class, 'cash_mean_user', 'user_id', 'mean_id');
    }

    public function cash()
    {
        return $this->belongsToMany(Cash::class)->withPivot('amount');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function sharedReports()
    {
        return $this->belongsToMany(Report::class);
    }

    protected function profilePictureLink(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!array_key_exists("profile_picture", $attributes)) {
                    return "";
                }

                if (preg_match("/Emoji([1-6]|Admin).png/", $attributes["profile_picture"])) {
                    return getFileLink(
                        "public",
                        config("constants.directories.public.profile_picture"),
                        $attributes["profile_picture"]
                    );
                }

                return getFileLink(
                    "s3",
                    config("constants.directories.cloud.profile_picture"),
                    $attributes["profile_picture"]
                );
            }
        );
    }

    protected function accountType(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!array_key_exists("admin", $attributes) || !array_key_exists("premium_expiration", $attributes)) {
                    return "";
                }

                if ($attributes["admin"]) {
                    return "Admin";
                }

                if ($attributes["premium_expiration"] == null || today()->lte(Carbon::parse($attributes["premium_expiration"]))) {
                    return "Premium";
                }

                return "Normal";
            }
        );
    }

    protected function bundleIDs(): Attribute
    {
        return Attribute::make(
            get: function () {
                $IDs = auth()->user()->bundles->whereInStrict("pivot_enabled", [null, true])->pluck("id")->toArray();
                $premiumBundleIDs = auth()->user()->premiumBundles()->pluck("bundles.id")->toArray();

                return array_unique(array_merge($IDs, $premiumBundleIDs));
            }
        );
    }
}
