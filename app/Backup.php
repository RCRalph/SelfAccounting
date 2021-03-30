<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\User;

class Backup extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'last_backup', 'last_restoration'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
