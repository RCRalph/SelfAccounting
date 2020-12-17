<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->onDelete('cascade');
            $table->string('image', 96);
            $table->timestamps();

            $table->index(['id', 'bundle_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundle_images');
    }
}
