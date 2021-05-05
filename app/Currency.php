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
        return $this->hasMany(Income::class);
    }

    public function outcome()
    {
        return $this->hasMany(Outcome::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function meansOfPayment()
    {
        return $this->hasMany(MeanOfPayment::class);
    }

    public function cash()
    {
        return $this->hasMany(Cash::class);
    }

    public function reportQueries()
    {
        return $this->hasMany(ReportQuery::class);
    }
}
