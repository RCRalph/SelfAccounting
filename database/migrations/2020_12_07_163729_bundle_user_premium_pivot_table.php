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
            $table->foreignId('bundle_id')->constrained('bundles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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

        App\Bundle::create([
            "title" => "Chart pack",
            "code" => "charts",
            "price" => 5,
            "short_description" => "A pack of useful charts",
            "thumbnail" => "4G4WhlnmOldHaNxgEuoaV49X8dOa2qpKi7ZugA3zFewbHYvgVK.jpg",
            "description" => "This bundle consists of, well, charts. It includes visual representation of your data, which will help you with analysing what you spend on as well as will show you how your balance progressed overtime and so on. The currently available charts are:\n - **Balance monitor** - see how your balance has progressed overtime\n- **Income by categories** - see how your income splits into categories\n - **Outcome by categories** - see how your outcome splits into categories\n - **Income by means of payment** - see how your income splits into means of payment\n - **Outcome by means of payment** - see how your outcome splits into means of payment"
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
