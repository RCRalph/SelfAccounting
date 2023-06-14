<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $guarded = [];

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
        'end_date'
    ];

    public static $ICONS = [
        "mdi-tshirt-crew",
        "mdi-train",
        "mdi-soccer",
        "mdi-school",
        "mdi-popcorn",
        "mdi-paw",
        "mdi-laptop",
        "mdi-hospital-building",
        "mdi-home-variant",
        "mdi-lipstick",
        "mdi-gift",
        "mdi-food-fork-drink",
        "mdi-cash-multiple",
        "mdi-cart",
        "mdi-car-hatchback",
        "mdi-beach",
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

    public function reportQueries()
    {
        return $this->hasMany(ReportQuery::class);
    }

    public function reportAdditionalEntries()
    {
        return $this->hasMany(ReportAdditionalEntry::class);
    }

    public function balance()
    {
        if (!array_key_exists("count_to_summary", $this->attributes)) {
            abort(500, "Missing category count to summary");
        } else if (!$this->count_to_summary) return null;

        foreach (["start_date", "end_date"] as $attribute) {
            if (!array_key_exists($attribute, $this->attributes)) {
                abort(500, "Missing category $attribute");
            }
        }

        $expenses = $this->expenses();
        if (!is_null($this->start_date)) {
            $expenses = $expenses->whereDate("date", ">=", $this->start_date);
        }

        if (!is_null($this->end_date)) {
            $expenses = $expenses->whereDate("date", "<=", $this->end_date);
        }

        return round($expenses->sum(DB::raw("round(amount * price, 2)")), 2);
    }
}
