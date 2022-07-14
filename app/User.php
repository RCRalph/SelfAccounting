<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
    ];

    protected $appends = ["profile_picture_link"];

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
        return $this->belongsToMany(Bundle::class)->withPivot('enabled');
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
}
