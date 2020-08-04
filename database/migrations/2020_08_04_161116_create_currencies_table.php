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
        });

        Currency::insert([
            ["ISO" => "USD", "name" => "United States Dollar"],
            ["ISO" => "EUR", "name" => "European Euro"],
            ["ISO" => "CHF", "name" => "Swiss Franc"],
            ["ISO" => "PLN", "name" => "Polish Zloty"],
            ["ISO" => "GBP", "name" => "Pound Sterling"],
            ["ISO" => "CAD", "name" => "Canadian Dollar"],
            ["ISO" => "JPY", "name" => "Japanese Yen"],
            ["ISO" => "AUD", "name" => "Australian Dollar"]
        ]);
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
