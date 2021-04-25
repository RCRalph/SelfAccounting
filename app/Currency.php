<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Income;
use App\Outcome;
use App\Category;
use App\MeanOfPayment;
use App\Cash;

class Currency extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function income()
    {
        return $this->belongsToMany(Income::class);
    }

    public function outcome()
    {
        return $this->belongsToMany(Outcome::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function meansOfPayment()
    {
        return $this->belongsToMany(MeanOfPayment::class);
    }

    public function cash()
    {
        return $this->hasMany(Cash::class);
    }
}
