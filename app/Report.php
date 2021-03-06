<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function queries()
    {
        return $this->hasMany(ReportQuery::class);
    }

    public function additionalEntries()
    {
        return $this->hasMany(ReportAdditionalEntry::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class);
    }
}
