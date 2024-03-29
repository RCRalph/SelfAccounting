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
        Schema::create('cash_user', function (Blueprint $table) {
            $table->foreignId('cash_id')->constrained('cash')->cascadeOnDelete(); // Constrain has to be there, because it defaults to 'cashes'
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('amount');
            $table->timestamps();

            $table->primary(['cash_id', 'user_id']);
            $table->index(['cash_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_user');
    }
};
