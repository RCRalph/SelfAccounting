<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('icon', 64)->nullable();
            $table->boolean('used_in_income')->default(true);
            $table->boolean('used_in_expenses')->default(true);
            $table->boolean('count_to_summary')->default(true);
            $table->boolean('show_on_charts')->default(true);
            $table->date('start_date');
            $table->decimal('start_balance', 13, 2);
            $table->timestamps(6);

            $table->index(['id', 'user_id', 'currency_id']);
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
};
