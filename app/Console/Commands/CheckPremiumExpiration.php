<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;

use App\User;
use App\Mail\RemindOfPremiumExpiration;

class CheckPremiumExpiration extends Command
{
    protected $daysToCheck = [7, 3, 1];

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
    protected $description = "Sends emails if user has 7, 3 or 1 day until premium expiration";

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
        foreach ($this->daysToCheck as $numberOfDays) {
            $users = User::where("premium_expiration", Carbon::now()->addDays($numberOfDays - 1))
                ->select("username", "email")->get();

            foreach ($users as $user) {
                Mail::to($user->email)
                    ->queue(new RemindOfPremiumExpiration($numberOfDays, $user->username));
            }
        }

        return 0;
    }
}
