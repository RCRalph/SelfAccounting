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
        Schema::create('chart_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_id')->constrained()->onDelete('cascade');
            $table->string('route', 128);

            $table->index(['id', 'chart_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_routes');
    }
};
