<?php

namespace Devel8\LaravelActionTracker;

use Illuminate\Support\ServiceProvider;

class ActionTrackerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/action-tracker.php' => config_path('action-tracker.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/action-tracker.php', 'action-tracker'
        );
    }
}
