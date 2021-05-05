<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->string('query_data', 8);
            $table->date('min_date')->nullable();
            $table->date('max_date')->nullable();
            $table->string('title', 64)->nullable();
            $table->decimal('min_amount', 9, 3)->nullable();
            $table->decimal('max_amount', 9, 3)->nullable();
            $table->decimal('min_price', 13, 2)->nullable();
            $table->decimal('max_price', 13, 2)->nullable();
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->foreingId('category_id')->constrained()->onDelete('set null');
            $table->foreignId('mean_id')->constrained('mean_of_payments')->onDelete('set null');

            $table->index(['id', 'report_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_queries');
    }
}
