<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\User;
use App\Income;
use App\Outcome;
use App\Currency;

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
        return $this->hasMany(Currency::class);
    }

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }

    public function cash_user()
    {
        return $this->belongsToMany(User::class, "cash_mean_user", "mean_id", "user_id");
    }
}
