<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Extension;

class ExtensionSeeder extends Seeder
{
    private $extensions = [
        [
            "title" => "Backup data",
            "code" => "backup",
            "icon" => "mdi-content-save",
            "directory" => "backup",
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
            "icon" => "mdi-cash-multiple",
            "directory" => "cash",
            "price" => 5,
            "short_description" => "Monitor how much cash you have",
            "thumbnail" => "cmdj3kqWW2jjD6ArLTkm7OMkMifxAwMgmVoA4XXKEZLHwsLYls.png",
            "description" => "This bundle allows you to monitor how much cash you have at the current moment and it will tell you if you lost any when you count it all up from time to time. All currencies in the app are supported.\n\nYou can immidately change how much cash you own while entering income or outcome. A special **cash** mean of payment is required.",
            "gallery" => [
                "xVjOkipHCG2tnnh5khniL9wQLYYJLcsX9kc2KqXPm8lXFynrz2.png",
                "Hfp53FN32ek3uQaUjC4M9XRpkgQQS7KWR9boPJmN2b9wBXaFdd.png",
                "kv1mdZywk4ZPrWgA4y8skWZNDWHGBOA0VUNhe5FKMJPmfYmeDl.png",
                "rBEdr7vYhSrdc1FZrawp5QzucS53tSnRXIafFCEDYSQStxCl5i.png"
            ]
        ],
        [
            "title" => "Report management",
            "code" => "report",
            "icon" => "mdi-newspaper",
            "directory" => "reports",
            "price" => 5,
            "short_description" => "Share your data with other users",
            "thumbnail" => "tWE8V7sdg7uDYJRynL7V3Axz787A8Sja9ecrzV0pd8xvsulm9t.png",
            "description" => "This bundle allows you to create and share reports with **other users**. A report is created by combining results from entered queries and entries specific to the report, then it is presented as a table sorted by dates and titles. You can also not share your reports with anyone and keep them for **personal usage**.\n\nMake sure that users that you are sharing your reports to own this bundle as well, otherwise they won't be able to see what you share with them.",
            "gallery" => [
                "ClWlSfW6eGCa6lyp54vfM1OxkjkYU3JOM8OMY5JrM8MqCTgteV.png",
                "dSLzL5fTYBgSaxQVm7m2fEd0EerNrBPuXaqJ2i9r3SQfr002u5.png",
                "ZfnEKjUrugiLValA8QMhJSnVdjRqhuiYwMInLNEXoAFcrlTrfJ.png",
                "LMxjtx3XDX7WDNZOSM1BsW4suEntQpzVEcCG0Rfjhgy4SCWHXL.png"
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
