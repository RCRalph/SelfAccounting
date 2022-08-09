<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartRoute extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function chart()
    {
        return $this->belongsTo(Chart::class);
    }
}
