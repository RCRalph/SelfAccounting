<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Mail;

use App\User;
use App\Mail\ActivityReminder;

class CheckActivityReminder extends Command
{
    protected $daysToCheck = [7, 14, 30];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_activity_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends emails of user has a set amount of days of inactivity.';

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
            $users = User::where([
                ["last_page_visit", Carbon::now()->subDays($numberOfDays)],
                ["send_activity_reminders", true]
            ])->select("username", "email")->get();

            foreach ($users as $user) {
                Mail::to($user->email)
                    ->queue(new ActivityReminder($numberOfDays, $user->username));
            }
        }

        return 0;
    }
}
