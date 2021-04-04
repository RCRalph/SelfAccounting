<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Bundle;
use App\BundleImage;
use App\Currency;

class ExecuteAfterMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Set currencies
        $currencies = ["USD", "EUR", "JPY", "GBP", "AUD", "CAD", "CHF", "PLN"];

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
            ],
            [
                "title" => "Backup data",
                "code" => "backup",
                "price" => 2.5,
                "short_description" => "Create backups of your data",
                "thumbnail" => "9FNj1k4yQ2Sp0OZ2swlhlSgwfYHx9awtrD6s8Cd5h4x4euBKXT.png",
                "description" => "This bundle allows you to easily backup your data and then restore it in case of an emergency. The backup includes:\n - **Your categories**\n - **Your means of payment**\n - **Your income**\n - **Your outcome**\n\nAs more bundles are developed, this bundle will be updated with all data stored by them.",
                "gallery" => [
                    "Tu9vjO7WdYm54o5QISTVddl17Divu3fdc3ezLbcgwLaChSU5VS.png",
                    "2BVTVrmt8nsTyACyVkNVY6PnPzAMpAsDqSx1bOJgwL2Z3lqQk9.png",
                    "CBclby9Ln4fKmFHF5rvoq4d2trWMLnQjhe5eMqf5DjL0SQx9om.png",
                    "Ky2VhbsE1ZxSW5a1ZJdTBvVCAfqSBGhalBZDSx0vlwcXEhSFKD.png"
                ]
            ]
        ];

        // Add records to database (if they don't exits)
        foreach ($currencies as $currency) {
            if (!Currency::where("ISO", $currency)->count()) {
                Currency::create(["ISO" => $currency]);
            }
        }

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
