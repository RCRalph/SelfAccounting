<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Income;
use App\Outcome;
use App\Currency;

class MeanOfPayment extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'currency_id', 'name', 'income_mean', 'outcome_mean', 'show_on_charts', 'count_to_summary', 'first_entry_income_id'
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
        return $this->belongsTo(Income::class);
    }

    public function outcome()
    {
        return $this->belongsTo(Outcome::class);
    }

    public function firstEntry()
    {
        return $this->belongsTo(Income::class, 'first_entry_income_id');
    }
}
