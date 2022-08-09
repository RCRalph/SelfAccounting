<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function routes()
    {
        return $this->hasMany(ChartRoute::class);
    }
}
