<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\User;
use App\Method;
use App\Category;
use App\Currency;
use App\MeanOfPayment;

class Income extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Category::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function meanOfPayment()
    {
        return $this->belongsTo(MeanOfPayment::class);
    }
}
