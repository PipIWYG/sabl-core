<?php
namespace PipIWYG\SablCore;

use Illuminate\Support\ServiceProvider;

/**
 * Class SablCoreServiceProvider
 *
 * @package PipIWYG\SablCore
 * @author Quintin Stoltz<qstoltz@gmail.com>
 */
class SablCoreServiceProvider
    extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Check for Console Mode
        if (app()->runningInConsole()) {
            // Register Package Migrations
            $this->registerMigrations();
        }
        // Load and Define Package Routes
        $this->defineRoutes();
    }

    /**
     * Register Package migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        // Load Migrations from...
        return $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Define the package routes.
     *
     * @return void
     */
    protected function defineRoutes()
    {
        // Check for cached routes
        if (app()->routesAreCached()) {
            return;
        }
        // Load routes from...
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }
}
