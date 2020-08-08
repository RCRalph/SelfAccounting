<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeanOfPayment extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'currency_id', 'name', 'income_mean', 'outcome_mean', 'show_on_charts', 'count_to_summary', 'first_entry_income_id'
    ];
}
