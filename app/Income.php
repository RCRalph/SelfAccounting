<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Method;
use App\Category;
use App\Currency;
use App\MeanOfPayment;

class Income extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'date', 'title', 'amount', 'price', 'category_id', 'mean_id', 'currency_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class);
    }

    public function firstEntry()
    {
        return $this->hasOne(MeanOfPayment::class, 'first_entry_income_id');
    }

    public function mean()
    {
        return $this->hasOne(MeanOfPayment::class);
    }
}
