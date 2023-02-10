<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->string('name', 32);
            $table->boolean('used_in_income')->default(true);
            $table->boolean('used_in_expences')->default(true);
            $table->boolean('count_to_summary')->default(true);
            $table->boolean('show_on_charts')->default(true);
            $table->date('start_date');
            $table->decimal('start_balance', 13, 2);
            $table->timestamps(6);

            $table->index(['id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
