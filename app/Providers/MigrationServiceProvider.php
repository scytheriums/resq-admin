<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Set the migrations table name
        $this->app->when(MigrationRepositoryInterface::class)
            ->needs('$table')
            ->giveConfig('database.migrations.table', 'admin_migrations');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
