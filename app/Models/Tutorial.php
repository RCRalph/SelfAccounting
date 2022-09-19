<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function usersWhoDisabled()
    {
        return $this->belongsToMany(User::class, 'tutorial_user', 'tutorial_id', 'user_id');
    }
}
