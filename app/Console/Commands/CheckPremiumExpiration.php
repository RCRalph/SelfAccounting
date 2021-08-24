<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;

use App\User;
use App\Mail\RemindOfPremiumExpiration;

class CheckPremiumExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "check_premium_expiration";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sends emails if user has 7 or 1 day until premium expiration";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users7Days = User::where("premium_expiration", Carbon::now()->addDays(6))
            ->select("username", "email")->get();
        $users1Day = User::where("premium_expiration", Carbon::now())
            ->select("username", "email")->get();

        foreach ($users7Days as $user) {
            Mail::to($user->email)
                ->queue(new RemindOfPremiumExpiration(7, $user->username));
        }

        foreach ($users1Day as $user) {
            Mail::to($user->email)
                ->queue(new RemindOfPremiumExpiration(1, $user->username));
        }

        return 0;
    }
}
