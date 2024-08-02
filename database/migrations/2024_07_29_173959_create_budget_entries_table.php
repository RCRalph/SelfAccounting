<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId("budget_id")->constrained()->cascadeOnDelete();
            $table->foreignId("category_id")->constrained()->cascadeOnDelete();
            $table->string("transaction_type", 8);
            $table->decimal("value", 13, 2);
            $table->timestamps();

            $table->unique(["budget_id", "category_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_entries');
    }
};
