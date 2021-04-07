<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Currency;

class Cash extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
