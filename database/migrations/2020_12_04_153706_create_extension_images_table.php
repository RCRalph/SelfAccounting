<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extension_id')->constrained()->onDelete('cascade');
            $table->string('image', 64)->unique();
            $table->timestamps();

            $table->index(['id', 'extension_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extension_images');
    }
}
