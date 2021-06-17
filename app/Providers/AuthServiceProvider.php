<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        "App\Income" => "App\Policies\IncomeOutcomePolicy",
        "App\Outcome" => "App\Policies\IncomeOutcomePolicy",
        "App\Backup" => "App\Policies\BackupPolicy",
        "App\Report" => "App\Policies\ReportPolicy"
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
