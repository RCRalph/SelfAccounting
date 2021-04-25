<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Bundle;
use App\BundleImage;
use App\Currency;
use App\Cash;

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
        $currenciesAndCash = [
            "USD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.25, 0.1, 0.05, 0.01],
            "EUR" => [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
            "JPY" => [10000, 5000, 2000, 1000, 500, 100, 50, 10, 5, 1],
            "GBP" => [50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
            "AUD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05],
            "CAD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.25, 0.1, 0.05, 0.01],
            "CHF" => [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05],
            "PLN" => [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
            "INR" => [2000, 500, 200, 100, 50, 20, 10, 5, 2, 1]
        ];

        // Set data of bundlesd
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
                "description" => "This bundle allows you to easily backup your data and then restore it in case of an emergency. The backup includes:\n - **Your categories**\n - **Your means of payment**\n - **Your income**\n - **Your outcome**\n\nAs more bundles are developed, this bundle will be updated with all data stored by them.\n\nBare in mind that you can only restore your data if your account is older than 30 days old. In case of an emergency you of course can contact the developer, who will allow you to restore your data. This feature prevents new users from overloading the database.",
                "gallery" => [
                    "Tu9vjO7WdYm54o5QISTVddl17Divu3fdc3ezLbcgwLaChSU5VS.png",
                    "2BVTVrmt8nsTyACyVkNVY6PnPzAMpAsDqSx1bOJgwL2Z3lqQk9.png",
                    "CBclby9Ln4fKmFHF5rvoq4d2trWMLnQjhe5eMqf5DjL0SQx9om.png",
                    "Ky2VhbsE1ZxSW5a1ZJdTBvVCAfqSBGhalBZDSx0vlwcXEhSFKD.png"
                ]
            ],
            [
                "title" => "Cash handling",
                "code" => "cashan",
                "price" => 10,
                "short_description" => "Monitor how much cash you have",
                "thumbnail" => "cmdj3kqWW2jjD6ArLTkm7OMkMifxAwMgmVoA4XXKEZLHwsLYls.png",
                "description" => "This bundle allows you to monitor how much cash you have at the current moment and it will tell you if you lost any when you count it all up from time to time. All currencies in the app are supported.\n\nYou can immidately change how much cash you own while entering income or outcome. A special **cash** mean of payment is required.",
                "gallery" => [
                    "xVjOkipHCG2tnnh5khniL9wQLYYJLcsX9kc2KqXPm8lXFynrz2.png",
                    "Hfp53FN32ek3uQaUjC4M9XRpkgQQS7KWR9boPJmN2b9wBXaFdd.png",
                    "kv1mdZywk4ZPrWgA4y8skWZNDWHGBOA0VUNhe5FKMJPmfYmeDl.png",
                    "rBEdr7vYhSrdc1FZrawp5QzucS53tSnRXIafFCEDYSQStxCl5i.png"
                ]
            ]
        ];

        // Add records to database (if they don't exits)
        foreach ($currenciesAndCash as $ISO => $cash) {
            $currency = Currency::where("ISO", $ISO)->first();
            if (!$currency) {
                $currency = Currency::create(compact("ISO"));
            }

            sort($cash);
            foreach ($cash as $value) {
                if (!Cash::where(["currency_id" => $currency->id, "value" => $value])->count()) {
                    Cash::create([
                        "currency_id" => $currency->id,
                        "value" => $value
                    ]);
                }
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
