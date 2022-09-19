<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'last_backup', 'last_restoration'
    ];

    protected $casts = [
        'last_backup' => 'datetime',
        'last_restoration' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
