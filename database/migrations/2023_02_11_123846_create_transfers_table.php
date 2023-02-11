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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('source_account')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('source_value', 13, 2);
            $table->foreignId('target_account')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('target_value', 13, 2);
            $table->timestamps(6);

            $table->index(['id', 'user_id', 'source_account', 'target_account']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
