<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->onDelete('cascade');
            $table->date('date');
            $table->string('title', 64);
            $table->decimal('amount', 9, 3);
            $table->decimal('price', 13, 2);
            $table->foreignId('category_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('mean_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('currency_id');
            $table->timestamps(6);

            $table->index(['id', 'user_id', 'category_id', 'mean_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
