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

    protected $appends = ["profile_picture_link", "account_type", "extension_codes", "last_used_currencies", "transaction_titles"];

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'currency_user', 'user_id', 'currency_id')->withPivot('last_used');
    }

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

    public function expenses()
    {
        return $this->hasMany(Expense::class);
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

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function balance($accounts, $categories, $endDate = null)
    {
        $result = [];

        foreach ($accounts as $account) {
            $balance = $account->balance($endDate);

            if (!is_null($balance)) {
                // Check for missing attributes
                foreach (["icon", "id", "name", "start_date", "start_balance"] as $attribute) {
                    if (!array_key_exists($attribute, $account->attributes)) {
                        abort(500, "Missing account $attribute");
                    }
                }

                $result[] = [
                    "icon" => $account->icon,
                    "account_id" => $account->id,
                    "name" => $account->name,
                    "balance" => $balance
                ];
            }
        }

        foreach ($categories as $category) {
            $balance = $category->balance();

            if (!is_null($balance)) {
                // Check for missing attributes
                foreach (["icon", "id", "name"] as $attribute) {
                    if (!array_key_exists($attribute, $category->attributes)) {
                        abort(500, "Missing category $attribute");
                    }
                }

                $result[] = [
                    "icon" => $category->icon,
                    "category_id" => $category->id,
                    "name" => $category->name,
                    "balance" => $balance
                ];
            }
        }

        // Sort by balance DESC
        usort($result, function ($a, $b) {
            if ($b["balance"] == $a["balance"]) return 0;

            return $b["balance"] < $a["balance"] ? -1 : 1;
        });

        return $result;
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
                $codes = $this->extensions->whereInStrict("enabled", [null, true])->pluck("code");
                if (!in_array(strtolower($this->account_type), ["admin", "premium"])) {
                    return $codes;
                }

                $premiumCodes = $this->premiumExtensions()->pluck("extensions.code");

                return $codes->merge($premiumCodes)->unique();
            }
        );
    }

    protected function lastUsedCurrencies(): Attribute
    {
        return Attribute::make(
            get: function () {
                $currencies = Currency::all()->toArray();

                $lastCurrencyIDs = $this->currencies()
                    ->orderBy("last_used", "ASC") // Sorting ASC due to the order of insertion in foreach below
                    ->pluck("id");

                // Move last used currencies to the beginning of the array
                foreach ($lastCurrencyIDs as $currency) {
                    $index = array_search($currency, array_column($currencies, "id"));
                    array_unshift($currencies, $currencies[$index]);
                    array_splice($currencies, $index + 1, 1);
                }

                return $currencies;
            }
        );
    }
}
