<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Currency;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string("ISO", 3)->unique();
            $table->string("name", 65)->unique();

            $table->index(['id']);
        });

        $currencies = [
            ["ISO" => "USD", "name" => "United States Dollar"],
            ["ISO" => "EUR", "name" => "European Euro"],
            ["ISO" => "JPY", "name" => "Japanese Yen"],
            ["ISO" => "GBP", "name" => "Pound Sterling"],
            ["ISO" => "AUD", "name" => "Australian Dollar"],
            ["ISO" => "CAD", "name" => "Canadian Dollar"],
            ["ISO" => "CHF", "name" => "Swiss Franc"],
            ["ISO" => "PLN", "name" => "Polish Zloty"],
        ];

        Currency::insert($currencies);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
