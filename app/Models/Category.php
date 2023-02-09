<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $guarded = [];

    protected $fillable = [
        'user_id', 'currency_id', 'name', 'used_in_income', 'used_in_outcome', 'show_on_charts', 'count_to_summary', 'start_date', 'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function outcome()
    {
        return $this->hasMany(Outcome::class);
    }

    public function reportQueries()
    {
        return $this->hasMany(ReportQuery::class);
    }

    public function reportAdditionalEntries()
    {
        return $this->hasMany(ReportAdditionalEntry::class);
    }
}
