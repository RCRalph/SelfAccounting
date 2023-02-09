<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    private $users = [
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
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env("CREATE_ADMIN", false)) {
            User::updateOrCreate(
                [ "email" => env("ADMIN_EMAIL", "admin@selfaccounting.com") ],
                [
                    "username" => "Admin",
                    "password" => Hash::make(env("ADMIN_PASSWORD", "1234567890")),
                    "admin" => true,
                    "darkmode" => true,
                    "premium_expiration" => null,
                    "profile_picture" => "admin.png",
                    "last_page_visit" => "1970-01-01",
                    "send_activity_reminders" => true
                ]
            );
        }

        if (env("CREATE_USERS", false)) {
            foreach ($this->users as $user) {
                User::updateOrCreate(
                    [ "email" => $user["email"] ],
                    [ ...$user, "password" => Hash::make($user["password"]) ]
                );
            }
        }
    }
}
