<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeanOfPayment extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'currency_id', 'name', 'income_mean', 'outcome_mean', 'show_on_charts', 'count_to_summary', 'first_entry_date', 'first_entry_amount'
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
        return $this->hasMany(Income::class, "mean_id");
    }

    public function outcome()
    {
        return $this->hasMany(Outcome::class, "mean_id");
    }

    public function cashUser()
    {
        return $this->belongsToMany(User::class, "cash_mean_user", "mean_id", "user_id");
    }

    public function reportQueries()
    {
        return $this->hasMany(ReportQuery::class, "mean_id");
    }

    public function reportAdditionalEntries()
    {
        return $this->hasMany(ReportAdditionalEntry::class, "mean_id");
    }
}
