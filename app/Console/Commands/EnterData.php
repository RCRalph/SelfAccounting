<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Extension;
use App\ExtensionImage;
use App\Currency;
use App\Cash;
use App\Chart;
use App\Tutorial;

class EnterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enter_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add records to the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private $dataToEnter = [
        "extensions" => [
            [
                "title" => "Chart pack",
                "code" => "charts",
                "icon" => "mdi-chart-bar",
                "directory" => "charts",
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
        ],
        "currencies" => [
            "USD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.25, 0.1, 0.05, 0.01],
            "EUR" => [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
            "JPY" => [10000, 5000, 2000, 1000, 500, 100, 50, 10, 5, 1],
            "GBP" => [50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
            "AUD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05],
            "CAD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.25, 0.1, 0.05, 0.01],
            "CHF" => [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05],
            "PLN" => [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
            "INR" => [2000, 500, 200, 100, 50, 20, 10, 5, 2, 1]
        ],
        "users" => [
            [
                "username" => "Normal",
                "email" => "normal@test.com",
                "password" => "1234567890",
                "admin" => false,
                "darkmode" => true,
                "premium_expiration" => null,
                "profile_picture" => "Emoji1.png",
                "last_page_visit" => "2000-01-01",
                "send_activity_reminders" => true
            ]
        ],
        "charts" => [
            [
                "id" => 1,
                "name" => "Balance history"
            ],
            [
                "id" => 2,
                "name" => "Income by categories"
            ],
            [
                "id" => 3,
                "name" => "Income by means of payment"
            ],
            [
                "id" => 4,
                "name" => "Outcome by categories"
            ],
            [
                "id" => 5,
                "name" => "Outcome by means of payment"
            ]
        ],
        "tutorials" => [
            [
                "route" => "/",
                "filename" => "dashboard.md"
            ],
            [
                "route" => "/settings",
                "filename" => "settings.md"
            ],
            [
                "route" => "/income",
                "filename" => "income.md"
            ],
            [
                "route" => "/outcome",
                "filename" => "outcome.md"
            ],
            [
                "route" => "/extensions/store",
                "filename" => "extensions.md"
            ]
        ]
    ];

    private function outputMessage($message) {
        $this->info("$message...");
    }

    private function addUsers()
    {
        $createAdmin = env("CREATE_ADMIN", false);
        $createUsers = env("CREATE_USERS", false);

        if (!$createUsers && !$createUsers) {
            return;
        }

        $progressBar = $this->output->createProgressBar(count($this->dataToEnter["users"]) + $createAdmin);
        $this->outputMessage("Creating users");

        $progressBar->start();
        if ($createAdmin) {
            User::updateOrCreate(
                ["id" => 1],
                [
                    "username" => "Admin",
                    "email" => env("ADMIN_EMAIL", "admin@selfaccounting.com"),
                    "password" => Hash::make(env("ADMIN_PASSWORD", "1234567890")),
                    "admin" => true,
                    "darkmode" => true,
                    "premium_expiration" => null,
                    "profile_picture" => "EmojiAdmin.png",
                    "last_page_visit" => "1970-01-01",
                    "send_activity_reminders" => true
                ]
            );

            $progressBar->advance();
        }

        if ($createUsers) {
            foreach ($this->dataToEnter["users"] as $user) {
                User::updateOrCreate(
                    ["email" => $user["email"]],
                    [
                        "username" => $user["username"],
                        "password" => Hash::make($user["password"]),
                        "admin" => $user["admin"],
                        "darkmode" => $user["darkmode"],
                        "premium_expiration" => $user["premium_expiration"],
                        "profile_picture" => $user["profile_picture"],
                        "last_page_visit" => $user["last_page_visit"],
                        "send_activity_reminders" => $user["send_activity_reminders"]
                    ]
                );

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);
    }

    private function addCurrenciesAndCash()
    {
        $progressBar = $this->output->createProgressBar(count($this->dataToEnter["currencies"]));
        $this->outputMessage("Creating currencies and cash");

        $progressBar->start();
        foreach ($this->dataToEnter["currencies"] as $ISO => $cash) {
            $currencyInDB = Currency::firstOrCreate(compact("ISO"));
            sort($cash);

            foreach ($cash as $value) {
                $cashInDB = $currencyInDB->cash()->where("value", $value)->first();

                if (!$cashInDB) {
                    $currencyInDB->cash()->create(["value" => $value]);
                }
            }

            $currencyInDB->cash()->whereNotIn("value", $cash)->delete();

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
    }

    private function addExtensions()
    {
        $progressBar = $this->output->createProgressBar(count($this->dataToEnter["extensions"]));
        $this->outputMessage("Creating extensions");

        $progressBar->start();

        foreach ($this->dataToEnter["extensions"] as $extension) {
            $extensionInDB = Extension::updateOrCreate(["code" => $extension["code"]], $extension);

            foreach ($extension["gallery"] as $image) {
                $imageInDB = $extensionInDB->gallery()->where("image", $image)->first();

                if (!$imageInDB) {
                    $extensionInDB->gallery()->create(["image" => $image]);
                }
            }

            $extensionInDB->gallery()->whereNotIn("image", $extension["gallery"])->delete();

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
    }

    private function addCharts()
    {
        $progressBar = $this->output->createProgressBar(count($this->dataToEnter["charts"]));
        $this->outputMessage("Creating charts");

        $progressBar->start();

        foreach ($this->dataToEnter["charts"] as $chart) {
            Chart::updateOrCreate(["id" => $chart["id"]], $chart);

            $progressBar->advance();
        }
        $progressBar->finish();
        $this->newLine(2);
    }

    private function addTutorials()
    {
        $progressBar = $this->output->createProgressBar(count($this->dataToEnter["tutorials"]));
        $this->outputMessage("Creating tutorials");

        $progressBar->start();

        foreach ($this->dataToEnter["tutorials"] as $tutorial) {
            Tutorial::updateOrCreate(["route" => $tutorial["route"]], $tutorial);

            $progressBar->advance();
        }
        $progressBar->finish();
        $this->newLine(2);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->addUsers();
        $this->addCurrenciesAndCash();
        $this->addExtensions();
        $this->addCharts();
        $this->addTutorials();

        $this->info("Finished!");

        return 0;
    }
}
