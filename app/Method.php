<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Income;
use App\Outcome;

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

    public function income()
    {
        return $this->belongsToMany(Income::class);
    }

    public function outcome()
    {
        return $this->belongsToMany(Outcome::class);
    }
}