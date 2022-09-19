<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportQuery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function meanOfPayment()
    {
        return $this->belongsTo(MeanOfPayment::class, "mean_id");
    }
}
