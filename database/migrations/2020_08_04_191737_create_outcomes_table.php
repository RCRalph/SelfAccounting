<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->onDelete('cascade');
            $table->date('date');
            $table->string('title', 64);
            $table->decimal('amount', 9, 3);
            $table->decimal('price', 10, 2);
            $table->foreignId('category_id')->nullable();
            $table->foreignId('method_id')->nullable();
            $table->foreignId('currency_id');
            $table->timestamps(6);
        });

        App\User::create([
            "username" => "SelfAccountingAdmin",
            "email" => "admin@selfaccounting.com",
            "password" => Illuminate\Support\Facades\Hash::make("h3r3c0m3sth3m0n3y"),
            "admin" => true,
            "darkmode" => false
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outcomes');
    }
}
