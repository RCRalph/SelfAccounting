<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BundleUserPremiumPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_user_premium', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->onDelete('cascade');
            $table->foreignId('user_id')->onDelete('cascade');
            $table->timestamps();

            $table->index(['id', 'bundle_id', 'user_id']);
        });

        App\User::create([
            "username" => "Admin",
            "email" => "admin@selfaccounting.com",
            "password" => Illuminate\Support\Facades\Hash::make(env("ADMIN_PASSWORD")),
            "admin" => true,
            "darkmode" => true,
            "premium_expiration" => null,
            'profile_picture' => 'EmojiAdmin.png'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundle_user_premium');
    }
}
