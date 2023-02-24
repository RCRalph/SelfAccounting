<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Cashier\Billable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, HasFactory, Billable;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'admin',
        'darkmode',
        'profile_picture',
        'premium_expiration',
        'hide_all_tutorials',
        'send_activity_reminders',
        'last_page_visit',
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

    protected $appends = ["profile_picture_link", "account_type", "extension_codes"];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function expences()
    {
        return $this->hasMany(Expence::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function extensions()
    {
        return $this->belongsToMany(Extension::class, 'extension_user', 'user_id', 'extension_id')->withPivot('enabled');
    }

    public function premiumExtensions()
    {
        return $this->belongsToMany(Extension::class, 'extension_user_premium', 'user_id', 'extension_id');
    }

    public function backup()
    {
        return $this->hasOne(Backup::class);
    }

    public function cashAccounts()
    {
        return $this->belongsToMany(Account::class, 'cash_account_user', 'user_id', 'account_id');
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

    public function disabledTutorials()
    {
        return $this->belongsToMany(Tutorial::class, 'tutorial_user', 'user_id', 'tutorial_id');
    }

    protected function profilePictureLink(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!array_key_exists("profile_picture", $attributes)) {
                    return "";
                }

                if (preg_match("/(Emoji[1-6]|admin).png/", $attributes["profile_picture"])) {
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

                if ($attributes["premium_expiration"] == null ||
                    today()->lte(Carbon::parse($attributes["premium_expiration"])) ||
                    $this->subscribed("premium")
                ) {
                    return "Premium";
                }

                return "Normal";
            }
        );
    }

    protected function extensionCodes(): Attribute
    {
        return Attribute::make(
            get: function () {
                $codes = $this->extensions->whereInStrict("pivot.enabled", [null, true])->pluck("code");
                if (!in_array(strtolower($this->account_type), ["admin", "premium"])) {
                    return $codes;
                }

                $premiumCodes = $this->premiumExtensions()->pluck("extensions.code");

                return $codes->merge($premiumCodes)->unique();
            }
        );
    }
}
