<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Method extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
