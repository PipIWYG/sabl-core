<?php
namespace PipIWYG\SablCore;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
     * Console Command Definitions
     *
     * @var array
     */
    protected $commands = [];

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

	// Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
