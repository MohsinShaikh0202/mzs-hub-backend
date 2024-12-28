<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for your application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')  // API routes will be prefixed with "api"
             ->middleware('api') // "api" middleware group will be applied
             ->namespace($this->namespace)  // Default namespace for API controllers
             ->group(base_path('routes/api.php'));  // Routes from "routes/api.php"
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateful.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')  // "web" middleware group will be applied
             ->namespace($this->namespace)  // Default namespace for web controllers
             ->group(base_path('routes/web.php'));  // Routes from "routes/web.php"
    }
}
