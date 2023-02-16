<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'currency_id', 'name', 'used_in_income', 'used_in_expences', 'show_on_charts', 'count_to_summary', 'start_date', 'start_balance'
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

    public function expences()
    {
        return $this->hasMany(Expence::class);
    }

    public function sourceOfTransfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function targetOfTransfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function cashUser()
    {
        return $this->belongsToMany(User::class, "cash_account_user", "account_id", "user_id");
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