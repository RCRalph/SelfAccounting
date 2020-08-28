<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Method;
use App\Category;
use App\Currency;

class Outcome extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'user_id', 'date', 'title', 'amount', 'price', 'category_id', 'method_id', 'currency_id'
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
}
