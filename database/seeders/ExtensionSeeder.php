<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Extension;

class ExtensionSeeder extends Seeder
{
    private $extensions = [
        [
            "title" => "Backup data",
            "code" => "backup",
            "stripe_product_code" => "price_1LiOFhAmJbobJ1GsRjpZWlU2",
            "icon" => "mdi-content-save",
            "directory" => "backup",
            "price" => 2.5,
            "short_description" => "Create backups of your data",
            "thumbnail" => "XzOiqpBrzhFDMVBuFLKhYvvhdkkyDTaHWkNmwHPlajEGkPgxqn.png",
            "description" => "This extension allows you to easily backup your data and then restore it in case of an emergency. The backup includes:\n - **Your categories**\n - **Your means of payment**\n - **Your income**\n - **Your outcome**\n\nAs more extensions are developed, this extension will be updated with all data stored by them.\n\nBare in mind that you can only restore your data if your account is older than 30 days old. In case of an emergency you of course can contact the developer, who will allow you to restore your data. This feature prevents new users from overloading the database.",
            "gallery" => [
                "XzOiqpBrzhFDMVBuFLKhYvvhdkkyDTaHWkNmwHPlajEGkPgxqn.png",
                "DfyHLPNjRLTGAhhnmauXdvptraTFSKlJcAigEpTCuWwCEmfOjj.png",
                "SFfKmPbSrdumdSJLZiVcXVZyBkLkLIXmcZuQbdBmARYDclAVoV.png",
                "tMHHPStNPGaPyQgkpdwSMyqzuGAanYuGrQYCbRPiooEHtqfCXl.png",
                "vcjLwbazZRbHSqLPthwAvBKnSIwtFQLqKOjTYyQxwBxaBKnCHh.png",
                "DsRVeucRFayuGyLzZJqfIVmdFoulMtjLwoEwUROYtClxqRPQuK.png"
            ]
        ],
        [
            "title" => "Cash handling",
            "code" => "cashan",
            "stripe_product_code" => "price_1LiPK1AmJbobJ1GsxUlTQ7Fq",
            "icon" => "mdi-cash-multiple",
            "directory" => "cash",
            "price" => 5,
            "short_description" => "Monitor how much cash you have",
            "thumbnail" => "SBifCTbjVEhZdDmNamVQNbVpUUdabJEspBvJvamWgePgtIRkKr.png",
            "description" => "This extension allows you to monitor how much cash you have at the current moment and it will tell you if you lost any when you count it all up from time to time. All currencies in the app are supported.\n\nYou can immidately change how much cash you own while entering income or outcome. A special **cash** mean of payment is required.",
            "gallery" => [
                "SBifCTbjVEhZdDmNamVQNbVpUUdabJEspBvJvamWgePgtIRkKr.png",
                "GPUIHRnrqtwibGmoybLfXWrfwfsSJcQMkCQkbsmIggTvfzgcQd.png",
                "AgakViYsEskRNRWNXakzywqfhtEeTnONNMmqvFPcqTlvkXCfzM.png"
            ]
        ],
        [
            "title" => "Report management",
            "code" => "report",
            "stripe_product_code" => "price_1LiPKdAmJbobJ1GsdPCCl89p",
            "icon" => "mdi-newspaper",
            "directory" => "reports",
            "price" => 5,
            "short_description" => "Share your data with other users",
            "thumbnail" => "2JJC6BYgtB11uBfQxBhfkIKHcef1hlGIP53TLOAbzgI7F8oLby.png",
            "description" => "This extension allows you to create and share reports with **other users**. A report is created by combining results from entered queries and entries specific to the report, then it is presented as a table sorted by dates and titles. You can also not share your reports with anyone and keep them for **personal usage**.\n\nMake sure that users that you are sharing your reports to own this extension as well, otherwise they won't be able to see what you share with them.",
            "gallery" => [
                "2JJC6BYgtB11uBfQxBhfkIKHcef1hlGIP53TLOAbzgI7F8oLby.png",
                "D9ygoZzipEyMvJiOYpSS2Crkpz0GKIEH9JoRFtxCtqh2utgmqr.png",
                "0W2CgEuYQc6MZXCSeDcFRD3DDHDqC88fqGciM9FMqHX1uobi8X.png",
                "TwWUSgasrWp3z1xCWdltnNL9MXNCymWVIZ64lSw7nR5zRBRZWa.png",
                "jLmMHGijbc7KVR5Foi2D7sL6Zbdaa6hCq95ECYXKt3jooF6HVn.png",
                "u7NI2CEX7lbAYzhhOyzl4d549H5soMHu2R7xbizsJwktfr3CUk.png"
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->extensions as $extension) {
            $gallery = $extension["gallery"];
            unset($extension["gallery"]);

            $extensionInDB = Extension::updateOrCreate([ "code" => $extension["code"] ], $extension);

            foreach ($gallery as $image) {
                if ($extensionInDB->gallery()->where("image", $image)->doesntExist()) {
                    $extensionInDB->gallery()->create(["image" => $image]);
                }
            }

            $extensionInDB->gallery()->whereNotIn("image", $gallery)->delete();
        }
    }
}
