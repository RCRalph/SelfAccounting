<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Income;
use App\Outcome;

class Currency extends Model
{
    public function income()
    {
        return $this->belongsToMany(Income::class);
    }

    public function outcome()
    {
        return $this->belongsToMany(Outcome::class);
    }
}
