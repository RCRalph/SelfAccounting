<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Extension;

class ExtensionSeeder extends Seeder
{
    private $extensions = [
        [
            "title" => "Backup creator",
            "code" => "backup",
            "stripe_product_code" => "price_1LiOFhAmJbobJ1GsRjpZWlU2",
            "icon" => "mdi-content-save",
            "directory" => "backup",
            "price" => 2.5,
            "thumbnail" => "XzOiqpBrzhFDMVBuFLKhYvvhdkkyDTaHWkNmwHPlajEGkPgxqn.png",
            "short_description" => "Create backups of your data",
            "description" => "backup-creator.md",
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
            "title" => "Cash management",
            "code" => "cashan",
            "stripe_product_code" => "price_1LiPK1AmJbobJ1GsxUlTQ7Fq",
            "icon" => "mdi-cash-multiple",
            "directory" => "cash",
            "price" => 5,
            "thumbnail" => "SBifCTbjVEhZdDmNamVQNbVpUUdabJEspBvJvamWgePgtIRkKr.png",
            "short_description" => "Monitor how much cash you have",
            "description" => "cash-management.md",
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
            "thumbnail" => "2JJC6BYgtB11uBfQxBhfkIKHcef1hlGIP53TLOAbzgI7F8oLby.png",
            "short_description" => "Share your data with other users",
            "description" => "report-management.md",
            "gallery" => [
                "2JJC6BYgtB11uBfQxBhfkIKHcef1hlGIP53TLOAbzgI7F8oLby.png",
                "D9ygoZzipEyMvJiOYpSS2Crkpz0GKIEH9JoRFtxCtqh2utgmqr.png",
                "0W2CgEuYQc6MZXCSeDcFRD3DDHDqC88fqGciM9FMqHX1uobi8X.png",
                "TwWUSgasrWp3z1xCWdltnNL9MXNCymWVIZ64lSw7nR5zRBRZWa.png",
                "jLmMHGijbc7KVR5Foi2D7sL6Zbdaa6hCq95ECYXKt3jooF6HVn.png",
                "u7NI2CEX7lbAYzhhOyzl4d549H5soMHu2R7xbizsJwktfr3CUk.png"
            ]
        ],
        [
            "title" => "Budget creator",
            "code" => "budget",
            "stripe_product_code" => "price_1PiiMxAmJbobJ1GsFXZLpg2y",
            "icon" => "mdi-home-analytics",
            "directory" => "budgets",
            "price" => 5,
            "thumbnail" => "8OqgP3iC2VGgCTPmk0CIYbOUzDMGDQdG51yv9jcg98oZbd0jqm.png",
            "short_description" => "Budget your cashflow with ease",
            "description" => "budget-creator.md",
            "gallery" => [
                "PfqvbtCxzxbnFP3F2SAVbBUQUImrVxdRSklxJ9YQaUPpkAelKa.png",
                "DpKzBkRV7FDUYhoyLYTej2d05GpCcaTuXSVUVrAc0a8pLmWM8A.png",
                "I7E05iMFY6Sf3SqydIvtJokHvrJAs7tPnBU5JrFQmQajIVuhpl.png"
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

            $extensionInDB = Extension::updateOrCreate(["code" => $extension["code"]], $extension);

            foreach ($gallery as $image) {
                if ($extensionInDB->gallery()->where("image", $image)->doesntExist()) {
                    $extensionInDB->gallery()->create(["image" => $image]);
                }
            }

            $extensionInDB->gallery()->whereNotIn("image", $gallery)->delete();
        }
    }
}
