<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        "pivot"
    ];

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
