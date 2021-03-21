<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Bundle;
use App\BundleImage;

class ExecuteAfterMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Set data of bundles
        $bundles = [
            [
                "title" => "Chart pack",
                "code" => "charts",
                "price" => 5,
                "short_description" => "A pack of useful charts",
                "thumbnail" => "Qc7TmMyeTRQttayk85Xw6zd3WvwNhSqOhdzL8DRs0sM322zv8y.png",
                "description" => "This bundle consists of, well, charts. It includes visual representation of your data, which will help you with analysing what you spend on as well as will show you how your balance progressed overtime and so on. The currently available charts are:\n - **Balance monitor** - see how your balance has progressed overtime\n- **Income by categories** - see how your income splits into categories\n - **Outcome by categories** - see how your outcome splits into categories\n - **Income by means of payment** - see how your income splits into means of payment\n - **Outcome by means of payment** - see how your outcome splits into means of payment",
                "gallery" => [
                    "JETiW41miD6OfsMwxDhO4OKBsYMby9Pgp467q2BtA84sB9OTgX.png",
                    "Qc7TmMyeTRQttayk85Xw6zd3WvwNhSqOhdzL8DRs0sM322zv8y.png",
                    "TkIrnuOJsHQILUPUaVMEWZS2GysjurjN9vAbk3rrgHkKVVwPsQ.png",
                    "u1EWxwhhvZyRKQLg7PKXOkyicZcys4mLk3zzPPKfJylRS5Fa2t.png",
                    "vxu6OFsLnzuuBgreyqR78fM9kYehIYqZlD9ZZcqi0iOw86Y9rZ.png"
                ]
            ]
        ];

        // Add records to database (if they don't exits)
        if (!User::find(1)) {
            User::create([
                "username" => "Admin",
                "email" => "admin@selfaccounting.com",
                "password" => Hash::make(env("ADMIN_PASSWORD")),
                "admin" => true,
                "darkmode" => true,
                "premium_expiration" => null,
                'profile_picture' => 'EmojiAdmin.png'
            ]);
        }

        foreach ($bundles as $bundle) {
            if (!Bundle::where("code", $bundle["code"])->count()) {
                $created = Bundle::create(array_diff_key(
                    $bundle, array_flip(["gallery"])
                ));
            }

            foreach ($bundle["gallery"] as $image) {
                if (!BundleImage::where("image", $image)->count()) {
                    BundleImage::create([
                        "bundle_id" => $created->id,
                        "image" => $image
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return;
    }
}
