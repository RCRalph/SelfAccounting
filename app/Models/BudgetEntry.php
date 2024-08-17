<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
