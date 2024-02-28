<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public static function route($route)
    {
        return self::select("charts.id", "charts.name", "charts.type")
            ->join("chart_routes", "chart_routes.chart_id", "=", "charts.id")
            ->where("chart_routes.route", $route)
            ->get();
    }

    public function routes()
    {
        return $this->hasMany(ChartRoute::class);
    }
}
