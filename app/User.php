<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Method;
use App\Category;
use App\Income;
use App\MeanOfPayment;
use App\Bundle;
use App\Backup;

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
        'username', 'email', 'password', 'admin', 'darkmode', 'profile_picture', 'premium_expiration'
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

    public function premium_bundles()
    {
        return $this->belongsToMany(Bundle::class, "bundle_user_premium", "user_id", "bundle_id");
    }

    public function backup()
    {
        return $this->hasOne(Backup::class);
    }
}
