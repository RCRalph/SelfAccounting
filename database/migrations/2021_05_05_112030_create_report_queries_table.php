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
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->string('query_data', 8);
            $table->date('min_date')->nullable();
            $table->date('max_date')->nullable();
            $table->string('title', 64)->nullable();
            $table->decimal('min_amount', 9, 3)->nullable();
            $table->decimal('max_amount', 9, 3)->nullable();
            $table->decimal('min_price', 13, 2)->nullable();
            $table->decimal('max_price', 13, 2)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();

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
