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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 32);
            $table->string('email', 64)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 64);
            $table->boolean('admin')->default(false);
            $table->boolean('darkmode')->default(true);
            $table->string('profile_picture', 64);
            $table->date('premium_expiration')->nullable();
            $table->boolean('hide_all_tutorials')->default(false);
            $table->boolean('send_activity_reminders')->default(true);
            $table->date('last_page_visit');
            $table->rememberToken();
            $table->timestamps(6);

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
        Schema::dropIfExists('users');
    }
};
