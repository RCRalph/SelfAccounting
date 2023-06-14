<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id',
        'currency_id',
        'icon',
        'name',
        'used_in_income',
        'used_in_expenses',
        'show_on_charts',
        'count_to_summary',
        'start_date',
        'start_balance'
    ];

    public static $ICONS = [
        "mdi-wallet",
        "mdi-steam",
        "mdi-piggy-bank",
        "mdi-google-play",
        "mdi-credit-card-multiple",
        "mdi-credit-card",
        "mdi-currency-btc",
        "mdi-cash-multiple",
        "mdi-cash-100",
        "mdi-cash",
        "mdi-bank",
        "mdi-archive",
        "mdi-apple",
        "fab fa-paypal",
        "fab fa-google-wallet",
        "fa-vault",
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

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function transfersOut()
    {
        return $this->hasMany(Transfer::class, "source_account_id");
    }

    public function transfersIn()
    {
        return $this->hasMany(Transfer::class, "target_account_id");
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

    public function balance($endDate = null)
    {
        if (!isset($this->start_balance)) {
            abort(500, "Missing account start balance");
        }

        $income = $this->income();
        $expenses = $this->expenses();
        $transfersIn = $this->transfersIn();
        $transfersOut = $this->transfersOut();

        if ($endDate != null) {
            if (!isset($this->start_date)) {
                abort(500, "Missing account start date");
            } else if (strtotime($this->start_date) > strtotime($endDate)) return null;

            $income = $income->whereDate("date", "<=", $endDate);
            $expenses = $expenses->whereDate("date", "<=", $endDate);
            $transfersIn = $transfersIn->whereDate("date", "<=", $endDate);
            $transfersOut = $transfersOut->whereDate("date", "<=", $endDate);
        }

        return round(
            $this->start_balance
            + $income->sum(DB::raw("round(amount * price, 2)"))
            - $expenses->sum(DB::raw("round(amount * price, 2)"))
            + $transfersIn->sum("target_value")
            - $transfersOut->sum("source_value"),
            2
        );
    }
}
