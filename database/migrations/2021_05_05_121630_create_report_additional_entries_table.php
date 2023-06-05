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
        Schema::create('report_additional_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('title', 64);
            $table->decimal('amount', 9, 3);
            $table->decimal('price', 13, 2);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();

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
        Schema::dropIfExists('report_additional_entries');
    }
};
