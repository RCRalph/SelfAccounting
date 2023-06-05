<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function currencies()
    {
        return $this->belongsToMany(User::class, 'currency_user', 'currency_id', 'user_id')->withPivot('last_used');
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function cash()
    {
        return $this->hasMany(Cash::class);
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
