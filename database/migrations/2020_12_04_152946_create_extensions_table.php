<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6);
            $table->string('stripe_product_code', 64);
            $table->string('title', 64);
            $table->string('icon', 32);
            $table->string('directory', 32)->unique();
            $table->decimal('price', 5, 2);
            $table->string('thumbnail', 96);
            $table->string('short_description', 64);
            $table->string('description', 64);
            $table->timestamps();

            $table->index(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extensions');
    }
}
