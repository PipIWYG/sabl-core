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
        // Routes
        if (!$this->app->routesAreCached()) {
            // Load routes from...
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        }

//        if (app()->runningInConsole()) {
//            $this->registerMigrations();
//
//            $this->publishes([
//                __DIR__.'/../database/migrations' => database_path('migrations'),
//            ], 'sanctum-migrations');
//
//            $this->publishes([
//                __DIR__.'/../config/sanctum.php' => config_path('sanctum.php'),
//            ], 'sanctum-config');
//        }
//
//        $this->defineRoutes();
//        $this->configureGuard();
//        $this->configureMiddleware();


	// Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
