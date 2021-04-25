<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Currency;
use App\User;

class Cash extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "cash";

    public $timestamps = false;

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
