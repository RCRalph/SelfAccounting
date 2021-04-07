<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ExecuteAfterMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute_after_migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add records to the database after migration is finished';

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
        DB::table("migrations")
            ->where("migration", "9999_99_99_999999_execute_after_migration")
            ->delete();
        $this->info("Deleted migration from database");

        Artisan::call("migrate", []);
        $this->info("Entered the values");

        return 0;
    }
}
