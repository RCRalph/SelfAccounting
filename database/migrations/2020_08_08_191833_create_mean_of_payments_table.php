<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeanOfPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mean_of_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('currency_id');
            $table->string('name', 32);
            $table->boolean('income_mean');
            $table->boolean('outcome_mean');
            $table->boolean('count_to_summary');
            $table->boolean('show_on_charts')->default(true);
            $table->date('first_entry_date');
            $table->decimal('first_entry_amount', 13, 2);
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
        Schema::dropIfExists('mean_of_payments');
    }
}
